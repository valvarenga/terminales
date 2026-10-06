@extends('layouts.plantilla')
@section('title', 'Publicidad')
@section('content')
<div class="container py-4">
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary mb-3">Volver al panel</a>
    <h1 class="h3">Publicidad</h1>
    <p class="text-muted">Sube los banners que se mostrarán en la página de inicio. Los anuncios inactivos no aparecen a los visitantes.</p>

    @include('admin.partials.errors')
    @if(session('success'))<div class="alert alert-success" role="status">{{ session('success') }}</div>@endif

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="content-card p-4">
                <h2 class="h5 mb-3">Nuevo anuncio</h2>
                <form method="POST" action="{{ route('admin.anuncios.store') }}" enctype="multipart/form-data">
                    @csrf
                    @include('admin.partials.anuncio-form')
                    <button type="submit" class="btn btn-primary mt-3">Subir anuncio</button>
                </form>
            </div>
        </div>
        <div class="col-lg-8">
            @forelse($anuncios as $anuncio)
                <article class="content-card p-3 mb-3">
                    <div class="d-flex gap-3 align-items-start">
                        <img src="{{ Storage::disk('public')->url($anuncio->imagen) }}" alt="Banner de {{ $anuncio->negocio }}" class="rounded" style="width:160px;height:90px;object-fit:cover;flex-shrink:0">
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between flex-wrap gap-2">
                                <div>
                                    <strong>{{ $anuncio->titulo }}</strong>
                                    <span class="badge {{ $anuncio->activo ? 'bg-success' : 'bg-secondary' }} ms-2">{{ $anuncio->activo ? 'Activo' : 'Inactivo' }}</span>
                                    <p class="small text-muted mb-1">{{ $anuncio->negocio }} · Orden {{ $anuncio->orden }}</p>
                                    @if($anuncio->enlace)<p class="small mb-0" style="overflow-wrap:anywhere"><a href="{{ $anuncio->enlace }}" target="_blank" rel="noopener nofollow">{{ $anuncio->enlace }}</a></p>@endif
                                </div>
                                <div class="d-flex gap-2">
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.anuncios.edit', $anuncio) }}">Editar</a>
                                    <form method="POST" action="{{ route('admin.anuncios.destroy', $anuncio) }}" onsubmit="return confirm('¿Eliminar este anuncio? Esta acción no se puede deshacer.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <div class="content-card p-4 text-muted">Aún no hay anuncios. Sube el primero con el formulario de la izquierda.</div>
            @endforelse
            {{ $anuncios->links() }}
        </div>
    </div>
</div>
@endsection
