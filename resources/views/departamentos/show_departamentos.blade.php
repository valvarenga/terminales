@extends('layouts.plantilla')

@section('title', 'Departamentos')

@section('content')
<section class="container app-shell">
    <header class="app-heading">
        <div><p class="eyebrow mb-2">Administración</p><h1>Departamentos</h1><p>Gestiona los destinos principales disponibles en la plataforma.</p></div>
        <a href="{{ route('newdepartamento') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1" aria-hidden="true"></i> Nuevo departamento</a>
    </header>

    <div class="content-card p-3 p-md-4">
        @include('partials.search-filter', ['targetId' => 'tabla-departamentos', 'placeholder' => 'Buscar departamento por nombre...'])
        <div class="table-responsive" id="tabla-departamentos">
            <table class="table table-hover align-middle">
                <thead><tr><th scope="col">Departamento</th><th scope="col">Imagen</th><th class="text-end" scope="col">Acciones</th></tr></thead>
                <tbody>
                @forelse($departamentos as $departamento)
                    <tr>
                        <td><strong>{{ $departamento->nombre }}</strong></td>
                        <td><img src="{{ asset($departamento->url) }}" alt="{{ $departamento->nombre }}" width="92" height="58" loading="lazy"></td>
                        <td class="text-end"><a class="btn btn-outline-primary btn-sm" href="{{ route('departamento.ver', $departamento) }}"><i class="bi bi-pencil-square me-1" aria-hidden="true"></i> Editar</a></td>
                    </tr>
                @empty
                    <tr><td colspan="3"><div class="empty-state"><i class="bi bi-map fs-2 d-block mb-2" aria-hidden="true"></i>No hay departamentos registrados.</div></td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
