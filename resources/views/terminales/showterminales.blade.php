@extends('layouts.plantilla')

@section('title', 'Terminales')

@section('content')

<div class="container py-4">


{{-- Encabezado --}}
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

    <div>
        <h1 class="fw-bold mb-1">
            <i class="bi bi-bus-front-fill text-warning me-2"></i>
            Terminales
        </h1>

        <p class="text-muted mb-0">
            Consulta las terminales disponibles en Nicaragua.
        </p>
    </div>

    <a href="{{ session('admin_role') ? route('admin.dashboard') : route('admin.login') }}"
       class="btn btn-warning px-4 shadow-sm">
        <i class="bi bi-house-door-fill me-1"></i>
        Home
    </a>

</div>


{{-- Buscador --}}
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">

        <div class="d-flex align-items-center gap-3 mb-3">

            <div class="search-icon">
                <i class="bi bi-search"></i>
            </div>

            <div>
                <h5 class="fw-bold mb-0">
                    Buscar terminal
                </h5>

                <small class="text-muted">
                    Escribe el nombre de la terminal.
                </small>
            </div>

        </div>

        @include('partials.search-filter', [
            'targetId' => 'lista-terminales',
            'placeholder' => 'Buscar terminal por nombre...'
        ])

    </div>
</div>


{{-- Lista de terminales --}}
<div id="lista-terminales">

    <div class="row g-4">

        @forelse($terminales as $terminal)

            <div class="col-12 col-sm-6 col-lg-4 terminal-item"
                 id="terminal-{{ $terminal->id }}">

                <a href="{{ route('ver.terminal', $terminal) }}"
                   class="terminal-card text-decoration-none">

                    <div class="terminal-card__body">

                        {{-- Icono --}}
                        <div class="terminal-icon">
                            <i class="bi bi-bus-front-fill"></i>
                        </div>

                        {{-- Información --}}
                        <div class="terminal-info">

                            <h3>
                                {{ $terminal->nombre }}
                            </h3>

                            <span>
                                Ver información
                                <i class="bi bi-arrow-right"></i>
                            </span>

                        </div>

                        {{-- Flecha --}}
                        <div class="terminal-arrow">
                            <i class="bi bi-chevron-right"></i>
                        </div>

                    </div>

                </a>

            </div>

        @empty

            <div class="col-12">

                <div class="empty-state text-center py-5">

                    <div class="empty-icon mb-3">
                        <i class="bi bi-bus-front"></i>
                    </div>

                    <h4 class="fw-bold">
                        No hay terminales disponibles
                    </h4>

                    <p class="text-muted mb-0">
                        Actualmente no existen terminales registradas.
                    </p>

                </div>

            </div>

        @endforelse

    </div>

</div>


</div>

<style>

    /* Icono del buscador */
    .search-icon {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        background: rgba(255, 193, 7, .15);
        color: #b8860b;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }


    /* Tarjeta */
    .terminal-card {
        display: block;
        height: 100%;
        background: #fff;
        border-radius: 16px;
        border: 1px solid rgba(0, 0, 0, .06);
        box-shadow: 0 4px 15px rgba(0, 0, 0, .06);
        transition: all .25s ease;
    }


    .terminal-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, .13);
        border-color: rgba(255, 193, 7, .4);
    }


    /* Contenido */
    .terminal-card__body {
        min-height: 130px;
        padding: 22px;
        display: flex;
        align-items: center;
        gap: 16px;
    }


    /* Icono terminal */
    .terminal-icon {
        width: 55px;
        height: 55px;
        min-width: 55px;
        border-radius: 15px;
        background: rgba(255, 193, 7, .14);
        color: #b8860b;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        transition: all .25s ease;
    }


    .terminal-card:hover .terminal-icon {
        background: #ffc107;
        color: #212529;
        transform: scale(1.05);
    }


    /* Información */
    .terminal-info {
        flex: 1;
        min-width: 0;
    }


    .terminal-info h3 {
        margin: 0 0 7px;
        color: #212529;
        font-size: 1.1rem;
        font-weight: 700;
        word-break: break-word;
    }


    .terminal-info span {
        color: #b8860b;
        font-size: .9rem;
        font-weight: 600;
    }


    .terminal-info span i {
        transition: transform .2s ease;
    }


    .terminal-card:hover .terminal-info span i {
        transform: translateX(4px);
    }


    /* Flecha */
    .terminal-arrow {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: #f8f9fa;
        color: #6c757d;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all .25s ease;
    }


    .terminal-card:hover .terminal-arrow {
        background: #ffc107;
        color: #212529;
    }


    /* Estado vacío */
    .empty-state {
        background: #fff;
        border-radius: 18px;
        border: 1px dashed #dee2e6;
    }


    .empty-icon {
        width: 70px;
        height: 70px;
        margin: auto;
        border-radius: 50%;
        background: #f8f9fa;
        color: #adb5bd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
    }


    /* Animación */
    .terminal-item {
        animation: aparecer .4s ease;
    }


    @keyframes aparecer {

        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }

    }


    /* Móvil */
    @media (max-width: 576px) {

        .terminal-card__body {
            padding: 18px;
        }

        .terminal-icon {
            width: 48px;
            height: 48px;
            min-width: 48px;
            font-size: 21px;
        }

    }

</style>

@endsection
