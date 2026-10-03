@extends('layouts.plantilla')

@section('title', 'Autobuses')

@section('content')
<section class="container app-shell">
    <header class="app-heading">
        <div><p class="eyebrow mb-2">Administración</p><h1>Autobuses y horarios</h1><p>Consulta, actualiza o reutiliza los servicios registrados.</p></div>
        <a href="{{ route('newbus') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1" aria-hidden="true"></i> Registrar servicio</a>
    </header>

    <div class="content-card p-3 p-md-4">
        @include('partials.search-filter', ['targetId' => 'tabla-autobuses', 'placeholder' => 'Buscar por nombre, terminal, origen o destino...'])
        @if($autobuses->isEmpty())
            <div class="empty-state"><i class="bi bi-bus-front fs-2 d-block mb-2" aria-hidden="true"></i><h2 class="h5">No hay autobuses registrados</h2><p class="mb-3">Registra el primer servicio para comenzar.</p><a href="{{ route('newbus') }}" class="btn btn-primary btn-sm">Registrar servicio</a></div>
        @else
            <div class="table-responsive" id="tabla-autobuses">
                <table class="table table-hover align-middle">
                    <thead><tr><th scope="col">Servicio</th><th scope="col">Terminal</th><th scope="col">Recorrido</th><th scope="col">Salida</th><th scope="col">Tarifa</th><th class="text-end" scope="col">Acciones</th></tr></thead>
                    <tbody>
                    @foreach($autobuses as $autobus)
                        <tr>
                            <td><strong class="d-block">{{ $autobus->nombre }}</strong><span class="small text-muted">{{ $autobus->categoria ?: 'Sin categoría' }}@if($autobus->placa) · {{ $autobus->placa }}@endif</span></td>
                            <td>@forelse($autobus->terminales as $terminal)<span class="badge bg-light text-dark border">{{ $terminal->nombre }}</span>@empty<span class="text-muted small">Sin terminal</span>@endforelse</td>
                            <td><span class="d-block">{{ $autobus->origenMunicipio?->nombre ?? $autobus->origen }}</span><span class="small text-muted"><i class="bi bi-arrow-right me-1" aria-hidden="true"></i>{{ $autobus->destinoMunicipio?->nombre ?? $autobus->destino }}</span></td>
                            <td><span class="badge bg-success">{{ $autobus->hora_salida ? substr($autobus->hora_salida, 0, 5) : 'Por confirmar' }}</span></td>
                            <td class="fw-semibold">{{ $autobus->tarifa !== null ? 'C$ '.number_format((float) $autobus->tarifa, 2) : 'Por confirmar' }}</td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('autobus.show', $autobus) }}" class="btn btn-info btn-sm" title="Ver detalle"><i class="bi bi-eye" aria-hidden="true"></i><span class="visually-hidden"> Ver</span></a>
                                <a href="{{ route('autobus.edit', $autobus) }}" class="btn btn-outline-primary btn-sm" title="Editar"><i class="bi bi-pencil-square" aria-hidden="true"></i><span class="visually-hidden"> Editar</span></a>
                                <a href="{{ route('autobus.duplicate', $autobus) }}" class="btn btn-outline-success btn-sm" title="Registrar otra salida"><i class="bi bi-copy" aria-hidden="true"></i><span class="visually-hidden"> Otra salida</span></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</section>
@endsection
