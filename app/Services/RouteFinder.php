<?php

namespace App\Services;

use App\Models\Autobuses;
use Illuminate\Support\Collection;

class RouteFinder
{
    /**
     * Finds same-day itineraries of up to three bus legs.
     */
    public function find(int $originId, int $destinationId): Collection
    {
        $servicesByOrigin = Autobuses::query()
            ->with(['origenMunicipio', 'destinoMunicipio', 'terminales'])
            ->whereNotNull('municipio_origen_id')
            ->whereNotNull('municipio_destino_id')
            ->orderBy('hora_salida')
            ->get()
            ->groupBy('municipio_origen_id');

        return $this->findFromServices($servicesByOrigin, $originId, $destinationId);
    }

    /**
     * Exposed for deterministic tests and for future import integrations.
     */
    public function findFromServices(Collection $servicesByOrigin, int $originId, int $destinationId): Collection
    {
        $itineraries = collect();
        $this->search($servicesByOrigin, $originId, $destinationId, [], [$originId => true], null, $itineraries);

        return $itineraries
            ->sortBy(fn (array $itinerary) => sprintf('%02d-%05d', $itinerary['transbordos'], $this->minutes($itinerary['llegada'])))
            ->values();
    }

    private function search(Collection $servicesByOrigin, int $currentId, int $destinationId, array $legs, array $visited, ?string $previousArrival, Collection $itineraries): void
    {
        foreach ($servicesByOrigin->get($currentId, collect()) as $service) {
            $departure = (string) $service->hora_salida;
            $arrival = (string) $service->hora_llegada;

            // This first version supports journeys that finish on the same day only.
            if ($this->minutes($arrival) <= $this->minutes($departure)) {
                continue;
            }

            if ($previousArrival !== null && $this->minutes($departure) <= $this->minutes($previousArrival)) {
                continue;
            }

            $nextId = (int) $service->municipio_destino_id;
            if (isset($visited[$nextId])) {
                continue;
            }

            $nextLegs = [...$legs, $service];
            if ($nextId === $destinationId) {
                $itineraries->push([
                    'tramos' => $nextLegs,
                    'transbordos' => count($nextLegs) - 1,
                    'salida' => $nextLegs[0]->hora_salida,
                    'llegada' => $service->hora_llegada,
                ]);
                continue;
            }

            if (count($nextLegs) < 3) {
                $visited[$nextId] = true;
                $this->search($servicesByOrigin, $nextId, $destinationId, $nextLegs, $visited, $arrival, $itineraries);
            }
        }
    }

    private function minutes(string $time): int
    {
        [$hours, $minutes] = array_map('intval', explode(':', $time));

        return ($hours * 60) + $minutes;
    }
}
