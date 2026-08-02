<?php

namespace Tests\Unit;

use App\Models\Autobuses;
use App\Services\RouteFinder;
use Illuminate\Support\Collection;
use Tests\TestCase;

class RouteFinderTest extends TestCase
{
    public function test_it_finds_direct_and_connected_routes_in_the_right_order(): void
    {
        $routes = (new RouteFinder())->findFromServices($this->services([
            $this->bus(1, 2, '07:00', '08:00'),
            $this->bus(1, 3, '06:00', '06:30'),
            $this->bus(3, 2, '06:45', '07:30'),
        ]), 1, 2);

        $this->assertCount(2, $routes);
        $this->assertSame(0, $routes[0]['transbordos']);
        $this->assertSame(1, $routes[1]['transbordos']);
    }

    public function test_it_rejects_connections_that_leave_before_arrival_and_prevents_cycles(): void
    {
        $routes = (new RouteFinder())->findFromServices($this->services([
            $this->bus(1, 3, '07:00', '08:00'),
            $this->bus(3, 2, '07:45', '09:00'),
            $this->bus(3, 1, '08:30', '09:00'),
            $this->bus(1, 2, '10:00', '11:00'),
        ]), 1, 2);

        $this->assertCount(1, $routes);
        $this->assertCount(1, $routes[0]['tramos']);
        $this->assertSame('11:00:00', $routes[0]['llegada']);
    }

    public function test_it_allows_at_most_two_transfers_and_orders_equal_options_by_arrival(): void
    {
        $routes = (new RouteFinder())->findFromServices($this->services([
            $this->bus(1, 3, '06:00', '06:30'), $this->bus(3, 4, '06:45', '07:00'), $this->bus(4, 2, '07:10', '08:30'),
            $this->bus(1, 5, '06:00', '06:20'), $this->bus(5, 6, '06:30', '07:00'), $this->bus(6, 2, '07:10', '08:00'),
            $this->bus(1, 7, '06:00', '06:10'), $this->bus(7, 8, '06:20', '06:30'), $this->bus(8, 9, '06:40', '06:50'), $this->bus(9, 2, '07:00', '07:30'),
        ]), 1, 2);

        $this->assertCount(2, $routes);
        $this->assertSame('08:00:00', $routes[0]['llegada']);
        $this->assertSame(2, $routes[0]['transbordos']);
    }

    private function services(array $buses): Collection
    {
        return collect($buses)->groupBy('municipio_origen_id');
    }

    private function bus(int $origin, int $destination, string $departure, string $arrival): Autobuses
    {
        return new Autobuses([
            'municipio_origen_id' => $origin,
            'municipio_destino_id' => $destination,
            'hora_salida' => $departure.':00',
            'hora_llegada' => $arrival.':00',
        ]);
    }
}
