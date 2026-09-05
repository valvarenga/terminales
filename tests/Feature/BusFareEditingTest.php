<?php
namespace Tests\Feature;

use App\Models\Autobuses;
use App\Services\RouteFinder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class BusFareEditingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        DB::table('departamentos')->insert(['id' => 1, 'nombre' => 'Norte', 'slug' => 'norte']);
        foreach ([1, 2, 3] as $id) {
            DB::table('municipios')->insert(['id' => $id, 'nombre' => 'Municipio '.$id, 'slug' => 'm-'.$id, 'departamento_id' => 1]);
        }
        foreach ([1, 2] as $id) {
            DB::table('terminales')->insert(['id' => $id, 'nombre' => 'Terminal '.$id, 'slug' => 't-'.$id, 'departamento_id' => 1, 'municipio_id' => 1, 'hora_apertura' => '05:00', 'hora_cierre' => '20:00', 'url_T' => '']);
        }
    }

    private function data(): array
    {
        return ['nombre' => 'Servicio Nuevo', 'placa' => 'ABC123', 'municipio_origen_id' => 1, 'municipio_destino_id' => 2, 'hora_salida' => '06:00', 'hora_llegada' => '08:00', 'terminal' => 1, 'categoria' => 'Expreso', 'tarifa' => '25.50'];
    }

    public function test_create_and_full_edit_preserve_url_and_save_terminal_and_fare(): void
    {
        $this->withSession(['admin_authenticated' => true])->post(route('autobus'), $this->data())->assertSessionHasNoErrors()->assertRedirect(route('newbus'));
        $bus = Autobuses::firstOrFail();
        $slug = $bus->slug;
        $this->get(route('autobus.edit', $bus))->assertOk()->assertSee('name="tarifa"', false)->assertSee('name="hora_salida"', false)->assertSee('name="terminal"', false);
        $changed = array_replace($this->data(), ['nombre' => 'Otro nombre', 'placa' => 'XYZ789', 'municipio_origen_id' => 2, 'municipio_destino_id' => 3, 'hora_salida' => '10:00', 'hora_llegada' => '12:00', 'terminal' => 2, 'categoria' => 'Ruteado', 'tarifa' => '0']);
        $this->put(route('autobus.update', $bus), $changed)->assertSessionHasNoErrors()->assertRedirect(route('autobuses.list'));
        $bus->refresh();
        $this->assertSame($slug, $bus->slug);
        $this->assertSame('Otro nombre', $bus->nombre);
        $this->assertSame('XYZ789', $bus->placa);
        $this->assertSame('Ruteado', $bus->categoria);
        $this->assertSame('Municipio 2', $bus->origen);
        $this->assertSame('Municipio 3', $bus->destino);
        $this->assertStringStartsWith('10:00', $bus->hora_salida);
        $this->assertStringStartsWith('12:00', $bus->hora_llegada);
        $this->assertSame('0.00', $bus->tarifa);
        $this->assertSame([2], $bus->terminales()->pluck('terminales.id')->all());
        $this->assertDatabaseHas('audit_logs', ['entity' => 'autobuses', 'entity_id' => $bus->id, 'action' => 'terminales_actualizadas']);
        $this->put(route('autobus.update', $bus), array_replace($changed, ['tarifa' => '']))->assertSessionHasNoErrors();
        $this->assertNull($bus->refresh()->tarifa);
    }

    public function test_invalid_fares_and_mismatched_terminal_do_not_create_services(): void
    {
        $this->withSession(['admin_authenticated' => true]);
        foreach (['-1', '1.234', '1000000', 'abc'] as $fare) {
            $this->post(route('autobus'), array_replace($this->data(), ['tarifa' => $fare]))->assertSessionHasErrors('tarifa');
        }
        DB::table('departamentos')->insert(['id' => 2, 'nombre' => 'Sur', 'slug' => 'sur']);
        DB::table('terminales')->where('id', 1)->update(['departamento_id' => 2]);
        $this->post(route('autobus'), $this->data())->assertSessionHasErrors('terminal');
        $this->assertDatabaseCount('autobuses', 0);
    }

    public function test_failed_audit_rolls_back_service_and_terminal_changes(): void
    {
        $this->withSession(['admin_authenticated' => true])->post(route('autobus'), $this->data())->assertSessionHasNoErrors();
        $bus = Autobuses::firstOrFail();
        \App\Models\AuditLog::creating(function () {
            throw new \RuntimeException('Audit unavailable');
        });
        try {
            $this->put(route('autobus.update', $bus), array_replace($this->data(), ['nombre' => 'No guardar', 'terminal' => 2]))->assertStatus(500);
            $this->assertSame('Servicio Nuevo', $bus->refresh()->nombre);
            $this->assertSame([1], $bus->terminales()->pluck('terminales.id')->all());
        } finally {
            \App\Models\AuditLog::flushEventListeners();
        }
    }

    public function test_public_itinerary_total_requires_all_fares_and_accepts_free_legs(): void
    {
        $this->withSession(['admin_authenticated' => true])->post(route('autobus'), $this->data())->assertSessionHasNoErrors();
        $this->post(route('autobus'), array_replace($this->data(), ['municipio_origen_id' => 2, 'municipio_destino_id' => 3, 'hora_salida' => '09:00', 'hora_llegada' => '11:00', 'tarifa' => '10.25']))->assertSessionHasNoErrors();
        $this->assertSame('35.75', app(RouteFinder::class)->find(1, 3)->first()['tarifa_total']);
        $this->get(route('buscar.index', ['origen_id' => 1, 'destino_id' => 3]))->assertOk()->assertSee('C$ 35.75');
        Autobuses::orderByDesc('id')->first()->update(['tarifa' => null]);
        $this->assertNull(app(RouteFinder::class)->find(1, 3)->first()['tarifa_total']);
        $this->get(route('buscar.index', ['origen_id' => 1, 'destino_id' => 3]))->assertOk()->assertSee('Por confirmar (faltan tarifas)');
        Autobuses::orderByDesc('id')->first()->update(['tarifa' => 0]);
        $this->assertSame('25.50', app(RouteFinder::class)->find(1, 3)->first()['tarifa_total']);
    }
}
