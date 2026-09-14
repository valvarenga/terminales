<?php

namespace App\Services;

use App\Models\Autobuses;
use App\Models\AutobusParada;
use Illuminate\Support\Collection;

class RouteFinder
{
    /**
     * Finds same-day itineraries of up to four bus legs (three transfers).
     */
    public function find(int $originId, int $destinationId): Collection
    {
        $services = collect();
        $frontier = collect([$originId]);
        $loadedBusIds = collect();

        // Load only the portion of the network reachable within the supported
        // four legs, instead of hydrating every service on every search.
        for ($depth = 0; $depth < 4 && $frontier->isNotEmpty(); $depth++) {
            $levelServices = Autobuses::query()
                ->with(['origenMunicipio', 'destinoMunicipio', 'terminales', 'paradas.municipio'])
                ->whereNotIn('id', $loadedBusIds)
                ->where(function ($query) use ($frontier) {
                    $query->whereHas('paradas', fn ($stops) => $stops->whereIn('municipio_id', $frontier))
                        ->orWhereIn('municipio_origen_id', $frontier);
                })
                ->orderBy('hora_salida')
                ->get();

            $services = $services->concat($levelServices);
            $loadedBusIds = $loadedBusIds->concat($levelServices->pluck('id'))->unique();
            $frontier = $levelServices->flatMap(function (Autobuses $service) use ($frontier) {
                $stops = $this->serviceStops($service);
                return $stops->flatMap(function ($stop, $index) use ($stops, $frontier) {
                    return $frontier->contains((int) $stop->municipio_id)
                        ? $stops->slice($index + 1)->pluck('municipio_id')
                        : [];
                });
            })->unique()->values();
        }

        $servicesByOrigin = $services
            ->flatMap(fn (Autobuses $service) => $this->rideOptions($service))
            ->groupBy('municipio_origen_id');

        return $this->findFromServices($servicesByOrigin, $originId, $destinationId);
    }

    private function serviceStops(Autobuses $service): Collection
    {
        if ($service->relationLoaded('paradas') && $service->paradas->count() >= 2) {
            return $service->paradas->sortBy('posicion')->values();
        }

        if (! $service->municipio_origen_id || ! $service->municipio_destino_id) {
            return collect();
        }

        return collect([
            new AutobusParada(['municipio_id' => $service->municipio_origen_id, 'posicion' => 0, 'hora_paso' => $service->hora_salida, 'tarifa_acumulada' => 0]),
            new AutobusParada(['municipio_id' => $service->municipio_destino_id, 'posicion' => 1, 'hora_paso' => $service->hora_llegada, 'tarifa_acumulada' => $service->tarifa]),
        ])->each(function (AutobusParada $stop, int $index) use ($service) {
            $stop->setRelation('municipio', $index === 0 ? $service->origenMunicipio : $service->destinoMunicipio);
        });
    }

    private function rideOptions(Autobuses $service): Collection
    {
        $stops = $this->serviceStops($service);
        $options = collect();

        for ($from = 0; $from < $stops->count() - 1; $from++) {
            for ($to = $from + 1; $to < $stops->count(); $to++) {
                $boarding = $stops[$from];
                $alighting = $stops[$to];
                $leg = clone $service;
                $leg->setAttribute('municipio_origen_id', $boarding->municipio_id);
                $leg->setAttribute('municipio_destino_id', $alighting->municipio_id);
                $leg->setAttribute('hora_salida', $boarding->hora_paso);
                $leg->setAttribute('hora_llegada', $alighting->hora_paso);
                $leg->setAttribute('origen', $boarding->municipio?->nombre);
                $leg->setAttribute('destino', $alighting->municipio?->nombre);
                $leg->setAttribute('tarifa', $boarding->tarifa_acumulada !== null && $alighting->tarifa_acumulada !== null
                    ? number_format((float) $alighting->tarifa_acumulada - (float) $boarding->tarifa_acumulada, 2, '.', '')
                    : null);
                $leg->setRelation('origenMunicipio', $boarding->municipio);
                $leg->setRelation('destinoMunicipio', $alighting->municipio);
                $leg->setRelation('routeStops', $stops->slice($from, $to - $from + 1)->values());
                $options->push($leg);
            }
        }

        return $options;
    }

    /**
     * Exposed for deterministic tests and for future import integrations.
     */
    public function findFromServices(Collection $servicesByOrigin, int $originId, int $destinationId): Collection
    {
        $itineraries = collect();
        $this->search($servicesByOrigin, $originId, $destinationId, [], [$originId => true], null, $itineraries);

        return $this->removeDominatedItineraries($itineraries)
            ->sortBy(fn (array $itinerary) => sprintf('%02d-%05d', $itinerary['transbordos'], $this->minutes($itinerary['llegada'])))
            ->values();
    }

    /**
     * Removes detours that board a service downstream even though the same
     * service (and the remaining connections) can be taken from the requested
     * origin without leaving earlier or arriving later.
     */
    private function removeDominatedItineraries(Collection $itineraries): Collection
    {
        return $itineraries->reject(function (array $candidate) use ($itineraries) {
            if ($candidate['transbordos'] === 0) {
                return false;
            }

            $candidateBusIds = collect($candidate['tramos'])->pluck('id')->all();

            return $itineraries->contains(function (array $alternative) use ($candidate, $candidateBusIds) {
                if (count($alternative['tramos']) >= count($candidate['tramos'])) {
                    return false;
                }

                $alternativeBusIds = collect($alternative['tramos'])->pluck('id')->all();
                $sameRemainingServices = array_slice($candidateBusIds, -count($alternativeBusIds)) === $alternativeBusIds;

                return $sameRemainingServices
                    && $this->minutes($alternative['salida']) >= $this->minutes($candidate['salida'])
                    && $this->minutes($alternative['llegada']) <= $this->minutes($candidate['llegada']);
            });
        });
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
                    'tarifa_total' => collect($nextLegs)->contains(fn ($leg) => $leg->tarifa === null)
                        ? null
                        : number_format(collect($nextLegs)->sum(fn ($leg) => (int) round((float) $leg->tarifa * 100)) / 100, 2, '.', ''),
                    'transbordos' => count($nextLegs) - 1,
                    'salida' => $nextLegs[0]->hora_salida,
                    'llegada' => $service->hora_llegada,
                ]);
                continue;
            }

            // A journey can include four services: the initial bus plus up to
            // three transfers. Keep visited municipalities to avoid loops.
            if (count($nextLegs) < 4) {
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
