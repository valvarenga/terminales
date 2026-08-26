@extends('layouts.plantilla')

@section('title', 'Panel administrativo')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Panel administrativo</h1>
            <p class="text-muted mb-0">Aquí están los formularios para crear y modificar datos.</p>
        </div>
        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-secondary">Cerrar sesión</button>
        </form>
    </div>

    <div class="row g-4">
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <h2 class="h5">Departamentos</h2>
                    <p class="text-muted">Crear, consultar y administrar departamentos.</p>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('newdepartamento') }}" class="btn btn-primary">Registrar</a>
                        <a href="{{ route('departamentos.show') }}" class="btn btn-outline-primary">Listar</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <h2 class="h5">Municipios</h2>
                    <p class="text-muted">Crear, consultar y administrar municipios.</p>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('newmunicipio') }}" class="btn btn-primary">Registrar</a>
                        <a href="{{ route('municipio.show') }}" class="btn btn-outline-primary">Listar</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <h2 class="h5">Terminales</h2>
                    <p class="text-muted">Crear, consultar y administrar terminales.</p>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('newterminal') }}" class="btn btn-primary">Registrar</a>
                        <a href="{{ route('show_terminal') }}" class="btn btn-outline-primary">Listar</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <h2 class="h5">Autobuses</h2>
                    <p class="text-muted">Ver, editar y administrar los servicios registrados.</p>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('newbus') }}" class="btn btn-primary">Registrar</a>
                        <a href="{{ route('autobuses.list') }}" class="btn btn-outline-primary">Listar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h2 class="h4 mb-1">Fotos sugeridas por usuarios</h2>
                <p class="text-muted mb-0">Terminales que aún están pendientes de revisión.</p>
            </div>
            <span class="badge bg-primary rounded-pill">{{ $sugerenciasTerminales->count() }}</span>
        </div>
        @forelse($sugerenciasTerminales as $sugerencia)
            <article class="card shadow-sm border-0 mb-3 overflow-hidden">
                <div class="row g-0">
                    @if ($sugerencia->foto)
                        <div class="col-md-3"><img src="{{ route('admin.suggestions.photo', $sugerencia) }}" class="img-fluid h-100 w-100" style="object-fit: cover; min-height: 180px" alt="Foto de {{ $sugerencia->nombre_terminal }}"></div>
                    @endif
                    <div class="col-md-9"><div class="card-body"><h3 class="h5">{{ $sugerencia->nombre_terminal }}</h3><p class="mb-2"><strong>Ubicación:</strong> {{ $sugerencia->ubicacion ?: 'No indicada' }}</p><small class="text-muted">Enviada el {{ $sugerencia->created_at->format('d/m/Y H:i') }}</small></div></div>
                </div>
            </article>
        @empty
            <div class="alert alert-light border mb-0">Aún no hay fotos enviadas por usuarios.</div>
        @endforelse
    </section>
</div>
@endsection
