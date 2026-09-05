<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SecurityAdministrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_does_not_redirect_backslash_path_to_an_external_host(): void
    {
        config(['admin.username' => 'security-admin', 'admin.password_hash' => Hash::make('Password123456')]);
        $response = $this->post('/admin/login', [
            'username' => 'security-admin',
            'password' => 'Password123456',
            'redirect' => '/\\example.com',
        ]);
        $response->assertRedirect();
        $this->assertSame('localhost', parse_url($response->headers->get('Location'), PHP_URL_HOST));
    }

    public function test_administrative_mutation_rolls_back_when_audit_fails(): void
    {
        AuditLog::creating(function () {
            throw new \RuntimeException('Simulated audit failure');
        });
        $this->withSession(['admin_authenticated' => true, 'admin_actor' => 'admin', 'admin_role' => 'admin'])
            ->post('/admin/usuarios', [
                'name' => 'Temporary editor', 'email' => 'temporary@example.test',
                'role' => 'editor', 'is_active' => 1,
                'password' => 'Password123456', 'password_confirmation' => 'Password123456',
            ])->assertStatus(500);
        $this->assertDatabaseMissing('users', ['email' => 'temporary@example.test']);
    }

    public function test_editor_cannot_promote_self_via_user_update(): void
    {
        $user = User::create(['name' => 'Editor', 'email' => 'editor-security@example.test', 'password' => Hash::make('Password123456'), 'role' => 'editor', 'is_active' => true]);
        $this->withSession(['admin_authenticated' => true, 'admin_user_id' => $user->id, 'admin_session_version' => 1, 'admin_role' => 'editor'])
            ->put('/admin/usuarios/'.$user->id, ['name' => 'Editor', 'email' => $user->email, 'role' => 'admin', 'is_active' => 1])->assertForbidden();
        $this->assertSame('editor', $user->fresh()->role);
    }

    public function test_audit_never_stores_password_hashes(): void
    {
        $user = User::create(['name' => 'Administrator', 'email' => 'audit-security@example.test', 'password' => Hash::make('Password123456'), 'role' => 'admin', 'is_active' => true]);
        $firstHash = $user->password;
        $user->password = Hash::make('AnotherPassword123');
        $user->save();
        $logs = AuditLog::where('entity', 'users')->where('entity_id', $user->id)->get();
        $this->assertStringNotContainsString($firstHash, $logs->toJson());
        $this->assertStringNotContainsString($user->password, $logs->toJson());
        $this->assertTrue($logs->contains('action', 'contraseña_actualizada'));
    }

    public function test_previous_session_version_loses_access(): void
    {
        $user = User::create(['name' => 'Editor', 'email' => 'stale-security@example.test', 'password' => Hash::make('Password123456'), 'role' => 'editor', 'is_active' => true]);
        $user->session_version = 2;
        $user->save();
        $this->withSession(['admin_authenticated' => true, 'admin_user_id' => $user->id, 'admin_session_version' => 1, 'admin_role' => 'editor'])
            ->get('/newdepartamento')->assertRedirect(route('admin.login'));
        $this->assertFalse((bool) session('admin_authenticated'));
    }
}
