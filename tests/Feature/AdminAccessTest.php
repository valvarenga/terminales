<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('admin.username', 'admin-test');
        config()->set('admin.password_hash', Hash::make('password-for-tests'));
    }

    public function test_create_departamento_requires_admin_access(): void
    {
        $response = $this->get('/newdepartamento');

        $response->assertRedirectContains('/admin/login');
    }

    public function test_public_navigation_never_exposes_administration_access(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertDontSee(route('admin.login'), false)
            ->assertDontSee(route('admin.dashboard'), false);

        $this->withSession(['admin_authenticated' => true])
            ->get('/')
            ->assertOk()
            ->assertDontSee(route('admin.login'), false)
            ->assertDontSee(route('admin.dashboard'), false);
    }

    public function test_admin_login_allows_access_to_protected_route(): void
    {
        $response = $this->post('/admin/login', [
            'username' => 'admin-test',
            'password' => 'password-for-tests',
            'redirect' => '/newdepartamento',
        ]);

        $response->assertRedirect('/newdepartamento');
    }

    public function test_default_credentials_cannot_access_the_panel(): void
    {
        $response = $this->post('/admin/login', [
            'username' => 'admin',
            'password' => 'admin123',
        ]);

        $response->assertSessionHasErrors('username');
        $this->assertFalse((bool) session('admin_authenticated'));
    }

    public function test_login_never_redirects_to_an_external_url(): void
    {
        $response = $this->post('/admin/login', [
            'username' => 'admin-test',
            'password' => 'password-for-tests',
            'redirect' => '//attacker.example',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
    }
}
