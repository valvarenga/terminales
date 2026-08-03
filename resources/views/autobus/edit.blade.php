@extends('layouts.plantilla')
@section('title', 'Editar autobús')
@section('content')
    <section class="container py-4">
        <div class="content-card p-4 col-lg-7 mx-auto">
            <a href="{{ route('autobuses.list') }}" class="eyebrow">← Volver</a>
            <h1 class="h3 mt-2">Editar autobús</h1>
            <p class="text-muted">{{ $autobus->nombre }} — {{ $autobus->origen }} → {{ $autobus->destino }}</p>
            <form method="POST" action="{{ route('autobus.update', $autobus) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="municipio_origen_id" class="form-label">Municipio de origen</label>
                    <select class="form-select" id="municipio_origen_id" name="municipio_origen_id" required>
                        <option value="">Seleccione un municipio</option>
                        @foreach($municipios as $municipio)
                            <option value="{{ $municipio->id }}" @selected(old('municipio_origen_id', $autobus->municipio_origen_id) == $municipio->id)>{{ $municipio->nombre }}</option>
                        @endforeach
                    </select>
                    @error('municipio_origen_id')<div class="text-danger">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label for="municipio_destino_id" class="form-label">Municipio de destino</label>
                    <select class="form-select" id="municipio_destino_id" name="municipio_destino_id" required>
                        <option value="">Seleccione un municipio</option>
                        @foreach($municipios as $municipio)
                            <option value="{{ $municipio->id }}" @selected(old('municipio_destino_id', $autobus->municipio_destino_id) == $municipio->id)>{{ $municipio->nombre }}</option>
                        @endforeach
                    </select>
                    @error('municipio_destino_id')<div class="text-danger">{{ $message }}</div>@enderror
                </div>

                <button class="btn btn-primary">Guardar cambios</button>
            </form>
        </div>
    </section>
@endsection