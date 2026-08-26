@extends('layouts.plantilla')
@section('title', 'Rutas de '.$origen->nombre.' a '.$destino->nombre)
@section('estilos')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
@endsection
@section('content')
<section class="container py-4 pb-5">
    <header class="page-header pt-3">
        <a href="{{ route('home') }}#buscar-ruta" class="eyebrow">← Nueva búsqueda</a>
        <h1 class="mt-2">{{ $origen->nombre }} <span class="text-success">→</span> {{ $destino->nombre }}</h1>
        <p>Opciones basadas en los servicios y horarios registrados.</p>
    </header>
    @if($itinerarios->isEmpty())
        <div class="empty-state content-card"><h2>No hay rutas disponibles</h2><p class="mb-0">Aún no hay una combinación registrada para este trayecto.</p><a href="{{ route('home') }}#buscar-ruta" class="btn btn-primary mt-3">Probar otra búsqueda</a></div>
    @else
        <div class="row g-4 align-items-start">
            <div class="col-lg-7 order-2 order-lg-1">
                <div class="d-flex align-items-center justify-content-between mb-3"><h2 class="h4 mb-0">{{ $itinerarios->count() }} {{ $itinerarios->count() === 1 ? 'opción encontrada' : 'opciones encontradas' }}</h2><span class="small text-muted">Selecciona una opción en el mapa</span></div>
                <div class="d-grid gap-3">
                    @foreach($itinerarios as $itinerario)
                        <article class="content-card route-option p-4" data-route-index="{{ $loop->index }}">
                            <div class="d-flex justify-content-between flex-wrap gap-2 mb-3"><div><p class="eyebrow mb-1">{{ $itinerario['transbordos'] ? $itinerario['transbordos'].' transbordo(s)' : 'Ruta directa' }}</p><h2 class="h4 mb-0">Salida {{ \Illuminate\Support\Str::of($itinerario['salida'])->substr(0,5) }} · llegada {{ \Illuminate\Support\Str::of($itinerario['llegada'])->substr(0,5) }}</h2></div><span class="route-option__badge">Ruta {{ $loop->iteration }}</span></div>
                            <ol class="mb-0 ps-3">
                                @foreach($itinerario['tramos'] as $tramo)
                                    <li class="mb-3"><strong>{{ $tramo->origenMunicipio->nombre }} → {{ $tramo->destinoMunicipio->nombre }}</strong><br><span class="text-muted">{{ $tramo->nombre }} · {{ $tramo->categoria }} · {{ \Illuminate\Support\Str::of($tramo->hora_salida)->substr(0,5) }} – {{ \Illuminate\Support\Str::of($tramo->hora_llegada)->substr(0,5) }}@if($tramo->terminales->first()) · sale de {{ $tramo->terminales->first()->nombre }}@endif</span>@if(!$loop->last)<div class="small text-primary mt-1">Bájate en {{ $tramo->destinoMunicipio->nombre }} y toma el siguiente bus.</div>@endif</li>
                                @endforeach
                            </ol>
                        </article>
                    @endforeach
                </div>
            </div>
            <aside class="col-lg-5 order-1 order-lg-2"><div class="route-map-card content-card p-3 p-md-4"><div class="mb-3"><p class="eyebrow mb-1">Vista del recorrido</p><h2 class="h4 mb-1">Mapa de la ruta</h2><p class="small text-muted mb-0">Las líneas unen los municipios que tienen coordenadas registradas.</p></div><div id="route-map" class="route-map" aria-label="Mapa de los recorridos encontrados"></div><p id="route-map-note" class="small text-muted mt-3 mb-0"></p></div></aside>
        </div>
    @endif
</section>
@endsection
@section('scripts')
@if($itinerarios->isNotEmpty())
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const itineraries = @json($itinerarios->map(fn ($item) => collect($item['tramos'])->map(fn ($bus) => ['origin' => $bus->origenMunicipio, 'destination' => $bus->destinoMunicipio])->values()));
            const colors = ['#0c705c', '#e7aa3b', '#2e6fbb', '#a74b77', '#7354a2'];
            const map = L.map('route-map', {scrollWheelZoom: false});
            const mapNote = document.getElementById('route-map-note');
            const points = [], layers = [];
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {maxZoom: 19, attribution: '&copy; OpenStreetMap'}).addTo(map);
            itineraries.forEach(function (itinerary, index) {
                const color = colors[index % colors.length], line = [];
                itinerary.forEach(function (leg) {
                    [leg.origin, leg.destination].forEach(function (municipality) {
                        if (municipality && municipality.latitud !== null && municipality.longitud !== null) {
                            const point = [Number(municipality.latitud), Number(municipality.longitud)];
                            line.push(point); points.push(point);
                            L.circleMarker(point, {radius: 6, color: '#fff', weight: 2, fillColor: color, fillOpacity: 1}).addTo(map).bindPopup('<strong>' + municipality.nombre + '</strong><br>Ruta ' + (index + 1));
                        }
                    });
                });
                if (line.length > 1) layers[index] = L.polyline(line, {color: color, weight: 5, opacity: .8}).addTo(map);
            });
            if (points.length) { map.fitBounds(points, {padding: [28, 28], maxZoom: 11}); mapNote.textContent = 'El mapa muestra ' + points.length + ' punto(s) con ubicación registrada.'; } else { map.setView([12.8654, -85.2072], 7); mapNote.textContent = 'Aún no hay coordenadas registradas para mostrar este recorrido en el mapa.'; }
            document.querySelectorAll('.route-option').forEach(function (option) { option.addEventListener('click', function () { const layer = layers[Number(option.dataset.routeIndex)]; if (layer) map.fitBounds(layer.getBounds(), {padding: [35, 35], maxZoom: 12}); document.querySelectorAll('.route-option').forEach(item => item.classList.remove('is-active')); option.classList.add('is-active'); }); });
        });
    </script>
@endif
@endsection
