@extends('layouts.plantilla')
@section('title', 'Horarios de buses')
@section('content')
<section class="hero"><div class="container"><div class="row align-items-center g-5">
    <div class="col-lg-7"><p class="eyebrow">Viaja con claridad</p><h1 class="hero-title">Tu ruta comienza aquí.</h1><p class="hero-copy mt-4">Encuentra los buses y transbordos necesarios para viajar entre municipios de Nicaragua.</p>
        <form id="buscar-ruta" method="GET" action="{{ route('buscar.index') }}" data-municipios-url="{{ route('municipios.search') }}" class="search-card mt-4"><div class="row g-3">
            <div class="col-md-6"><label for="origen" class="fw-bold mb-2">Origen</label><input type="text" class="form-control" placeholder="Selecciona un municipio" id="origen" autocomplete="off" value="{{ old('origen') }}"><input type="hidden" id="origen_id" name="origen_id" value="{{ old('origen_id') }}">@error('origen_id')<small class="text-danger d-block mt-1">{{ $message }}</small>@enderror</div>
            <div class="col-md-6"><label for="destino" class="fw-bold mb-2">Destino</label><input type="text" class="form-control" placeholder="Selecciona un municipio" id="destino" autocomplete="off" value="{{ old('destino') }}"><input type="hidden" id="destino_id" name="destino_id" value="{{ old('destino_id') }}">@error('destino_id')<small class="text-danger d-block mt-1">{{ $message }}</small>@enderror</div>
            <div class="col-12"><button type="submit" class="btn btn-warning px-4">Buscar rutas</button></div>
        </div></form>
    </div>
    <div class="col-lg-5"><div class="content-card overflow-hidden"><video class="w-100 d-block" controls preload="none" poster="{{ asset('images/inicio.png') }}" playsinline aria-label="Video de Terminales Nicaragua"><source src="{{ asset('images/video.mp4') }}" type="video/mp4"></video></div></div>
</div></div></section>
<section class="container py-5"><div class="row g-4">
    <div class="col-md-4"><a href="{{ route('home') }}#buscar-ruta" class="process-card content-card p-4 h-100"><p class="eyebrow">01</p><h3>Elige tu ciudad</h3><p class="mb-3 text-muted">Indica el municipio desde el que iniciarás tu viaje.</p><span>Buscar una ruta <span aria-hidden="true">→</span></span></a></div>
    <div class="col-md-4"><a href="{{ route('departamentos.listar') }}" class="process-card content-card p-4 h-100"><p class="eyebrow">02</p><h3>Encuentra la terminal</h3><p class="mb-3 text-muted">Explora destinos, municipios y las terminales disponibles.</p><span>Explorar destinos <span aria-hidden="true">→</span></span></a></div>
    <div class="col-md-4"><a href="{{ route('departamentos.listar') }}" class="process-card content-card p-4 h-100"><p class="eyebrow">03</p><h3>Consulta horarios</h3><p class="mb-3 text-muted">Selecciona un destino y una terminal para ver sus salidas registradas.</p><span>Ver horarios <span aria-hidden="true">→</span></span></a></div>
</div></section>
<section class="container pb-5"><div class="content-card p-4 p-lg-5"><div class="row align-items-center g-4">
    <div class="col-lg-5"><p class="eyebrow">Ayúdanos a crecer</p><h2 class="h3">¿Conoces una terminal que aún no aparece?</h2><p class="text-muted mb-0">Comparte su nombre, ubicación o una foto si la tienes. El equipo administrador revisará la sugerencia antes de agregarla al sitio.</p></div>
    <div class="col-lg-7"><form action="{{ route('sugerencias-terminales.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">@csrf
        <div class="col-md-6"><label for="nombre_terminal" class="form-label">Nombre de la terminal</label><input id="nombre_terminal" name="nombre_terminal" class="form-control" value="{{ old('nombre_terminal') }}" required>@error('nombre_terminal')<small class="text-danger">{{ $message }}</small>@enderror</div>
        <div class="col-md-6"><label for="ubicacion" class="form-label">Municipio o ubicación</label><input id="ubicacion" name="ubicacion" class="form-control" value="{{ old('ubicacion') }}" placeholder="Ej. Matagalpa">@error('ubicacion')<small class="text-danger">{{ $message }}</small>@enderror</div>
        <div class="col-12"><label for="foto" class="form-label">Foto de la terminal <span class="text-muted">(opcional)</span></label><input id="foto" name="foto" type="file" class="form-control" accept="image/jpeg,image/png,image/webp">@error('foto')<small class="text-danger">{{ $message }}</small>@enderror</div>
        <div class="col-12"><button type="submit" class="btn btn-primary">Enviar sugerencia para revisión</button></div>
    </form></div>
</div></div></section>
@endsection
@section('scripts')<script src="{{ asset('js/municipio-autocomplete.js') }}" defer></script>@endsection
