@extends('layouts.plantilla')
@if(isset($autobuses) && $autobuses->isNotEmpty())
    @section('datatables', true)
@endif
@section('title', isset($autobuses) ? 'Horarios de '.$terminal->nombre : 'Terminales')
@section('estilos')<link rel="stylesheet" href="{{ asset('css/terminal-schedules.css') }}">@endsection
@section('content')
<section class="container py-4">
    @if(isset($autobuses))
        <header class="terminal-title mb-4">
            <div class="terminal-title-icon">
                <i class="bi bi-bus-front-fill" aria-hidden="true"></i>
            </div>
            <div>
                <span class="badge bg-warning text-dark mb-2">
                    <i class="bi bi-clock-fill me-1" aria-hidden="true"></i>
                    Horarios de autobuses
                </span>
                <h1 class="mb-1">
                    {{ $terminal->nombre }}
                </h1>
                <p class="mb-0 text-muted">
                    Horarios y servicios disponibles desde esta terminal.
                </p>
            </div>
        </header>
        <div class="mb-3">
            <a href="{{ url()->previous() }}"
               class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>
                Volver
            </a>
        </div>
        <div class="card bus-card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="bus-card-header">
                <div>
                    <h2 class="mb-1">
                        <i class="bi bi-calendar-week me-2" aria-hidden="true"></i>
                        Horarios disponibles
                    </h2>
                    <p class="mb-0 text-muted">
                        Consulta las salidas desde
                        {{ $terminal->nombre }}.
                    </p>
                </div>
                <span class="bus-count">
                    <i class="bi bi-bus-front-fill me-1" aria-hidden="true"></i>
                    {{ $autobuses->count() }}
                    {{ $autobuses->count() == 1
                        ? 'servicio'
                        : 'servicios' }}
                </span>
            </div>
            @if($autobuses->isEmpty())
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="bi bi-calendar-x" aria-hidden="true"></i>
                    </div>
                    <h2>
                        Próximamente
                    </h2>
                    <p class="mb-0">
                        Aún no hay horarios registrados
                        para esta terminal.
                    </p>
                </div>
            @else
                <div class="table-responsive">
                    <table id="buses"
                           class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th scope="col">
                                    <i class="bi bi-bus-front me-1" aria-hidden="true"></i>
                                    Autobús
                                </th>
                                <th scope="col">
                                    <i class="bi bi-credit-card me-1" aria-hidden="true"></i>
                                    Placa
                                </th>
                                <th scope="col">
                                    <i class="bi bi-geo-alt me-1" aria-hidden="true"></i>
                                    Destino
                                </th>
                                <th scope="col">
                                    <i class="bi bi-clock me-1" aria-hidden="true"></i>
                                    Hora de salida
                                </th>
                                <th scope="col">
                                    <i class="bi bi-cash-coin me-1" aria-hidden="true"></i>
                                    Tarifa
                                </th>
                                <th scope="col">
                                    <i class="bi bi-signpost-2 me-1" aria-hidden="true"></i>
                                    Servicio
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($autobuses as $autobus)
                            <tr>
                                <td>
                                    <div class="bus-name">
                                        <span class="bus-icon">
                                            <i class="bi bi-bus-front-fill" aria-hidden="true"></i>
                                        </span>
                                        <strong>
                                            {{ $autobus->nombre }}
                                        </strong>
                                    </div>
                                </td>
                                <td>
                                    @if($autobus->placa)
                                        <span class="plate">
                                            <i class="bi bi-credit-card-2-front me-1" aria-hidden="true"></i>
                                            {{ $autobus->placa }}
                                        </span>
                                    @else
                                        <span class="text-muted">
                                            —
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="destination">
                                        <span class="destination-icon">
                                            <i class="bi bi-geo-alt-fill" aria-hidden="true"></i>
                                        </span>
                                        <span>
                                            {{ $autobus->destino }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="departure-time">
                                        <span class="time-icon">
                                            <i class="bi bi-clock-fill" aria-hidden="true"></i>
                                        </span>
                                        <strong>
                                            {{ formato_hora($autobus->hora_salida) }}
                                        </strong>
                                    </div>
                                </td>
                                <td>
                                    @if($autobus->tarifa !== null)
                                        <span class="price">
                                            <i class=" me-1"></i>
                                            C$
                                            {{ number_format(
                                                (float) $autobus->tarifa,
                                                2
                                            ) }}
                                        </span>
                                    @else
                                        <span class="text-muted">
                                            <i class="bi bi-question-circle me-1" aria-hidden="true"></i>
                                            Tarifa por confirmar
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if(
                                        strtolower(
                                            trim($autobus->categoria)
                                        ) === 'expreso'
                                    )
                                        <span class="service-badge service-expreso">
                                            <i class="bi bi-lightning-charge-fill" aria-hidden="true"></i>
                                            <span>
                                                Expreso
                                            </span>
                                        </span>
                                    @elseif(
                                        strtolower(
                                            trim($autobus->categoria)
                                        ) === 'ruteado'
                                    )
                                        <span class="service-badge service-ruteado">
                                            <i class="bi bi-signpost-split-fill" aria-hidden="true"></i>
                                            <span>
                                                Ruteado
                                            </span>
                                        </span>
                                    @else
                                        <span class="service-badge service-default">
                                            <i class="bi bi-bus-front-fill" aria-hidden="true"></i>
                                            <span>
                                                {{ $autobus->categoria }}
                                            </span>
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @else
        <header class="terminal-title mb-4">
            <div class="terminal-title-icon">
                <i class="bi bi-building-fill" aria-hidden="true"></i>
            </div>
            <div>
                <span class="badge bg-warning text-dark mb-2">
                    <i class="bi bi-bus-front-fill me-1" aria-hidden="true"></i>
                    Terminales disponibles
                </span>
                <h1 class="mb-1">
                    Elige tu terminal
                </h1>
                <p class="mb-0 text-muted">
                    Selecciona una terminal para revisar
                    los horarios de salida.
                </p>
            </div>
        </header>
        <div class="row g-4">
            @forelse($terminales as $terminal)
                <div class="col-sm-6 col-lg-4 col-xl-3">
                    <a href="{{ route(
                        'departamento.autobuses',
                        $terminal
                    ) }}"
                       class="destination-card">
                        <div class="destination-card__image">
                            @if($terminal->url_T)
                                <img src="{{ asset($terminal->url_T) }}"
                                     alt="{{ $terminal->nombre }}"
                                     loading="lazy">
                            @else
                                <div class="terminal-no-image">
                                    <i class="bi bi-bus-front-fill" aria-hidden="true"></i>
                                    <span>
                                        Sin imagen
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="destination-card__body">
                            <div>
                                <span class="terminal-label">
                                    <i class="bi bi-building me-1" aria-hidden="true"></i>
                                    Terminal
                                </span>
                                <h3>
                                    {{ $terminal->nombre }}
                                </h3>
                            </div>
                            <span class="consult-link">
                                <i class="bi bi-clock-fill me-1" aria-hidden="true"></i>
                                Consultar horarios
                                <i class="bi bi-arrow-right" aria-hidden="true"></i>
                            </span>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12">
                    <div class="empty-state content-card">
                        <div class="empty-icon">
                            <i class="bi bi-building-x" aria-hidden="true"></i>
                        </div>
                        <h2>
                            No hay terminales disponibles
                        </h2>
                        <p class="mb-0">
                            Todavía no existen horarios
                            para este municipio.
                        </p>
                    </div>
                </div>
            @endforelse
        </div>
    @endif
</section>
@endsection
