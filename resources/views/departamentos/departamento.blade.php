@extends('layouts.plantilla')

@section('title', 'Nuevo departamento')

@section('content')
<section class="page-header pb-4">
    <div class="container">
        <p class="eyebrow mb-2">Administración</p>
        <h1 class="mb-2">Crear departamento</h1>
        <p class="mb-0">Agrega un nuevo destino y una imagen que ayude a los viajeros a reconocerlo.</p>
    </div>
</section>

<section class="container pb-5">
    <div class="form-shell">
        <form action="{{ route('departamento.store') }}" method="POST" enctype="multipart/form-data" class="form-section">
            @csrf
            <div class="d-flex align-items-start gap-3 mb-4">
                <span class="summary-icon"><i class="bi bi-map" aria-hidden="true"></i></span>
                <div><h2 class="h4 mb-1">Datos del departamento</h2><p class="text-muted mb-0">Los campos marcados son necesarios para crear el registro.</p></div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger"><strong>Revisa la información.</strong><ul class="mb-0 mt-2">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
            @endif

            <div class="mb-4">
                <label for="nombre" class="form-label">Nombre del departamento</label>
                <input type="text" name="nombre" id="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" placeholder="Ej. Estelí" required autofocus>
                @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label for="file_D" class="form-label">Imagen del departamento</label>
                <input type="file" class="form-control @error('file_D') is-invalid @enderror" id="file_D" name="file_D" accept="image/*">
                <div class="form-text">Usa una fotografía horizontal, nítida y representativa.</div>
                @error('file_D')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="d-flex flex-wrap gap-2 pt-2">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check2-circle me-1" aria-hidden="true"></i> Crear departamento</button>
                <a href="{{ route('departamentos.show') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</section>
@endsection
