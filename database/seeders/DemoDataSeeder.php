<?php

namespace Database\Seeders;

use App\Models\Autobuses;
use App\Models\Departamentos;
use App\Models\Municipios;
use App\Models\Terminales;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) {
            throw new RuntimeException('Los datos de demostración no se pueden cargar en producción.');
        }

        DB::transaction(function (): void {
            $departments = $this->departments();
            $municipalities = $this->municipalities($departments);
            $terminals = $this->terminals($departments, $municipalities);

            foreach ($this->services() as $service) {
                $bus = Autobuses::firstOrCreate(
                    ['slug' => $service['slug']],
                    [
                        'nombre' => $service['nombre'],
                        'categoria' => $service['categoria'],
                        'placa' => $service['placa'],
                        'origen' => $municipalities[$service['stops'][0][0]]->nombre,
                        'municipio_origen_id' => $municipalities[$service['stops'][0][0]]->id,
                        'hora_salida' => $service['stops'][0][1],
                        'destino' => $municipalities[$service['stops'][array_key_last($service['stops'])][0]]->nombre,
                        'municipio_destino_id' => $municipalities[$service['stops'][array_key_last($service['stops'])][0]]->id,
                        'hora_llegada' => $service['stops'][array_key_last($service['stops'])][1],
                        'tarifa' => $service['stops'][array_key_last($service['stops'])][2],
                    ]
                );

                $bus->terminales()->syncWithoutDetaching([$terminals[$service['terminal']]->id]);

                foreach ($service['stops'] as $position => [$municipality, $time, $fare]) {
                    $bus->paradas()->updateOrCreate(
                        ['posicion' => $position],
                        [
                            'municipio_id' => $municipalities[$municipality]->id,
                            'hora_paso' => $time,
                            'tarifa_acumulada' => $fare,
                        ]
                    );
                }
            }
        });
    }

    private function departments(): array
    {
        $items = [
            'esteli' => 'Estelí',
            'madriz' => 'Madriz',
            'nueva-segovia' => 'Nueva Segovia',
            'managua' => 'Managua',
            'matagalpa' => 'Matagalpa',
            'jinotega' => 'Jinotega',
        ];

        foreach ($items as $slug => $name) {
            $departments[$slug] = Departamentos::firstOrCreate(
                ['slug' => $slug],
                ['nombre' => $name, 'url' => null]
            );
        }

        return $departments;
    }

    private function municipalities(array $departments): array
    {
        $items = [
            'esteli' => ['Estelí', 'esteli', 13.0919, -86.3538],
            'condega' => ['Condega', 'esteli', 13.3650, -86.3985],
            'somoto' => ['Somoto', 'madriz', 13.4808, -86.5821],
            'ocotal' => ['Ocotal', 'nueva-segovia', 13.6321, -86.4752],
            'mozonte' => ['Mozonte', 'nueva-segovia', 13.6592, -86.4384],
            'jalapa' => ['Jalapa', 'nueva-segovia', 13.9222, -86.1235],
            'managua' => ['Managua', 'managua', 12.1149, -86.2362],
            'tipitapa' => ['Tipitapa', 'managua', 12.1973, -86.0963],
            'matagalpa' => ['Matagalpa', 'matagalpa', 12.9256, -85.9175],
            'jinotega' => ['Jinotega', 'jinotega', 13.0910, -86.0023],
        ];

        foreach ($items as $slug => [$name, $department, $latitude, $longitude]) {
            $municipalities[$slug] = Municipios::firstOrCreate(
                ['slug' => $slug],
                [
                    'nombre' => $name,
                    'departamento_id' => $departments[$department]->id,
                    'latitud' => $latitude,
                    'longitud' => $longitude,
                    'url_M' => null,
                ]
            );
        }

        return $municipalities;
    }

    private function terminals(array $departments, array $municipalities): array
    {
        $items = [
            'norte-esteli' => ['Terminal Norte de Estelí', 'esteli', 'esteli', '04:30', '20:30'],
            'somoto' => ['Terminal de Somoto', 'madriz', 'somoto', '04:00', '21:00'],
            'ocotal' => ['Terminal de Ocotal', 'nueva-segovia', 'ocotal', '04:30', '20:00'],
            'mayoreo' => ['Terminal El Mayoreo', 'managua', 'managua', '04:00', '22:00'],
            'guanuca' => ['Terminal de Guanuca', 'matagalpa', 'matagalpa', '04:30', '20:30'],
            'jinotega' => ['Terminal de Jinotega', 'jinotega', 'jinotega', '05:00', '20:00'],
        ];

        foreach ($items as $key => [$name, $department, $municipality, $opens, $closes]) {
            $slug = 'demo-'.$key;
            $terminals[$key] = Terminales::firstOrCreate(
                ['slug' => $slug],
                [
                    'nombre' => $name,
                    'departamento_id' => $departments[$department]->id,
                    'municipio_id' => $municipalities[$municipality]->id,
                    'hora_apertura' => $opens,
                    'hora_cierre' => $closes,
                    'url_T' => null,
                ]
            );
        }

        return $terminals;
    }

    private function services(): array
    {
        return [
            ['slug' => 'demo-expreso-norte-0600', 'nombre' => 'Expreso Norte', 'categoria' => 'Expreso', 'placa' => 'ES 1024', 'terminal' => 'norte-esteli', 'stops' => [['esteli', '06:00', 0], ['condega', '06:45', 45], ['somoto', '08:00', 95]]],
            ['slug' => 'demo-segoviano-0830', 'nombre' => 'El Segoviano', 'categoria' => 'Ruteado', 'placa' => 'MD 2318', 'terminal' => 'somoto', 'stops' => [['somoto', '08:30', 0], ['ocotal', '09:35', 55], ['mozonte', '10:05', 75], ['jalapa', '11:30', 120]]],
            ['slug' => 'demo-capitalena-0500', 'nombre' => 'La Capitaleña', 'categoria' => 'Expreso', 'placa' => 'ES 4812', 'terminal' => 'norte-esteli', 'stops' => [['esteli', '05:00', 0], ['managua', '08:00', 170]]],
            ['slug' => 'demo-norte-mayoreo-0615', 'nombre' => 'Norte Mayoreo', 'categoria' => 'Ruteado', 'placa' => 'MG 7710', 'terminal' => 'mayoreo', 'stops' => [['managua', '06:15', 0], ['tipitapa', '06:55', 30], ['matagalpa', '09:15', 155]]],
            ['slug' => 'demo-cafetalero-1000', 'nombre' => 'El Cafetalero', 'categoria' => 'Expreso', 'placa' => 'MT 3205', 'terminal' => 'guanuca', 'stops' => [['matagalpa', '10:00', 0], ['jinotega', '11:20', 85]]],
            ['slug' => 'demo-jinotega-managua-1230', 'nombre' => 'Rápido Jinotega', 'categoria' => 'Expreso', 'placa' => 'JI 4580', 'terminal' => 'jinotega', 'stops' => [['jinotega', '12:30', 0], ['matagalpa', '13:50', 80], ['tipitapa', '16:10', 175], ['managua', '16:50', 205]]],
            ['slug' => 'demo-frontera-sur-1400', 'nombre' => 'Frontera Sur', 'categoria' => 'Ruteado', 'placa' => 'NS 9021', 'terminal' => 'ocotal', 'stops' => [['ocotal', '14:00', 0], ['somoto', '15:10', 60], ['condega', '16:15', 105], ['esteli', '17:00', 145]]],
            ['slug' => 'demo-esteli-jinotega-1330', 'nombre' => 'Cordillera Express', 'categoria' => 'Expreso', 'placa' => 'ES 6654', 'terminal' => 'norte-esteli', 'stops' => [['esteli', '13:30', 0], ['matagalpa', '15:20', 115], ['jinotega', '16:40', 195]]],
        ];
    }
}
