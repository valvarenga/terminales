@extends('layouts.plantilla')
@section('title', 'Editar anuncio')
@section('content')
<div class="container py-4">
    <a href="{{ route('admin.anuncios.index') }}" class="btn btn-outline-secondary mb-3">Volver a publicidad</a>
    <h1 class="h3">Editar anuncio</h1>
    <div class="content-card p-4 mt-3">
        @include('admin.partials.errors')
        <form method="POST" action="{{ route('admin.anuncios.update', $anuncio) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            @include('admin.partials.anuncio-form')
            <button type="submit" class="btn btn-primary mt-3">Guardar cambios</button>
        </form>
    </div>
</div>
@endsection
