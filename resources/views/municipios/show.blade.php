@extends('layouts.plantilla')
@section('title', 'Ver municipios')

@section('content')

<section class="container py-4">

{{-- Encabezado --}}
<header class="page-header mb-4">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">

        <div>
            <a href="{{ session('admin_role') ? route('admin.dashboard') : route('admin.login') }}"
               class="eyebrow text-decoration-none">
                ← Home
            </a>

            <h1 class="mt-2 mb-1">
                Municipios
            </h1>

            <p class="text-muted mb-0">
                Selecciona un municipio para consultar su información.
            </p>
        </div>

    </div>

</header>


{{-- Buscador --}}
<div class="mb-4">
    @include('partials.search-filter', [
        'targetId' => 'lista-municipios',
        'placeholder' => 'Buscar municipio por nombre...'
    ])
</div>


{{-- Lista de municipios --}}
<div id="lista-municipios">

    <div class="row g-4">

        @forelse($municipios as $municipio)

            <div class="col-sm-6 col-lg-4 col-xl-3 municipio-item">

                <a href="{{ route('municipio.ver', $municipio) }}"
                   class="destination-card text-decoration-none">

                    {{-- Imagen del municipio --}}
                    <div class="destination-card__image">

                        @if($municipio->url_M)

                            <img src="{{ asset($municipio->url_M) }}"
                                 alt="{{ $municipio->nombre }}"
                                 loading="lazy">

                        @else

                            <div class="municipio-sin-imagen">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>

                        @endif

                    </div>


                    {{-- Información --}}
                    <div class="destination-card__body">

                        <h3>
                            {{ $municipio->nombre }}
                        </h3>

                        <span>
                            Ver municipio →
                        </span>

                    </div>

                </a>

            </div>

        @empty

            <div class="col-12">

                <div class="empty-state content-card">

                    <h2>Sin municipios disponibles</h2>

                    <p class="mb-0">
                        Aún no hay municipios registrados.
                    </p>

                </div>

            </div>

        @endforelse

    </div>

</div>

</section>

<style>

    /* Tarjeta */
    .destination-card {
        display: block;
        height: 100%;
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, .08);
        transition: transform .25s ease, box-shadow .25s ease;
    }

    .destination-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, .15);
    }


    /* Imagen */
    .destination-card__image {
        width: 100%;
        height: 210px;
        overflow: hidden;
        background: #f1f1f1;
    }

    .destination-card__image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform .4s ease;
    }

    .destination-card:hover .destination-card__image img {
        transform: scale(1.07);
    }


    /* Sin imagen */
    .municipio-sin-imagen {
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #adb5bd;
        font-size: 55px;
    }


    /* Texto */
    .destination-card__body {
        padding: 18px;
    }

    .destination-card__body h3 {
        margin: 0 0 7px;
        font-size: 1.2rem;
        font-weight: 700;
        color: #222;
    }

    .destination-card__body span {
        font-size: .9rem;
        color: #b8860b;
        font-weight: 600;
    }


    /* Animación */
    .municipio-item {
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

</style>

@endsection
