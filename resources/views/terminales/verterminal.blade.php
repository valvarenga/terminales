@extends('layouts.plantilla')

@section('title', 'Ver terminal')

@section('content')

<div class="container py-4">

    {{-- =========================================
         REGRESAR
    ========================================== --}}
    <div class="mb-4">

        <a href="{{ route('show_terminal') }}"
           class="text-decoration-none text-muted">

            <i class="bi bi-arrow-left me-1"></i>

            Regresar a terminales

        </a>

    </div>


    {{-- =========================================
         MENSAJE DE ERROR
    ========================================== --}}
    @error('terminal')

        <div class="alert alert-danger border-0 shadow-sm rounded-3">

            <i class="bi bi-exclamation-triangle-fill me-2"></i>

            {{ $message }}

        </div>

    @enderror


    {{-- =========================================
         TARJETA PRINCIPAL
    ========================================== --}}
    <div class="card terminal-detail border-0 shadow-sm rounded-4 overflow-hidden">


        {{-- =====================================
             IMAGEN DE LA TERMINAL
        ====================================== --}}
        <div class="terminal-image">

            @if($terminales->url_T)

                <img src="{{ asset($terminales->url_T) }}"
                     alt="{{ $terminales->nombre }}"
                     loading="lazy">

            @else

                <div class="no-image">

                    <i class="bi bi-bus-front-fill"></i>

                    <span>
                        Sin imagen disponible
                    </span>

                </div>

            @endif

        </div>


        {{-- =====================================
             ENCABEZADO
        ====================================== --}}
        <div class="terminal-header">

            <div class="terminal-header-icon">

                <i class="bi bi-bus-front-fill"></i>

            </div>


            <div>

                <span class="badge bg-warning text-dark mb-2">

                    Terminal de autobuses

                </span>


                <h1 class="fw-bold mb-1">

                    {{ $terminales->nombre }}

                </h1>


                <p class="mb-0 opacity-75">

                    Información de la terminal

                </p>

            </div>

        </div>


        {{-- =====================================
             INFORMACIÓN
        ====================================== --}}
        <div class="card-body p-4 p-lg-5">

            <div class="row g-4">


                {{-- =================================
                     HORA DE APERTURA
                ================================== --}}
                <div class="col-md-6">

                    <div class="info-card">

                        <div class="info-icon apertura">

                            <i class="bi bi-clock-fill"></i>

                        </div>


                        <div>

                            <small class="text-muted d-block">

                                Hora de apertura

                            </small>


                            <strong class="fs-5">

                                {{ $terminales->hora_apertura }}

                            </strong>

                        </div>

                    </div>

                </div>


                {{-- =================================
                     HORA DE CIERRE
                ================================== --}}
                <div class="col-md-6">

                    <div class="info-card">

                        <div class="info-icon cierre">

                            <i class="bi bi-clock-history"></i>

                        </div>


                        <div>

                            <small class="text-muted d-block">

                                Hora de cierre

                            </small>


                            <strong class="fs-5">

                                {{ $terminales->hora_cierre }}

                            </strong>

                        </div>

                    </div>

                </div>


                {{-- =================================
                     DEPARTAMENTO
                ================================== --}}
                <div class="col-md-6">

                    <div class="info-card">

                        <div class="info-icon departamento">

                            <i class="bi bi-map-fill"></i>

                        </div>


                        <div>

                            <small class="text-muted d-block">

                                Departamento

                            </small>


                            <strong class="fs-5">

                                {{ $departamento->nombre }}

                            </strong>

                        </div>

                    </div>

                </div>


                {{-- =================================
                     MUNICIPIO
                ================================== --}}
                <div class="col-md-6">

                    <div class="info-card">

                        <div class="info-icon municipio">

                            <i class="bi bi-geo-alt-fill"></i>

                        </div>


                        <div>

                            <small class="text-muted d-block">

                                Municipio

                            </small>


                            <strong class="fs-5">

                                {{ $municipio->nombre }}

                            </strong>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =====================================
                 SEPARADOR
            ====================================== --}}
            <hr class="my-4">


            {{-- =====================================
                 BOTONES
            ====================================== --}}
            <div class="d-flex flex-wrap gap-2">


                {{-- EDITAR --}}
                <a href="{{ route('terminal.edit', $terminales) }}"
                   class="btn btn-success px-4">

                    <i class="bi bi-pencil-square me-1"></i>

                    Editar

                </a>


                {{-- ELIMINAR --}}
                <form action="{{ route('terminal.destroy', $terminales) }}"
                      method="POST"
                      class="d-inline">

                    @csrf

                    @method('DELETE')


                    <button type="submit"
                            class="btn btn-danger px-4"
                            onclick="return confirm('¿Eliminar esta terminal?')">

                        <i class="bi bi-trash3 me-1"></i>

                        Eliminar

                    </button>

                </form>


                {{-- VER AUTOBUSES --}}
                <a href="{{ route('departamento.autobuses', $terminales) }}"
                   class="btn btn-warning px-4">

                    <i class="bi bi-bus-front me-1"></i>

                    Ver autobuses

                </a>


                {{-- REGRESAR --}}
                <a href="{{ route('show_terminal') }}"
                   class="btn btn-outline-secondary px-4">

                    <i class="bi bi-arrow-left me-1"></i>

                    Regresar

                </a>

            </div>

        </div>

    </div>

</div>


{{-- ==================================================
     ESTILOS
================================================== --}}
<style>

    /* =========================================
       TARJETA PRINCIPAL
    ========================================= */

    .terminal-detail {

        background: #ffffff;

    }


    /* =========================================
       IMAGEN DE LA TERMINAL
    ========================================= */

    .terminal-image {

        width: 100%;

        height: 350px;

        overflow: hidden;

        background: #f1f3f5;

    }


    .terminal-image img {

        width: 100%;

        height: 100%;

        object-fit: cover;

        display: block;

        transition: transform .5s ease;

    }


    .terminal-image:hover img {

        transform: scale(1.03);

    }


    /* =========================================
       SIN IMAGEN
    ========================================= */

    .no-image {

        width: 100%;

        height: 100%;

        display: flex;

        flex-direction: column;

        align-items: center;

        justify-content: center;

        gap: 10px;

        color: #adb5bd;

    }


    .no-image i {

        font-size: 70px;

    }


    .no-image span {

        font-size: 1rem;

    }


    /* =========================================
       ENCABEZADO
    ========================================= */

    .terminal-header {

        padding: 30px;

        display: flex;

        align-items: center;

        gap: 20px;

        background: linear-gradient(
            135deg,
            #ffc107,
            #e0a800
        );

        color: #212529;

    }


    .terminal-header-icon {

        width: 70px;

        height: 70px;

        flex-shrink: 0;

        border-radius: 18px;

        background: rgba(255,255,255,.35);

        display: flex;

        align-items: center;

        justify-content: center;

        font-size: 32px;

    }


    .terminal-header h1 {

        font-size: 2rem;

    }


    /* =========================================
       TARJETAS DE INFORMACIÓN
    ========================================= */

    .info-card {

        height: 100%;

        padding: 20px;

        display: flex;

        align-items: center;

        gap: 16px;

        border: 1px solid #eeeeee;

        border-radius: 15px;

        background: #fafafa;

        transition: all .25s ease;

    }


    .info-card:hover {

        transform: translateY(-3px);

        box-shadow:
            0 6px 18px rgba(0,0,0,.08);

        background: #ffffff;

    }


    /* =========================================
       ICONOS DE INFORMACIÓN
    ========================================= */

    .info-icon {

        width: 50px;

        height: 50px;

        flex-shrink: 0;

        border-radius: 14px;

        display: flex;

        align-items: center;

        justify-content: center;

        font-size: 21px;

    }


    .apertura {

        background: rgba(25,135,84,.12);

        color: #198754;

    }


    .cierre {

        background: rgba(220,53,69,.12);

        color: #dc3545;

    }


    .departamento {

        background: rgba(13,110,253,.12);

        color: #0d6efd;

    }


    .municipio {

        background: rgba(255,193,7,.18);

        color: #b8860b;

    }


    /* =========================================
       BOTONES
    ========================================= */

    .terminal-detail .btn {

        border-radius: 10px;

        font-weight: 500;

        transition: all .2s ease;

    }


    .terminal-detail .btn:hover {

        transform: translateY(-1px);

    }


    /* =========================================
       RESPONSIVE - TABLET
    ========================================= */

    @media (max-width: 768px) {

        .terminal-image {

            height: 280px;

        }


        .terminal-header {

            padding: 25px 20px;

        }


        .terminal-header-icon {

            width: 60px;

            height: 60px;

            font-size: 27px;

        }


        .terminal-header h1 {

            font-size: 1.7rem;

        }

    }


    /* =========================================
       RESPONSIVE - CELULAR
    ========================================= */

    @media (max-width: 576px) {

        .terminal-image {

            height: 240px;

        }


        .terminal-header {

            padding: 22px 18px;

            align-items: flex-start;

        }


        .terminal-header-icon {

            width: 52px;

            height: 52px;

            border-radius: 14px;

            font-size: 23px;

        }


        .terminal-header h1 {

            font-size: 1.4rem;

        }


        .terminal-header p {

            font-size: .9rem;

        }


        .info-card {

            padding: 16px;

        }


        .info-icon {

            width: 45px;

            height: 45px;

        }


        .terminal-detail .btn {

            width: 100%;

        }

    }

</style>

@endsection
```
