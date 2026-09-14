@extends('layouts.plantilla')
@section('jquery', true)
@section('datatables', true)
@section('title', isset($autobuses) ? 'Horarios de '.$terminal->nombre : 'Terminales')
@section('content')

{{-- =========================================================
     BOOTSTRAP ICONS
========================================================= --}}
<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


<section class="container py-4">

    {{-- =====================================================
         VISTA DE HORARIOS
    ====================================================== --}}
    @if(isset($autobuses))

        {{-- ENCABEZADO --}}
        <header class="terminal-title mb-4">

            <div class="terminal-title-icon">
                <i class="bi bi-bus-front-fill"></i>
            </div>

            <div>

                <span class="badge bg-warning text-dark mb-2">
                    <i class="bi bi-clock-fill me-1"></i>
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


        {{-- BOTÓN VOLVER --}}
        <div class="mb-3">

            <a href="{{ url()->previous() }}"
               class="btn btn-outline-secondary btn-sm rounded-pill px-3">

                <i class="bi bi-arrow-left me-1"></i>
                Volver

            </a>

        </div>


        {{-- TARJETA --}}
        <div class="card bus-card border-0 shadow-sm rounded-4 overflow-hidden">


            {{-- ENCABEZADO --}}
            <div class="bus-card-header">

                <div>

                    <h2 class="mb-1">

                        <i class="bi bi-calendar-week me-2"></i>

                        Horarios disponibles

                    </h2>

                    <p class="mb-0 text-muted">

                        Consulta las salidas desde
                        {{ $terminal->nombre }}.

                    </p>

                </div>


                {{-- CONTADOR --}}
                <span class="bus-count">

                    <i class="bi bi-bus-front-fill me-1"></i>

                    {{ $autobuses->count() }}

                    {{ $autobuses->count() == 1
                        ? 'servicio'
                        : 'servicios' }}

                </span>

            </div>


            {{-- =================================================
                 SIN RESULTADOS
            ================================================== --}}
            @if($autobuses->isEmpty())

                <div class="empty-state">

                    <div class="empty-icon">

                        <i class="bi bi-calendar-x"></i>

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


                {{-- =================================================
                     TABLA
                ================================================== --}}
                <div class="table-responsive">

                    <table id="buses"
                           class="table table-hover align-middle mb-0">

                        <thead>

                            <tr>

                                <th>
                                    <i class="bi bi-bus-front me-1"></i>
                                    Autobús
                                </th>

                                <th>
                                    <i class="bi bi-credit-card me-1"></i>
                                    Placa
                                </th>

                                <th>
                                    <i class="bi bi-geo-alt me-1"></i>
                                    Destino
                                </th>

                                <th>
                                    <i class="bi bi-clock me-1"></i>
                                    Hora de salida
                                </th>

                                <th>
                                    <i class="bi bi-cash-coin me-1"></i>
                                    Tarifa
                                </th>

                                <th>
                                    <i class="bi bi-signpost-2 me-1"></i>
                                    Servicio
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                        @foreach($autobuses as $autobus)

                            <tr>


                                {{-- =================================
                                     AUTOBÚS
                                ================================== --}}
                                <td>

                                    <div class="bus-name">

                                        <span class="bus-icon">

                                            <i class="bi bi-bus-front-fill"></i>

                                        </span>

                                        <strong>

                                            {{ $autobus->nombre }}

                                        </strong>

                                    </div>

                                </td>


                                {{-- =================================
                                     PLACA
                                ================================== --}}
                                <td>

                                    @if($autobus->placa)

                                        <span class="plate">

                                            <i class="bi bi-credit-card-2-front me-1"></i>

                                            {{ $autobus->placa }}

                                        </span>

                                    @else

                                        <span class="text-muted">

                                            —

                                        </span>

                                    @endif

                                </td>


                                {{-- =================================
                                     DESTINO
                                ================================== --}}
                                <td>

                                    <div class="destination">

                                        <span class="destination-icon">

                                            <i class="bi bi-geo-alt-fill"></i>

                                        </span>

                                        <span>

                                            {{ $autobus->destino }}

                                        </span>

                                    </div>

                                </td>


                                {{-- =================================
                                     HORA
                                ================================== --}}
                                <td>

                                    <div class="departure-time">

                                        <span class="time-icon">

                                            <i class="bi bi-clock-fill"></i>

                                        </span>

                                        <strong>

                                            {{ \Carbon\Carbon::parse(
                                                $autobus->hora_salida
                                            )->format('g:i A') }}

                                        </strong>

                                    </div>

                                </td>


                                {{-- =================================
                                     TARIFA
                                ================================== --}}
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

                                            <i class="bi bi-question-circle me-1"></i>

                                            Tarifa por confirmar

                                        </span>

                                    @endif

                                </td>


                                {{-- =================================
                                     SERVICIO
                                ================================== --}}
                                <td>

                                    @if(
                                        strtolower(
                                            trim($autobus->categoria)
                                        ) === 'expreso'
                                    )

                                        <span class="service-badge service-expreso">

                                            <i class="bi bi-lightning-charge-fill"></i>

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

                                            <i class="bi bi-signpost-split-fill"></i>

                                            <span>
                                                Ruteado
                                            </span>

                                        </span>


                                    @else

                                        <span class="service-badge service-default">

                                            <i class="bi bi-bus-front-fill"></i>

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


    {{-- =====================================================
         VISTA DE TERMINALES
    ====================================================== --}}
    @else


        {{-- ENCABEZADO --}}
        <header class="terminal-title mb-4">

            <div class="terminal-title-icon">

                <i class="bi bi-building-fill"></i>

            </div>

            <div>

                <span class="badge bg-warning text-dark mb-2">

                    <i class="bi bi-bus-front-fill me-1"></i>

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


        {{-- =================================================
             TERMINALES
        ================================================== --}}
        <div class="row g-4">


            @forelse($terminales as $terminal)


                <div class="col-sm-6 col-lg-4 col-xl-3">


                    <a href="{{ route(
                        'departamento.autobuses',
                        $terminal
                    ) }}"
                       class="destination-card">


                        {{-- IMAGEN --}}
                        <div class="destination-card__image">


                            @if($terminal->url_T)

                                <img src="{{ asset($terminal->url_T) }}"
                                     alt="{{ $terminal->nombre }}"
                                     loading="lazy">

                            @else

                                <div class="terminal-no-image">

                                    <i class="bi bi-bus-front-fill"></i>

                                    <span>
                                        Sin imagen
                                    </span>

                                </div>

                            @endif


                        </div>


                        {{-- INFORMACIÓN --}}
                        <div class="destination-card__body">


                            <div>

                                <span class="terminal-label">

                                    <i class="bi bi-building me-1"></i>

                                    Terminal

                                </span>


                                <h3>

                                    {{ $terminal->nombre }}

                                </h3>

                            </div>


                            <span class="consult-link">

                                <i class="bi bi-clock-fill me-1"></i>

                                Consultar horarios

                                <i class="bi bi-arrow-right"></i>

                            </span>


                        </div>


                    </a>

                </div>


            @empty


                <div class="col-12">

                    <div class="empty-state content-card">


                        <div class="empty-icon">

                            <i class="bi bi-building-x"></i>

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



<style>

/* =========================================================
   ENCABEZADO
========================================================= */

.terminal-title {

    display: flex;

    align-items: center;

    gap: 18px;

}

.terminal-title-icon {

    width: 68px;

    height: 68px;

    flex-shrink: 0;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 18px;

    background: linear-gradient(
        135deg,
        #ffc107,
        #e0a800
    );

    color: #212529;

    font-size: 30px;

    box-shadow:
        0 8px 20px rgba(0,0,0,.10);

}

.terminal-title h1 {

    font-size: 2rem;

    font-weight: 800;

    margin: 0;

}

.terminal-title p {

    font-size: .95rem;

}


/* =========================================================
   TARJETA DE HORARIOS
========================================================= */

.bus-card {

    background: #fff;

}

.bus-card-header {

    padding: 24px;

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 20px;

    border-bottom: 1px solid #eeeeee;

}

.bus-card-header h2 {

    font-size: 1.2rem;

    font-weight: 700;

}

.bus-count {

    display: inline-flex;

    align-items: center;

    padding: 8px 14px;

    border-radius: 50px;

    background: rgba(13,110,253,.10);

    color: #0d6efd;

    font-size: .85rem;

    font-weight: 700;

    white-space: nowrap;

}


/* =========================================================
   TABLA
========================================================= */

#buses thead th {

    background: #f8f9fa;

    color: #495057;

    font-size: .78rem;

    text-transform: uppercase;

    letter-spacing: .04em;

    font-weight: 700;

    padding: 15px 16px;

    white-space: nowrap;

}

#buses tbody td {

    padding: 16px;

}

#buses tbody tr {

    transition: background .2s ease;

}


/* =========================================================
   AUTOBÚS
========================================================= */

.bus-name {

    display: flex;

    align-items: center;

    gap: 10px;

    white-space: nowrap;

}

.bus-icon {

    width: 38px;

    height: 38px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    border-radius: 11px;

    background: rgba(13,110,253,.10);

    color: #0d6efd;

    font-size: 17px;

}


/* =========================================================
   PLACA
========================================================= */

.plate {

    display: inline-flex;

    align-items: center;

    padding: 6px 10px;

    border-radius: 7px;

    background: #f1f3f5;

    border: 1px solid #dee2e6;

    color: #343a40;

    font-size: .82rem;

    font-weight: 700;

    white-space: nowrap;

}


/* =========================================================
   DESTINO
========================================================= */

.destination {

    display: inline-flex;

    align-items: center;

    gap: 8px;

    font-weight: 600;

}

.destination-icon {

    width: 30px;

    height: 30px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    border-radius: 9px;

    background: rgba(220,53,69,.10);

    color: #dc3545;

}


/* =========================================================
   HORA
========================================================= */

.departure-time {

    display: inline-flex;

    align-items: center;

    gap: 8px;

    white-space: nowrap;

}

.time-icon {

    width: 34px;

    height: 34px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    border-radius: 10px;

    background: rgba(25,135,84,.12);

    color: #198754;

}

.time-icon i {

    font-size: 15px;

}

.departure-time strong {

    color: #198754;

    font-size: 1rem;

}

/* =========================================================
   SERVICIOS
========================================================= */

.service-badge {

    display: inline-flex;

    align-items: center;

    gap: 7px;

    padding: 7px 12px;

    border-radius: 50px;

    font-size: .82rem;

    font-weight: 700;

    white-space: nowrap;

}

.service-badge i {

    font-size: 15px;

}


/* EXPRESO */

.service-expreso {

    background: rgba(255,193,7,.18);

    color: #946f00;

}

.service-expreso i {

    color: #e0a800;

}


/* RUTEADO */

.service-ruteado {

    background: rgba(13,110,253,.12);

    color: #0d6efd;

}

.service-ruteado i {

    color: #0d6efd;

}


/* OTRO */

.service-default {

    background: rgba(108,117,125,.12);

    color: #6c757d;

}


/* =========================================================
   ESTADO VACÍO
========================================================= */

.empty-state {

    text-align: center;

    padding: 60px 20px;

}

.empty-icon {

    width: 70px;

    height: 70px;

    margin: 0 auto 18px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 20px;

    background: #f1f3f5;

    color: #adb5bd;

    font-size: 30px;

}

.empty-state h2 {

    font-size: 1.3rem;

    font-weight: 700;

    margin-bottom: 8px;

}

.empty-state p {

    color: #6c757d;

}


/* =========================================================
   TARJETAS DE TERMINALES
========================================================= */

.destination-card {

    display: block;

    height: 100%;

    overflow: hidden;

    background: #fff;

    border-radius: 18px;

    text-decoration: none;

    color: inherit;

    box-shadow:
        0 5px 18px rgba(0,0,0,.07);

    transition:
        transform .25s ease,
        box-shadow .25s ease;

}

.destination-card:hover {

    color: inherit;

    transform: translateY(-6px);

    box-shadow:
        0 12px 30px rgba(0,0,0,.12);

}


/* =========================================================
   IMAGEN
========================================================= */

.destination-card__image {

    width: 100%;

    height: 210px;

    overflow: hidden;

    background: #f1f3f5;

}

.destination-card__image img {

    width: 100%;

    height: 100%;

    display: block;

    object-fit: cover;

    transition: transform .4s ease;

}

.destination-card:hover
.destination-card__image img {

    transform: scale(1.05);

}


/* =========================================================
   SIN IMAGEN
========================================================= */

.terminal-no-image {

    width: 100%;

    height: 100%;

    display: flex;

    flex-direction: column;

    align-items: center;

    justify-content: center;

    gap: 8px;

    color: #adb5bd;

}

.terminal-no-image i {

    font-size: 50px;

}


/* =========================================================
   CUERPO DE TARJETA
========================================================= */

.destination-card__body {

    min-height: 145px;

    padding: 18px;

    display: flex;

    flex-direction: column;

    justify-content: space-between;

}

.destination-card__body h3 {

    margin: 5px 0 10px;

    font-size: 1.15rem;

    font-weight: 800;

}

.terminal-label {

    color: #6c757d;

    font-size: .75rem;

    font-weight: 700;

    text-transform: uppercase;

    letter-spacing: .04em;

}

.consult-link {

    display: inline-flex;

    align-items: center;

    justify-content: space-between;

    gap: 8px;

    color: #0d6efd;

    font-size: .9rem;

    font-weight: 700;

}

.destination-card:hover
.consult-link {

    color: #084298;

}


/* =========================================================
   BOTONES
========================================================= */

.btn {

    border-radius: 10px;

    font-weight: 600;

}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 768px) {

    .terminal-title {

        align-items: flex-start;

    }

    .terminal-title-icon {

        width: 58px;

        height: 58px;

        font-size: 25px;

        border-radius: 15px;

    }

    .terminal-title h1 {

        font-size: 1.6rem;

    }

    .bus-card-header {

        align-items: flex-start;

        flex-direction: column;

    }

    #buses tbody td {

        padding: 14px;

    }

    .destination-card__image {

        height: 190px;

    }

}


@media (max-width: 576px) {

    .terminal-title {

        gap: 12px;

    }

    .terminal-title-icon {

        width: 50px;

        height: 50px;

        font-size: 22px;

        border-radius: 13px;

    }

    .terminal-title h1 {

        font-size: 1.35rem;

    }

    .terminal-title p {

        font-size: .85rem;

    }

    .bus-card-header {

        padding: 18px;

    }

    .bus-count {

        width: 100%;

        justify-content: center;

    }

    .destination-card__image {

        height: 210px;

    }

}

</style>

@endsection

<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


