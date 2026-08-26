@extends('layouts.plantilla')
@section('title', 'Nuevo municipio')
@section('estilos')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
@endsection
@section('content')
<section class="page-header pb-4">
    <div class="container">
        <p class="eyebrow mb-2">Administración</p>
        <h1 class="mb-2">Crear municipio</h1>
        <p class="mb-0">Registra los datos principales y selecciona su ubicación aproximada en el mapa.</p>
    </div>
</section>
<section class="container pb-5">
    <form action="{{ route('municipio.store') }}" method="POST" enctype="multipart/form-data" class="content-card p-4 p-lg-5">
        @csrf
        <div class="row g-4 align-items-start">
            <div class="col-lg-7"><div class="row g-3">
                <div class="col-12"><label for="departamento_id" class="form-label fw-semibold">Departamento</label><select name="departamento_id" id="departamento_id" class="form-select" required><option value="" disabled {{ old('departamento_id') ? '' : 'selected' }}>Selecciona un departamento</option>@foreach($departamentos as $departamento)<option value="{{ $departamento->id }}" @selected(old('departamento_id') == $departamento->id)>{{ $departamento->nombre }}</option>@endforeach</select>@error('departamento_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror</div>
                <div class="col-12"><label for="nombre" class="form-label fw-semibold">Nombre del municipio</label><input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre') }}" required autofocus>@error('nombre')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label for="latitud" class="form-label fw-semibold">Latitud</label><input type="number" step="0.0000001" name="latitud" id="latitud" class="form-control" value="{{ old('latitud') }}" placeholder="Ej. 13.0910">@error('latitud')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label for="longitud" class="form-label fw-semibold">Longitud</label><input type="number" step="0.0000001" name="longitud" id="longitud" class="form-control" value="{{ old('longitud') }}" placeholder="Ej. -86.3545">@error('longitud')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror</div>
                <div class="col-12"><label for="file_M" class="form-label fw-semibold">Foto del municipio <span class="text-muted fw-normal">(opcional)</span></label><input type="file" class="form-control" id="file_M" name="file_M" accept="image/*"></div>
            </div></div>
            <div class="col-lg-5"><aside class="map-panel"><div class="d-flex justify-content-between align-items-start mb-2 gap-3"><div><h2 class="h5 mb-1">Ubicación en el mapa</h2><p class="small text-muted mb-0">Haz clic o arrastra el marcador para ajustar las coordenadas.</p></div><span class="map-status" id="map-status">Opcional</span></div><div id="municipio-map" class="municipio-map" aria-label="Mapa para seleccionar la ubicación del municipio"></div></aside></div>
        </div>
        @if ($errors->any())<div class="alert alert-danger mt-4 mb-0">Revisa los campos marcados e inténtalo de nuevo.</div>@endif
        <div class="d-flex flex-wrap gap-3 align-items-center mt-4 pt-2"><button type="submit" class="btn btn-primary px-4">Crear municipio</button><a href="{{ route('municipio.show') }}" class="btn btn-outline-secondary">Cancelar</a></div>
    </form>
</section>
@endsection
@section('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const latInput = document.getElementById('latitud'), lngInput = document.getElementById('longitud'), status = document.getElementById('map-status');
            const defaultPosition = [12.8654, -85.2072], savedLat = parseFloat(latInput.value), savedLng = parseFloat(lngInput.value);
            const hasCoordinates = Number.isFinite(savedLat) && Number.isFinite(savedLng);
            const map = L.map('municipio-map', {scrollWheelZoom: false}).setView(hasCoordinates ? [savedLat, savedLng] : defaultPosition, hasCoordinates ? 12 : 7);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {maxZoom: 19, attribution: '&copy; OpenStreetMap'}).addTo(map);
            const marker = L.marker(hasCoordinates ? [savedLat, savedLng] : defaultPosition, {draggable: true}).addTo(map);
            function setCoordinates(latlng, moveMap) { latInput.value = latlng.lat.toFixed(7); lngInput.value = latlng.lng.toFixed(7); marker.setLatLng(latlng); if (moveMap) map.panTo(latlng); status.textContent = 'Ubicación seleccionada'; status.classList.add('is-set'); }
            function updateFromInputs() { const lat = parseFloat(latInput.value), lng = parseFloat(lngInput.value); if (Number.isFinite(lat) && Number.isFinite(lng)) setCoordinates(L.latLng(lat, lng), true); }
            if (hasCoordinates) setCoordinates(L.latLng(savedLat, savedLng), false);
            marker.on('dragend', function () { setCoordinates(marker.getLatLng(), false); }); map.on('click', function (event) { setCoordinates(event.latlng, false); }); latInput.addEventListener('change', updateFromInputs); lngInput.addEventListener('change', updateFromInputs);
        });
    </script>
@endsection
