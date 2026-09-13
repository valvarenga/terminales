@extends('layouts.plantilla')
@section('title', 'Ver municipio')

@section('content')

<div class="container py-4">

```
{{-- Encabezado --}}
<div class="mb-4">

    <a href="{{ route('municipio.show') }}"
       class="text-decoration-none text-muted">
        ← Regresar a municipios
    </a>

</div>


{{-- Mensaje de error --}}
@error('municipio')
    <div class="alert alert-danger shadow-sm border-0 rounded-3">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        {{ $message }}
    </div>
@enderror


{{-- Tarjeta principal --}}
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">

    <div class="row g-0">

        {{-- Imagen --}}
        <div class="col-md-5">

            <div class="municipio-imagen">

                @if($municipio->url_M)

                    <img src="{{ asset($municipio->url_M) }}"
                         alt="{{ $municipio->nombre }}">

                @else

                    <div class="sin-imagen">
                        <i class="bi bi-geo-alt-fill"></i>
                        <span>Sin imagen</span>
                    </div>

                @endif

            </div>

        </div>


        {{-- Información --}}
        <div class="col-md-7">

            <div class="card-body p-4 p-lg-5">

                <span class="badge bg-warning text-dark mb-3">
                    Municipio
                </span>

                <h1 class="fw-bold mb-2">
                    {{ $municipio->nombre }}
                </h1>

                <p class="text-muted mb-4">
                    Información del municipio
                </p>


                {{-- Departamento --}}
                <div class="info-item mb-4">

                    <div class="info-icon">
                        <i class="bi bi-map-fill"></i>
                    </div>

                    <div>
                        <small class="text-muted d-block">
                            Departamento
                        </small>

                        <strong>
                            {{ $departamento->nombre }}
                        </strong>
                    </div>

                </div>


                {{-- ID --}}
                <div class="info-item mb-4">

                    <div class="info-icon">
                        <i class="bi bi-hash"></i>
                    </div>

                    <div>
                        <small class="text-muted d-block">
                            Identificador
                        </small>

                        <strong>
                            {{ $municipio->id }}
                        </strong>
                    </div>

                </div>


                {{-- Separador --}}
                <hr class="my-4">


                {{-- Botones --}}
                <div class="d-flex flex-wrap gap-2">

                    {{-- Editar --}}
                    <a href="{{ route('municipio.edit', $municipio) }}"
                       class="btn btn-primary px-4">

                        <i class="bi bi-pencil-square me-1"></i>
                        Editar

                    </a>


                    {{-- Eliminar --}}
                    <form action="{{ route('municipio.destroy', $municipio) }}"
                          method="POST"
                          class="d-inline">

                        @csrf
                        @method('DELETE')

                        <button type="submit"
                                class="btn btn-outline-danger px-4"
                                onclick="return confirm('¿Estás seguro de que deseas eliminar este municipio?')">

                            <i class="bi bi-trash3 me-1"></i>
                            Eliminar

                        </button>

                    </form>


                    {{-- Regresar --}}
                    <a href="{{ route('municipio.show') }}"
                       class="btn btn-outline-secondary px-4">

                        <i class="bi bi-arrow-left me-1"></i>
                        Regresar

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>
```

</div>

<style>

    /* Tarjeta */
    .card {
        background: #fff;
    }


    /* Imagen */
    .municipio-imagen {
        height: 100%;
        min-height: 420px;
        overflow: hidden;
        background: #f1f1f1;
    }

    .municipio-imagen img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform .4s ease;
    }

    .municipio-imagen:hover img {
        transform: scale(1.04);
    }


    /* Sin imagen */
    .sin-imagen {
        height: 100%;
        min-height: 420px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #adb5bd;
        gap: 10px;
    }

    .sin-imagen i {
        font-size: 70px;
    }


    /* Información */
    .info-item {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .info-icon {
        width: 45px;
        height: 45px;
        flex-shrink: 0;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 193, 7, .15);
        color: #b8860b;
        font-size: 19px;
    }


    /* Botones */
    .btn {
        border-radius: 10px;
        font-weight: 500;
    }


    /* Móvil */
    @media (max-width: 767px) {

        .municipio-imagen {
            min-height: 280px;
        }

        .sin-imagen {
            min-height: 280px;
        }

    }

</style>

@endsection
