@extends('layouts.plantilla')
@section('title', 'Autobuses')
@section('content')
<div class="container py-4">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-1">Autobuses registrados</h1>
            <p class="text-muted mb-0">Gestiona los servicios y edita su información.</p>
        </div>
        <a href="{{ route('newbus') }}" class="btn btn-primary">Registrar nuevo</a>
    </div>

    <div class="card">
        <div class="card-body">
            @if($autobuses->isEmpty())
                <div class="alert alert-info mb-0">No hay autobuses registrados todavía.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Terminal</th>
                                <th>Origen</th>
                                <th>Destino</th>
                                <th>Salida</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($autobuses as $autobus)
                                <tr>
                                    <td>{{ $autobus->nombre }}</td>
                                    <td>
                                        @foreach($autobus->terminales as $terminal)
                                            <span class="badge bg-secondary">{{ $terminal->nombre }}</span>
                                        @endforeach
                                    </td>
                                    <td>{{ $autobus->origenMunicipio?->nombre ?? $autobus->origen }}</td>
                                    <td>{{ $autobus->destinoMunicipio?->nombre ?? $autobus->destino }}</td>
                                    <td>{{ $autobus->hora_salida }}</td>
                                    <td>
                                        <a href="{{ route('autobus.show', $autobus) }}" class="btn btn-outline-info btn-sm">Ver</a>
                                        <a href="{{ route('autobus.edit', $autobus) }}" class="btn btn-outline-primary btn-sm">Editar</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
