<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CatalogEditingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withSession(['admin_authenticated' => true, 'admin_username' => 'editor-test', 'admin_role' => 'admin']);
        DB::table('departamentos')->insert([
            ['id' => 1, 'nombre' => 'Norte', 'slug' => 'norte', 'url' => '/original.jpg'],
            ['id' => 2, 'nombre' => 'Sur', 'slug' => 'sur', 'url' => '/sur.jpg'],
        ]);
        DB::table('municipios')->insert([
            ['id' => 1, 'nombre' => 'Pueblo', 'slug' => 'pueblo', 'departamento_id' => 1],
            ['id' => 2, 'nombre' => 'Ciudad', 'slug' => 'ciudad', 'departamento_id' => 2],
        ]);
        DB::table('terminales')->insert(['id' => 1, 'nombre' => 'Central', 'slug' => 'central', 'departamento_id' => 1, 'municipio_id' => 1, 'hora_apertura' => '06:00:00', 'hora_cierre' => '18:00:00', 'url_T' => '/original.jpg']);
    }

    public function test_department_photo_can_be_replaced_without_changing_public_slug(): void
    {
        Storage::fake();
        $photo = UploadedFile::fake()->createWithContent('nueva.png', base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+jRZkAAAAASUVORK5CYII='));
        $this->put(route('departamento.update', 'norte'), ['nombre' => 'Nuevo Norte', 'file_D' => $photo])->assertSessionHasNoErrors();
        $row = DB::table('departamentos')->find(1);
        $this->assertSame('norte', $row->slug);
        $this->assertNotSame('/original.jpg', $row->url);
        Storage::assertExists('public/imagenes/departamento/'.$photo->hashName());
    }

    public function test_invalid_photo_does_not_update_department(): void
    {
        $this->put(route('departamento.update', 'norte'), ['nombre' => 'Nuevo Norte', 'file_D' => UploadedFile::fake()->create('documento.pdf')])->assertSessionHasErrors('file_D');
        $this->assertDatabaseHas('departamentos', ['id' => 1, 'nombre' => 'Norte']);
    }

    public function test_municipality_cannot_be_moved_while_it_has_terminals(): void
    {
        $this->put(route('municipio.update', 'pueblo'), ['nombre' => 'Pueblo', 'departamento_id' => 2])->assertSessionHasErrors('departamento_id');
        $this->assertDatabaseHas('municipios', ['id' => 1, 'departamento_id' => 1]);
    }

    public function test_terminal_rejects_mismatched_department_and_municipality(): void
    {
        $this->put(route('terminal.update', 'central'), $this->terminalData(['municipio' => 2]))->assertSessionHasErrors('municipio');
        $this->assertDatabaseHas('terminales', ['id' => 1, 'municipio_id' => 1]);
    }

    public function test_terminal_cannot_be_moved_when_services_are_associated(): void
    {
        $bus = DB::table('autobuses')->insertGetId(['nombre' => 'Bus', 'slug' => 'bus', 'categoria' => 'Expreso', 'origen' => 'Pueblo', 'destino' => 'Ciudad', 'hora_salida' => '07:00', 'hora_llegada' => '09:00']);
        DB::table('autobus_terminal')->insert(['autobus_id' => $bus, 'terminal_id' => 1]);
        $this->put(route('terminal.update', 'central'), $this->terminalData(['departamento' => 2, 'municipio' => 2]))->assertSessionHasErrors('municipio');
        $this->assertDatabaseHas('terminales', ['id' => 1, 'municipio_id' => 1]);
    }

    public function test_terminal_can_remove_photo_and_edit_times_without_changing_slug(): void
    {
        $this->put(route('terminal.update', 'central'), $this->terminalData(['remove_photo' => 1]))->assertSessionHasNoErrors();
        $this->assertDatabaseHas('terminales', ['id' => 1, 'slug' => 'central', 'nombre' => 'Central renovada', 'url_T' => null, 'hora_apertura' => '05:30']);
    }

    public function test_edit_views_render_and_preserve_rejected_input(): void
    {
        $this->get(route('departamento.ver', 'norte'))->assertOk()->assertSee('multipart/form-data', false);
        $this->get(route('municipio.edit', 'pueblo'))->assertOk()->assertSee('file_M', false);
        $this->withSession(['_old_input' => ['nombre' => 'Nombre pendiente', 'departamento' => 2, 'municipio' => 2]])
            ->get(route('terminal.edit', 'central'))->assertOk()->assertSee('Nombre pendiente')->assertSee('value="06:00"', false)->assertSee('Ciudad');
    }

    public function test_municipality_rename_updates_service_labels_and_preserves_slug(): void
    {
        $first = DB::table('autobuses')->insertGetId(['nombre' => 'Ida', 'slug' => 'ida', 'categoria' => 'Expreso', 'origen' => 'Pueblo', 'destino' => 'Ciudad', 'municipio_origen_id' => 1, 'municipio_destino_id' => 2, 'hora_salida' => '07:00', 'hora_llegada' => '09:00']);
        $second = DB::table('autobuses')->insertGetId(['nombre' => 'Vuelta', 'slug' => 'vuelta', 'categoria' => 'Expreso', 'origen' => 'Ciudad', 'destino' => 'Pueblo', 'municipio_origen_id' => 2, 'municipio_destino_id' => 1, 'hora_salida' => '10:00', 'hora_llegada' => '12:00']);
        $this->put(route('municipio.update', 'pueblo'), ['nombre' => 'Pueblo Nuevo', 'departamento_id' => 1])->assertSessionHasNoErrors();
        $this->assertDatabaseHas('municipios', ['id' => 1, 'nombre' => 'Pueblo Nuevo', 'slug' => 'pueblo']);
        $this->assertDatabaseHas('autobuses', ['id' => $first, 'origen' => 'Pueblo Nuevo', 'destino' => 'Ciudad']);
        $this->assertDatabaseHas('autobuses', ['id' => $second, 'origen' => 'Ciudad', 'destino' => 'Pueblo Nuevo']);
    }

    public function test_invalid_photo_cannot_remove_existing_photo_or_change_fields(): void
    {
        $this->put(route('terminal.update', 'central'), $this->terminalData(['remove_photo' => 1, 'file_T' => UploadedFile::fake()->create('documento.pdf')]))->assertSessionHasErrors('file_T');
        $this->assertDatabaseHas('terminales', ['id' => 1, 'nombre' => 'Central', 'url_T' => '/original.jpg']);
        $this->put(route('municipio.update', 'pueblo'), ['nombre' => 'Pendiente', 'departamento_id' => 1, 'file_M' => UploadedFile::fake()->create('documento.pdf')])->assertSessionHasErrors('file_M');
        $this->assertDatabaseHas('municipios', ['id' => 1, 'nombre' => 'Pueblo']);
    }

    private function terminalData(array $overrides = []): array
    {
        return array_merge(['nombre' => 'Central renovada', 'departamento' => 1, 'municipio' => 1, 'hora_apertura' => '05:30', 'hora_cierre' => '19:00'], $overrides);
    }
}
