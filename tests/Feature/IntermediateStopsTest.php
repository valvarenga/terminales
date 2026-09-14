<?php

namespace Tests\Feature;

use App\Models\Autobuses;
use App\Services\RouteFinder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class IntermediateStopsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        DB::table('departamentos')->insert(['id' => 1, 'nombre' => 'Madriz', 'slug' => 'madriz']);
        DB::table('municipios')->insert([
            ['id' => 1, 'nombre' => 'Somoto', 'slug' => 'somoto', 'departamento_id' => 1, 'latitud' => 13.48, 'longitud' => -86.58],
            ['id' => 2, 'nombre' => 'San Lucas', 'slug' => 'san-lucas', 'departamento_id' => 1, 'latitud' => 13.41, 'longitud' => -86.61],
            ['id' => 3, 'nombre' => 'Las Sabanas', 'slug' => 'las-sabanas', 'departamento_id' => 1, 'latitud' => 13.34, 'longitud' => -86.62],
            ['id' => 4, 'nombre' => 'San José de Cusmapa', 'slug' => 'san-jose-de-cusmapa', 'departamento_id' => 1, 'latitud' => 13.29, 'longitud' => -86.65],
        ]);
        DB::table('terminales')->insert(['id' => 1, 'nombre' => 'Terminal Somoto', 'slug' => 'terminal-somoto', 'departamento_id' => 1, 'municipio_id' => 1, 'hora_apertura' => '05:00', 'hora_cierre' => '20:00', 'url_T' => '']);
        $this->withSession(['admin_authenticated' => true]);
    }

    private function serviceData(array $overrides = []): array
    {
        return array_replace_recursive([
            'nombre' => 'Bus de Cusmapa', 'placa' => 'MAD-01', 'terminal' => 1, 'categoria' => 'Ruteado',
            'paradas' => [
                ['municipio_id' => 1, 'hora_paso' => '06:00', 'tarifa_acumulada' => '0'],
                ['municipio_id' => 2, 'hora_paso' => '06:30', 'tarifa_acumulada' => '20'],
                ['municipio_id' => 3, 'hora_paso' => '07:00', 'tarifa_acumulada' => '40'],
                ['municipio_id' => 4, 'hora_paso' => '07:30', 'tarifa_acumulada' => '60'],
            ],
        ], $overrides);
    }

    public function test_passengers_can_board_at_any_intermediate_stop(): void
    {
        $this->post(route('autobus'), $this->serviceData())->assertSessionHasNoErrors();
        $bus = Autobuses::with('paradas')->firstOrFail();
        $this->assertSame([1, 2, 3, 4], $bus->paradas->pluck('municipio_id')->all());
        $this->get(route('autobus.edit', $bus))->assertOk()->assertSee('Agregar parada')->assertSee('Vista del recorrido')->assertSee('paradas[3][municipio_id]', false);
        $this->assertSame('40.00', app(RouteFinder::class)->find(2, 4)->first()['tarifa_total']);
        $this->assertSame('20.00', app(RouteFinder::class)->find(3, 4)->first()['tarifa_total']);
        $this->assertTrue(app(RouteFinder::class)->find(4, 2)->isEmpty());
        $this->get(route('buscar.index', ['origen_id' => 2, 'destino_id' => 4]))
            ->assertOk()->assertSee('Bus de Cusmapa')->assertSee('C$ 40.00')->assertSee('Las Sabanas');
    }

    public function test_stop_sequence_validation_rejects_duplicates_time_and_fare_regressions(): void
    {
        $duplicate = $this->serviceData(); $duplicate['paradas'][2]['municipio_id'] = 2;
        $this->post(route('autobus'), $duplicate)->assertSessionHasErrors('paradas.2.municipio_id');
        $time = $this->serviceData(); $time['paradas'][2]['hora_paso'] = '06:15';
        $this->post(route('autobus'), $time)->assertSessionHasErrors('paradas.2.hora_paso');
        $fare = $this->serviceData(); $fare['paradas'][2]['tarifa_acumulada'] = '10';
        $this->post(route('autobus'), $fare)->assertSessionHasErrors('paradas.2.tarifa_acumulada');
        $this->assertDatabaseCount('autobuses', 0);
    }

    public function test_search_hides_transfer_when_the_next_bus_can_be_boarded_at_the_original_origin(): void
    {
        $first = $this->serviceData(['nombre' => 'Rocha', 'placa' => 'MAD-10']);
        $first['paradas'] = [
            ['municipio_id' => 1, 'hora_paso' => '06:00', 'tarifa_acumulada' => '0'],
            ['municipio_id' => 2, 'hora_paso' => '06:30', 'tarifa_acumulada' => '10'],
        ];
        $second = $this->serviceData(['nombre' => 'Gloria', 'placa' => 'MAD-11']);
        $second['paradas'] = [
            ['municipio_id' => 1, 'hora_paso' => '07:00', 'tarifa_acumulada' => '0'],
            ['municipio_id' => 2, 'hora_paso' => '07:30', 'tarifa_acumulada' => '8'],
            ['municipio_id' => 3, 'hora_paso' => '08:15', 'tarifa_acumulada' => '13'],
        ];

        $this->post(route('autobus'), $first)->assertSessionHasNoErrors();
        $this->post(route('autobus'), $second)->assertSessionHasNoErrors();

        $routes = app(RouteFinder::class)->find(1, 3);

        $this->assertCount(1, $routes);
        $this->assertSame(0, $routes->first()['transbordos']);
        $this->assertSame('Gloria', $routes->first()['tramos'][0]->nombre);
        $this->assertSame('07:00', substr((string) $routes->first()['salida'], 0, 5));
    }
}
