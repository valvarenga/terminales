@extends('layouts.plantilla')
@section('title', 'Rutas de '.$origen->nombre.' a '.$destino->nombre)
@section('estilos')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<link rel="stylesheet" href="{{ asset('css/route-results.css') }}">
@endsection
@section('content')
<section class="route-results container py-4 pb-5">
    <header class="page-header pt-3">
        <a href="{{ route('home') }}#buscar-ruta" class="eyebrow">← Nueva búsqueda</a>
        <h1 class="mt-2">{{ $origen->nombre }} <span class="text-success">→</span> {{ $destino->nombre }}</h1>
        <p>Compara horarios y tarifas para elegir cómo llegar. Los horarios corresponden a los servicios registrados.</p>
    </header>
    @if($itinerarios->isEmpty())
        <div class="empty-state content-card"><h2>No hay rutas disponibles</h2><p class="mb-0">Aún no hay una combinación registrada para este trayecto.</p><a href="{{ route('home') }}#buscar-ruta" class="btn btn-primary mt-3">Probar otra búsqueda</a></div>
    @else
        <div class="row g-4 align-items-start">
            <div class="col-lg-7">
                <div class="results-heading mb-3"><h2 class="h4 mb-0">{{ $itinerarios->count() }} {{ $itinerarios->count() === 1 ? 'opción encontrada' : 'opciones encontradas' }}</h2><span class="small text-muted">Ordenadas por menos transbordos y llegada más temprana</span></div>
                <div class="d-grid gap-3">
                    @foreach($itinerarios as $itinerario)
                        @php
                            $toMinutes = fn ($time) => (int) substr($time, 0, 2) * 60 + (int) substr($time, 3, 2);
                            $duration = $toMinutes($itinerario['llegada']) - $toMinutes($itinerario['salida']);
                        @endphp
                        <article class="content-card route-option p-3 p-md-4" data-route-index="{{ $loop->index }}" aria-labelledby="route-title-{{ $loop->index }}">
                            <div class="route-card-heading">
                                <h3 id="route-title-{{ $loop->index }}" class="route-title"><span class="route-number">{{ $loop->iteration }}</span> {{ $itinerario['transbordos'] ? $itinerario['transbordos'].' '.($itinerario['transbordos'] === 1 ? 'transbordo' : 'transbordos') : 'Ruta directa' }}</h3>
                                <span class="route-duration"><i class="bi bi-clock me-1" aria-hidden="true"></i>{{ intdiv($duration, 60) }} h {{ $duration % 60 }} min en total</span>
                            </div>
                            <div class="route-overview">
                                <div><span class="metric-label">Salida</span><strong class="route-time">{{ formato_hora($itinerario['salida']) }}</strong><span class="metric-place">{{ $origen->nombre }}</span></div>
                                <span class="journey-arrow" aria-hidden="true"><i class="bi bi-arrow-right" aria-hidden="true"></i></span>
                                <div><span class="metric-label">Llegada</span><strong class="route-time">{{ formato_hora($itinerario['llegada']) }}</strong><span class="metric-place">{{ $destino->nombre }}</span></div>
                                <div class="route-total"><span class="metric-label">Total estimado por pasajero</span><strong>{{ $itinerario['tarifa_total'] !== null ? 'C$ '.number_format((float) $itinerario['tarifa_total'], 2) : 'Por confirmar (faltan tarifas)' }}</strong>@if($itinerario['tarifa_total'] === null)<span class="metric-place">Consulta la tarifa antes de viajar</span>@endif</div>
                            </div>
                            <details class="route-details" open>
                                <summary>Ver tramos y dónde abordar <i class="bi bi-chevron-down" aria-hidden="true"></i></summary>
                                <ol class="leg-list">
                                    @foreach($itinerario['tramos'] as $tramo)
                                        <li class="route-leg">
                                            <span class="leg-marker" aria-hidden="true"><i class="bi bi-bus-front" aria-hidden="true"></i></span>
                                            <div class="leg-content">
                                                <h4>{{ $tramo->origenMunicipio->nombre }} <span aria-hidden="true">→</span> {{ $tramo->destinoMunicipio->nombre }}</h4>
                                                <p class="leg-service">{{ $tramo->nombre }} @if($tramo->categoria)<span class="service-category">{{ $tramo->categoria }}</span>@endif</p>
                                                <p class="leg-schedule"><i class="bi bi-clock me-1" aria-hidden="true"></i>{{ formato_hora($tramo->hora_salida) }} – {{ formato_hora($tramo->hora_llegada) }} <span>Tarifa: <strong>{{ $tramo->tarifa !== null ? 'C$ '.number_format((float) $tramo->tarifa, 2) : 'Por confirmar' }}</strong></span></p>
                                                @php
                                                    $boardingTerminal = $tramo->terminales->first(fn ($terminal) => (int) $terminal->municipio_id === (int) $tramo->municipio_origen_id);
                                                @endphp
                                                <p class="boarding-note"><i class="bi bi-geo-alt me-1" aria-hidden="true"></i>{{ $boardingTerminal ? 'Aborda en '.$boardingTerminal->nombre : 'Punto de abordaje por confirmar en '.$tramo->origenMunicipio->nombre }}</p>
                                                @if(!$loop->last)
                                                    @php($wait = $toMinutes($itinerario['tramos'][$loop->index + 1]->hora_salida) - $toMinutes($tramo->hora_llegada))
                                                    <div class="transfer-note"><i class="bi bi-arrow-repeat me-1" aria-hidden="true"></i><strong>Cambio de bus · {{ $wait }} min de espera</strong><span>Bájate en {{ $tramo->destinoMunicipio->nombre }} y toma el siguiente bus.</span></div>
                                                @endif
                                            </div>
                                        </li>
                                    @endforeach
                                </ol>
                            </details>
                            <div class="route-card-footer"><span>Duración total con esperas incluidas.</span><button type="button" class="btn btn-outline-primary route-map-select" data-route-index="{{ $loop->index }}" aria-pressed="false" aria-controls="route-map" aria-label="Ver ruta {{ $loop->iteration }} en el mapa"><i class="bi bi-map me-2" aria-hidden="true"></i>Ver en el mapa</button></div>
                        </article>
                    @endforeach
                </div>
            </div>
            <aside class="col-lg-5"><div class="route-map-card content-card p-3 p-md-4"><div class="mb-3"><p class="eyebrow mb-1">Vista del recorrido</p><h2 class="h4 mb-1">Mapa de la ruta</h2><p class="small text-muted mb-0">Las líneas unen los municipios que tienen coordenadas registradas.</p></div><div id="route-map" class="route-map" aria-label="Mapa de los recorridos encontrados"></div><p id="route-map-note" role="status" aria-live="polite" class="small text-muted mt-3 mb-0"></p></div></aside>
        </div>
    @endif
</section>
@endsection
@section('scripts')
@if($itinerarios->isNotEmpty())
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script type="application/json" id="route-itineraries">@json($itinerarios->map(fn($item)=>collect($item['tramos'])->map(fn($bus)=>$bus->relationLoaded('routeStops')?$bus->routeStops->pluck('municipio'):collect([$bus->origenMunicipio,$bus->destinoMunicipio]))))</script>
<script src="{{ asset('js/route-map.js') }}" defer></script>
@endif
@endsection
