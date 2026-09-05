@extends('layouts.plantilla')
@section('title', 'Nuevo autobús')
@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header text-center">
            <h3>Registrar servicio de autobús</h3>
        </div>
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
            @endif
            <form action="{{ route('autobus') }}" method="POST">@csrf
                <div class="row g-3">
                    <div class="col-md-6"><label for="terminal" class="form-label">Terminal de salida</label><select name="terminal" id="terminal" class="form-select" required>
                            <option value="">Seleccione una terminal</option>@foreach($terminales as $terminal)<option value="{{ $terminal->id }}" data-municipio="{{ $terminal->municipio_id }}" @selected(old('terminal')==$terminal->id)>{{ $terminal->nombre }}{{ $terminal->municipios ? ' — '.$terminal->municipios->nombre : '' }}</option>@endforeach
                        </select>@error('terminal')<div class="text-danger">{{ $message }}</div>@enderror</div>
                    <div class="col-md-6"><label for="nombre" class="form-label">Nombre del autobús/empresa</label><input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre') }}" required>@error('nombre')<div class="text-danger">{{ $message }}</div>@enderror</div>
                    <div class="col-md-6"><label for="municipio_origen_id" class="form-label">Municipio de origen</label><select name="municipio_origen_id" id="municipio_origen_id" class="form-select" required>
                            <option value="">Seleccione un municipio</option>@foreach($municipios as $municipio)<option value="{{ $municipio->id }}" @selected(old('municipio_origen_id')==$municipio->id)>{{ $municipio->nombre }}</option>@endforeach
                        </select>@error('municipio_origen_id')<div class="text-danger">{{ $message }}</div>@enderror</div>
                    <div class="col-md-6"><label for="municipio_destino_id" class="form-label">Municipio de destino</label><select name="municipio_destino_id" id="municipio_destino_id" class="form-select" required>
                            <option value="">Seleccione un municipio</option>@foreach($municipios as $municipio)<option value="{{ $municipio->id }}" @selected(old('municipio_destino_id')==$municipio->id)>{{ $municipio->nombre }}</option>@endforeach
                        </select>@error('municipio_destino_id')<div class="text-danger">{{ $message }}</div>@enderror</div>
                    <div class="col-md-4"><label for="placa" class="form-label">Placa</label><input type="text" name="placa" id="placa" class="form-control" value="{{ old('placa') }}"></div>
                    <div class="col-md-4"><label for="hora_salida" class="form-label">Hora de salida</label><input type="time" name="hora_salida" id="hora_salida" class="form-control" value="{{ old('hora_salida') }}" required>@error('hora_salida')<div class="text-danger">{{ $message }}</div>@enderror</div>
                    <div class="col-md-4"><label for="hora_llegada" class="form-label">Hora de llegada</label><input type="time" name="hora_llegada" id="hora_llegada" class="form-control" value="{{ old('hora_llegada') }}" required>@error('hora_llegada')<div class="text-danger">{{ $message }}</div>@enderror</div>
                    <div class="col-md-4"><label for="categoria" class="form-label">Categoría</label><select name="categoria" id="categoria" class="form-select">
                            <option value="Expreso" @selected(old('categoria')==='Expreso' )>Expreso</option>
                            <option value="Ruteado" @selected(old('categoria')==='Ruteado' )>Ruteado</option>
                        </select></div>
                    <div class="col-md-4"><label for="tarifa" class="form-label">Tarifa por pasajero (C$)</label><input type="number" min="0" max="999999.99" step="0.01" name="tarifa" id="tarifa" class="form-control" value="{{ old('tarifa', $autobus->tarifa ?? '') }}" aria-describedby="tarifa-ayuda"><div id="tarifa-ayuda" class="form-text">Deje vacío si no se conoce. Cero indica servicio gratuito.</div>@error('tarifa')<div class="text-danger">{{ $message }}</div>@enderror</div>
                </div>
                <button type="submit" class="btn btn-info btn-lg mt-4">Guardar servicio</button>
            </form>
        </div>
    </div>
   
</div>
@endsection
