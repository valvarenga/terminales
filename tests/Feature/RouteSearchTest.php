<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RouteSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_validates_origin_and_destination(): void
    {
        $this->get(route('buscar.index'))
            ->assertSessionHasErrors(['origen_id', 'destino_id']);
    }

    public function test_it_displays_a_registered_direct_route(): void
    {
        $this->seedLocationData();

        $response = $this->get(route('buscar.index', ['origen_id' => 1, 'destino_id' => 2]));

        $response->assertOk()
            ->assertSee('Las Sabanas')
            ->assertSee('Estelí')
            ->assertSee('Ruta Norte')
            ->assertSee('Ruta directa');
    }

    public function test_it_shows_an_empty_state_when_no_itinerary_exists(): void
    {
        DB::table('municipios')->insert([
            ['id' => 1, 'nombre' => 'Las Sabanas', 'slug' => 'las-sabanas', 'url_M' => '/placeholder.jpg', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nombre' => 'Estelí', 'slug' => 'esteli', 'url_M' => '/placeholder.jpg', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $this->get(route('buscar.index', ['origen_id' => 1, 'destino_id' => 2]))
            ->assertOk()
            ->assertSee('No hay rutas disponibles');
    }

    private function seedLocationData(): void
    {
        DB::table('municipios')->insert([
            ['id' => 1, 'nombre' => 'Las Sabanas', 'slug' => 'las-sabanas', 'url_M' => '/placeholder.jpg', 'latitud' => 13.35, 'longitud' => -86.62, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nombre' => 'Estelí', 'slug' => 'esteli', 'url_M' => '/placeholder.jpg', 'latitud' => 13.09, 'longitud' => -86.35, 'created_at' => now(), 'updated_at' => now()],
        ]);
        DB::table('autobuses')->insert([
            'nombre' => 'Ruta Norte', 'slug' => 'ruta-norte', 'categoria' => 'Expreso', 'origen' => 'Las Sabanas', 'municipio_origen_id' => 1,
            'hora_salida' => '07:00:00', 'destino' => 'Estelí', 'municipio_destino_id' => 2, 'hora_llegada' => '10:00:00', 'created_at' => now(), 'updated_at' => now(),
        ]);
    }
}
