@extends('layouts.plantilla')
@section('title', isset($autobuses) ? 'Horarios de '.$terminal->nombre : 'Terminales')
@section('content')
<section class="container">
    @if(isset($autobuses))
    <header class="page-header"><a href="{{ url()->previous() }}" class="eyebrow">← Volver</a>
        <h1 class="mt-2">{{ $terminal->nombre }}</h1>
        <p>Horarios y servicios disponibles desde esta terminal.</p>
    </header>
    <div class="content-card p-3 p-md-4">@if($autobuses->isEmpty())<div class="empty-state">
            <h2>Próximamente</h2>
            <p class="mb-0">Aún no hay horarios registrados para esta terminal.</p>
        </div>@else <div class="table-responsive">
            <table id="buses" class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Autobús</th>
                        <th>Placa</th>
                        <th>Destino</th>
                        <th>Salida</th>
                        <th>Servicio</th>
                    </tr>
                </thead>
                <tbody>@foreach($autobuses as $autobus)<tr>
                        <td class="fw-bold">{{ $autobus->nombre }}</td>
                        <td>{{ $autobus->placa ?: '—' }}</td>
                        <td>{{ $autobus->destino }}</td>
                        <td>{{ $autobus->hora_salida }}</td>
                        <td><span class="badge rounded-pill bg-success">{{ $autobus->categoria }}</span></td>
                    </tr>@endforeach</tbody>
            </table>
        </div>@endif</div>
    @else
    <header class="page-header">
        <p class="eyebrow">Terminales disponibles</p>
        <h1>Elige tu terminal</h1>
        <p>Selecciona una terminal para revisar los horarios de salida.</p>
    </header>
    <div class="row g-4">@forelse($terminales as $terminal)<div class="col-sm-6 col-lg-4 col-xl-3"><a href="{{ route('departamento.autobuses', $terminal) }}" class="destination-card"><img src="{{ asset($terminal->url_T) }}" alt="{{ $terminal->nombre }}">
                <div class="destination-card__body">
                    <h3>{{ $terminal->nombre }}</h3><span>Consultar horarios →</span>
                </div>
            </a></div>@empty <div class="empty-state content-card">
            <h2>No hay terminales disponibles</h2>
            <p class="mb-0">Todavía no existen horarios para este municipio.</p>
        </div>@endforelse</div>
    @endif
</section>
@endsection