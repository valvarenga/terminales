@extends('layouts.plantilla')
@section('title', 'Detalle del autobús')
@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h1 class="h3 mb-1">{{ $autobus->nombre }}</h1>
                    <p class="text-muted mb-0">Detalle completo del servicio registrado.</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('autobus.edit', $autobus) }}" class="btn btn-primary">Editar</a>
                    <form action="{{ route('autobus.destroy', $autobus) }}" method="POST" onsubmit="return confirm('Eliminar este autobus?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="border rounded p-3 h-100">
                        <h2 class="h6 text-uppercase text-muted">Terminales asociadas</h2>
                        @foreach($autobus->terminales as $terminal)
                            <p class="mb-1"><strong>{{ $terminal->nombre }}</strong></p>
                        @endforeach
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="border rounded p-3 h-100">
                        <h2 class="h6 text-uppercase text-muted">Ruta</h2>
                        <p class="mb-1"><strong>Origen:</strong> {{ $autobus->origenMunicipio?->nombre ?? $autobus->origen }}</p>
                        <p class="mb-1"><strong>Destino:</strong> {{ $autobus->destinoMunicipio?->nombre ?? $autobus->destino }}</p>
                        <p class="mb-1"><strong>Salida:</strong> {{ $autobus->hora_salida }}</p>
                        <p class="mb-1"><strong>Llegada:</strong> {{ $autobus->hora_llegada }}</p>
                        <p class="mb-1"><strong>Placa:</strong> {{ $autobus->placa ?: '—' }}</p>
                        <p class="mb-0"><strong>Categoría:</strong> {{ $autobus->categoria }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
