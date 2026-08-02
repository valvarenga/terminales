<?php

namespace Tests\Feature;

use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    public function test_create_departamento_requires_admin_access(): void
    {
        $response = $this->get('/newdepartamento');

        $response->assertRedirectContains('/admin/login');
    }

    public function test_admin_login_allows_access_to_protected_route(): void
    {
        $response = $this->post('/admin/login', [
            'username' => 'admin',
            'password' => 'admin123',
            'redirect' => '/newdepartamento',
        ]);

        $response->assertRedirect('/newdepartamento');
    }
}
