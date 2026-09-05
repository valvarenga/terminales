<?php

namespace Tests\Feature;

use App\Models\SugerenciaTerminal;
use App\Models\AuditLog;
use App\Models\Terminales;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SugerenciaRevisionTest extends TestCase
{
    use RefreshDatabase;

    private function terminal(): Terminales
    {
        $terminal = new Terminales;
        $terminal->nombre = 'Terminal prueba';
        $terminal->slug = 'terminal-prueba';
        $terminal->hora_apertura = '05:00';
        $terminal->hora_cierre = '19:00';
        $terminal->save();
        return $terminal;
    }

    private function admin(): static
    {
        return $this->withSession(['admin_authenticated' => true, 'admin_actor' => 'editor-prueba', 'admin_role' => 'editor']);
    }

    public function test_guest_cannot_review_or_view_suggestions(): void
    {
        $suggestion = SugerenciaTerminal::create(['nombre_terminal' => 'Propuesta']);
        $this->get('/admin/sugerencias-terminales')->assertRedirectContains('/admin/login');
        $this->patch('/admin/sugerencias-terminales/'.$suggestion->id, ['estado' => 'rechazada', 'motivo_revision' => 'Duplicada'])->assertRedirectContains('/admin/login');
        $this->assertSame('pendiente', $suggestion->fresh()->estado);
    }

    public function test_approval_links_terminal_without_publishing_photo_and_cannot_be_reviewed_twice(): void
    {
        $terminal = $this->terminal();
        $suggestion = SugerenciaTerminal::create(['nombre_terminal' => 'Propuesta', 'foto' => 'privada.jpg']);
        $this->admin()->patch('/admin/sugerencias-terminales/'.$suggestion->id, ['estado' => 'aprobada', 'terminal_id' => $terminal->id])->assertSessionHasNoErrors()->assertRedirect();
        $this->assertSame('aprobada', $suggestion->fresh()->estado);
        $this->assertSame($terminal->id, $suggestion->fresh()->terminal_id);
        $this->assertSame('editor-prueba', $suggestion->fresh()->revisada_por);
        $this->assertNull($terminal->fresh()->url_T);
        $this->admin()->patch('/admin/sugerencias-terminales/'.$suggestion->id, ['estado' => 'rechazada', 'motivo_revision' => 'Segunda revisión'])->assertSessionHasErrors('estado');
        $this->assertSame('aprobada', $suggestion->fresh()->estado);
    }

    public function test_rejection_needs_reason_and_approval_needs_terminal(): void
    {
        $suggestion = SugerenciaTerminal::create(['nombre_terminal' => 'Propuesta']);
        $url = '/admin/sugerencias-terminales/'.$suggestion->id;
        $this->admin()->patch($url, ['estado' => 'rechazada'])->assertSessionHasErrors('motivo_revision');
        $this->admin()->patch($url, ['estado' => 'aprobada'])->assertSessionHasErrors('terminal_id');
        $this->admin()->patch($url, ['estado' => 'rechazada', 'motivo_revision' => 'Ya existe'])->assertSessionHasNoErrors();
        $this->assertSame('rechazada', $suggestion->fresh()->estado);
        $this->admin()->get('/admin/sugerencias-terminales?estado=rechazada')->assertOk()->assertSee('Ya existe');
    }

    public function test_photo_publication_requires_explicit_replacement_and_keeps_private_original(): void
    {
        Storage::fake('local');
        Storage::fake('public');
        $path = 'sugerencias-terminales/terminal.png';
        Storage::disk('local')->put($path, base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+jRZkAAAAASUVORK5CYII='));
        $terminal = $this->terminal();
        $terminal->url_T = '/storage/actual.jpg';
        $terminal->save();
        $suggestion = SugerenciaTerminal::create(['nombre_terminal' => 'Foto propuesta', 'foto' => $path]);
        $payload = ['estado' => 'aprobada', 'terminal_id' => $terminal->id, 'publicar_foto' => 1];
        $url = '/admin/sugerencias-terminales/'.$suggestion->id;
        $this->admin()->patch($url, $payload)->assertSessionHasErrors('reemplazar_foto');
        $this->assertSame('pendiente', $suggestion->fresh()->estado);
        $this->assertSame('/storage/actual.jpg', $terminal->fresh()->url_T);
        $this->admin()->patch($url, $payload + ['reemplazar_foto' => 1])->assertSessionHasNoErrors();
        $this->assertNotSame('/storage/actual.jpg', $terminal->fresh()->url_T);
        Storage::disk('local')->assertExists($path);
        $this->assertCount(1, Storage::disk('public')->allFiles('imagenes/terminal'));
    }

    public function test_missing_photo_does_not_approve_or_change_terminal(): void
    {
        Storage::fake('local');
        Storage::fake('public');
        $terminal = $this->terminal();
        $suggestion = SugerenciaTerminal::create(['nombre_terminal' => 'Sin archivo', 'foto' => 'sugerencias-terminales/no-existe.jpg']);
        $this->admin()->patch('/admin/sugerencias-terminales/'.$suggestion->id, ['estado' => 'aprobada', 'terminal_id' => $terminal->id, 'publicar_foto' => 1])->assertSessionHasErrors('publicar_foto');
        $this->assertSame('pendiente', $suggestion->fresh()->estado);
        $this->assertNull($terminal->fresh()->url_T);
        $this->assertCount(0, Storage::disk('public')->allFiles());
    }

    public function test_list_filters_pending_and_paginates(): void
    {
        for ($i = 1; $i <= 13; $i++) {
            SugerenciaTerminal::create(['nombre_terminal' => 'Propuesta '.$i]);
        }
        $rejected = SugerenciaTerminal::create(['nombre_terminal' => 'Rechazada visible solo con filtro']);
        $rejected->estado = 'rechazada';
        $rejected->save();
        $this->admin()->get('/admin/sugerencias-terminales')->assertOk()->assertDontSee('Rechazada visible solo con filtro')->assertViewHas('sugerencias', fn ($items) => $items->count() === 12 && $items->total() === 13);
        $this->admin()->get('/admin/sugerencias-terminales?estado=rechazada')->assertOk()->assertSee('Rechazada visible solo con filtro');
    }

    public function test_public_submission_rolls_back_record_and_photo_if_audit_fails(): void
    {
        Storage::fake('local');
        AuditLog::creating(function () {
            throw new \RuntimeException('Simulated audit failure');
        });
        $photo = UploadedFile::fake()->createWithContent('terminal.png', base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+jRZkAAAAASUVORK5CYII='));
        $this->post('/sugerencias-terminales', ['nombre_terminal' => 'No debe persistir', 'foto' => $photo])->assertStatus(500);
        $this->assertDatabaseMissing('sugerencias_terminales', ['nombre_terminal' => 'No debe persistir']);
        $this->assertCount(0, Storage::disk('local')->allFiles('sugerencias-terminales'));
    }
}
