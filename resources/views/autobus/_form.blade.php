@php
    $autobus = $autobus ?? null;
    $savedStops = $autobus?->paradas?->map(fn ($stop) => [
        'municipio_id' => $stop->municipio_id,
        'hora_paso' => substr($stop->hora_paso, 0, 5),
        'tarifa_acumulada' => $stop->tarifa_acumulada,
    ])->all() ?? [];
    if (!$savedStops && $autobus) {
        $savedStops = [
            ['municipio_id' => $autobus->municipio_origen_id, 'hora_paso' => substr($autobus->hora_salida, 0, 5), 'tarifa_acumulada' => 0],
            ['municipio_id' => $autobus->municipio_destino_id, 'hora_paso' => substr($autobus->hora_llegada, 0, 5), 'tarifa_acumulada' => $autobus->tarifa],
        ];
    }
    $formStops = old('paradas', $savedStops ?: [
        ['municipio_id' => '', 'hora_paso' => '', 'tarifa_acumulada' => 0],
        ['municipio_id' => '', 'hora_paso' => '', 'tarifa_acumulada' => ''],
    ]);
@endphp
@if($errors->any())<div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<div class="row g-3">
    <input type="hidden" name="municipio_origen_id" id="legacy-origin"><input type="hidden" name="municipio_destino_id" id="legacy-destination"><input type="hidden" name="hora_salida" id="legacy-departure"><input type="hidden" name="hora_llegada" id="legacy-arrival"><input type="hidden" name="tarifa" id="legacy-fare">
    <div class="col-md-6"><label for="terminal" class="form-label">Terminal de salida</label><select name="terminal" id="terminal" class="form-select" required><option value="">Seleccione una terminal</option>@foreach($terminales as $terminal)<option value="{{ $terminal->id }}" data-municipio="{{ $terminal->municipio_id }}" data-departamento="{{ $terminal->departamento_id }}" @selected(old('terminal', $autobus?->terminales->first()?->id)==$terminal->id)>{{ $terminal->nombre }}{{ $terminal->municipios ? ' — '.$terminal->municipios->nombre : '' }}</option>@endforeach</select>@error('terminal')<div class="text-danger">{{ $message }}</div>@enderror</div>
    <div class="col-md-6"><label for="nombre" class="form-label">Nombre del autobús/empresa</label><input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre', $autobus?->nombre) }}" required></div>
    <div class="col-md-6"><label for="placa" class="form-label">Placa</label><input type="text" name="placa" id="placa" class="form-control" value="{{ old('placa', $autobus?->placa) }}"></div>
    <div class="col-md-6"><label for="categoria" class="form-label">Categoría</label><select name="categoria" id="categoria" class="form-select"><option value="Expreso" @selected(old('categoria', $autobus?->categoria)==='Expreso')>Expreso</option><option value="Ruteado" @selected(old('categoria', $autobus?->categoria)==='Ruteado')>Ruteado</option></select></div>
</div>
<div class="row g-4 mt-2">
    <div class="col-lg-7">
        <div class="d-flex justify-content-between align-items-center mb-2"><div><h2 class="h5 mb-1">Recorrido y paradas</h2><p class="small text-muted mb-0">Ordena los municipios desde la salida hasta el destino final.</p></div><button type="button" id="add-stop" class="btn btn-outline-primary btn-sm">Agregar parada</button></div>
        <div id="bus-stops" data-min-stops="2">
            @foreach($formStops as $index => $stop)
                <div class="bus-stop-row" data-stop-row>
                    <span class="bus-stop-number">{{ $index + 1 }}</span>
                    <select class="form-select stop-municipality" name="paradas[{{ $index }}][municipio_id]" aria-label="Municipio de parada {{ $index + 1 }}" required><option value="">Seleccione municipio</option>@foreach($municipios as $municipio)<option value="{{ $municipio->id }}" data-departamento="{{ $municipio->departamento_id }}" data-lat="{{ $municipio->latitud }}" data-lng="{{ $municipio->longitud }}" @selected((string)($stop['municipio_id'] ?? '') === (string)$municipio->id)>{{ $municipio->nombre }}</option>@endforeach</select>
                    <input class="form-control stop-time" type="time" name="paradas[{{ $index }}][hora_paso]" value="{{ $stop['hora_paso'] ?? '' }}" aria-label="Hora de paso en parada {{ $index + 1 }}" required>
                    <div class="input-group"><span class="input-group-text">C$</span><input class="form-control stop-fare" type="number" min="0" max="999999.99" step="0.01" name="paradas[{{ $index }}][tarifa_acumulada]" value="{{ $stop['tarifa_acumulada'] ?? '' }}" aria-label="Tarifa acumulada en parada {{ $index + 1 }}"></div>
                    <div class="btn-group"><button type="button" class="btn btn-outline-secondary move-up" aria-label="Subir parada">↑</button><button type="button" class="btn btn-outline-secondary move-down" aria-label="Bajar parada">↓</button><button type="button" class="btn btn-outline-danger remove-stop" aria-label="Quitar parada">×</button></div>
                </div>
            @endforeach
        </div>
        <p class="form-text">La tarifa es acumulada desde el origen. La primera debe ser C$0; el sistema calcula la diferencia para viajes intermedios.</p>
    </div>
    <div class="col-lg-5"><aside class="map-panel"><h2 class="h5">Vista del recorrido</h2><div id="bus-route-map" class="municipio-map"></div><p id="bus-route-warning" class="small text-muted mt-2 mb-0"></p></aside></div>
</div>
<template id="bus-stop-template"><div class="bus-stop-row" data-stop-row><span class="bus-stop-number"></span><select class="form-select stop-municipality" required><option value="">Seleccione municipio</option>@foreach($municipios as $municipio)<option value="{{ $municipio->id }}" data-departamento="{{ $municipio->departamento_id }}" data-lat="{{ $municipio->latitud }}" data-lng="{{ $municipio->longitud }}">{{ $municipio->nombre }}</option>@endforeach</select><input class="form-control stop-time" type="time" required><div class="input-group"><span class="input-group-text">C$</span><input class="form-control stop-fare" type="number" min="0" max="999999.99" step="0.01"></div><div class="btn-group"><button type="button" class="btn btn-outline-secondary move-up" aria-label="Subir parada">↑</button><button type="button" class="btn btn-outline-secondary move-down" aria-label="Bajar parada">↓</button><button type="button" class="btn btn-outline-danger remove-stop" aria-label="Quitar parada">×</button></div></div></template>
