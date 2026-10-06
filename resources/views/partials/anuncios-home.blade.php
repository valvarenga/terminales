@php
    $anuncios = isset($anuncios)
        ? $anuncios
        : \App\Models\Anuncio::query()
            ->activos()
            ->orderBy('orden')
            ->orderByDesc('created_at')
            ->limit(3)
            ->get();
@endphp

<section class="container py-4 py-lg-5" aria-label="Publicidad">

    @if($anuncios->isNotEmpty())

        {{-- =========================================================
             UN SOLO ANUNCIO
        ========================================================== --}}
        @if($anuncios->count() === 1)

            @php($a = $anuncios->first())

            <div class="ad-banner content-card p-3 p-lg-4">

                @if($a->imagen)

                    <a
                        href="{{ $a->enlace ?: '#' }}"
                        @if($a->enlace)
                            target="_blank"
                            rel="noopener nofollow"
                        @endif
                        class="d-block text-decoration-none"
                    >

                        <div
                            class="ad-image-wrapper rounded overflow-hidden"
                            style="
                                width: 100%;
                                max-width: 1500px;
                                height: 180px;
                                margin: 0 auto;
                                background: #f8f9fa;
                                border: 1px solid #e9ecef;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                            "
                        >

                            <img
                                src="{{ asset('storage/anuncios/' . basename($a->imagen)) }}"
                                alt="Anuncio de {{ $a->negocio }}"
                                style="
                                    width: 100%;
                                    height: 100%;
                                    object-fit: contain;
                                    display: block;
                                "
                                onerror="
                                    this.style.display='none';
                                    this.parentElement.nextElementSibling.style.display='block';
                                "
                            >

                        </div>

                        {{-- Mensaje de error --}}
                        <div
                            class="alert alert-danger mt-2 mb-0"
                            style="display:none;"
                        >
                            No se pudo cargar la imagen:
                            <strong>{{ basename($a->imagen) }}</strong>
                        </div>

                    </a>

                @else

                    <div class="alert alert-secondary mb-0">
                        Este anuncio no tiene imagen.
                    </div>

                @endif

                <div class="text-center mt-2">
                    <small class="text-muted">
                        Espacio publicitario · {{ $a->negocio }}
                    </small>
                </div>

            </div>


        {{-- =========================================================
             VARIOS ANUNCIOS
        ========================================================== --}}
        @else

            <div class="row g-3 g-lg-4">

                @foreach($anuncios as $a)

                    <div class="col-12 col-md-6 col-lg-4">

                        <div class="ad-banner content-card p-2 p-lg-3 h-100">

                            @if($a->imagen)

                                <a
                                    href="{{ $a->enlace ?: '#' }}"
                                    @if($a->enlace)
                                        target="_blank"
                                        rel="noopener nofollow"
                                    @endif
                                    class="d-block text-decoration-none"
                                >

                                    <div
                                        class="ad-image-wrapper rounded overflow-hidden"
                                        style="
                                            width: 100%;
                                            height: 120px;
                                            background: #f8f9fa;
                                            border: 1px solid #e9ecef;
                                            display: flex;
                                            align-items: center;
                                            justify-content: center;
                                        "
                                    >

                                        <img
                                            src="{{ asset('storage/anuncios/' . basename($a->imagen)) }}"
                                            alt="Anuncio de {{ $a->negocio }}"
                                            loading="lazy"
                                            style="
                                                width: 100%;
                                                height: 100%;
                                                object-fit: contain;
                                                display: block;
                                            "
                                            onerror="
                                                this.style.display='none';
                                                this.parentElement.nextElementSibling.style.display='block';
                                            "
                                        >

                                    </div>

                                    {{-- Mensaje de error --}}
                                    <div
                                        class="alert alert-danger mt-2 mb-0"
                                        style="display:none;"
                                    >
                                        No se pudo cargar:
                                        <strong>{{ basename($a->imagen) }}</strong>
                                    </div>

                                </a>

                            @else

                                <div
                                    class="bg-light border rounded d-flex align-items-center justify-content-center"
                                    style="height:120px;"
                                >
                                    <div class="text-center text-muted">
                                        <i class="bi bi-image fs-1"></i>
                                        <p class="mb-0 small">Sin imagen</p>
                                    </div>
                                </div>

                            @endif

                            <div class="text-center mt-2">
                                <small class="text-muted">
                                    {{ $a->negocio }}
                                </small>
                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        @endif


    {{-- =============================================================
         SIN ANUNCIOS
    ============================================================= --}}
    @else

        <div class="ad-banner content-card p-4 p-lg-5">

            <div class="row align-items-center g-4">

                <div class="col-lg-9">

                    <p class="ad-label mb-2">
                        Espacio publicitario
                    </p>

                    <h2 class="h3 mb-2">
                        Tu negocio puede aparecer aquí.
                    </h2>

                    <p class="text-muted mb-0">
                        Promociona tu empresa ante miles de viajeros
                        que planean sus rutas cada mes en Terminales Nicaragua.
                    </p>

                </div>

                <div class="col-lg-3 text-lg-end">

                    <a
                        href="{{ route('anuncios') }}"
                        class="btn btn-outline-primary px-4"
                    >
                        Anúnciate
                    </a>

                </div>

            </div>

        </div>

    @endif

</section>


{{-- ================================================================
     ESTILOS DE PUBLICIDAD
================================================================ --}}
<style>

    .ad-banner {
        border-radius: 16px;
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .ad-banner:hover {
        transform: translateY(-2px);
    }

    .ad-image-wrapper {
        position: relative;
    }

    .ad-image-wrapper img {
        transition: transform .25s ease;
    }

    .ad-banner:hover .ad-image-wrapper img {
        transform: scale(1.02);
    }

    .ad-label {
        font-size: .78rem;
        text-transform: uppercase;
        letter-spacing: .08em;
        font-weight: 600;
        color: #6c757d;
    }

    /* Pantallas pequeñas */
    @media (max-width: 575.98px) {

        .ad-image-wrapper {
            height: 110px !important;
        }

    }

    /* Tablets */
    @media (min-width: 576px) and (max-width: 991.98px) {

        .ad-image-wrapper {
            height: 130px !important;
        }

    }

</style>

