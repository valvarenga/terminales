@extends('layouts.plantilla')

@section('title', 'Editar departamento')

@section('content')
<div class="container py-4">
    <h1>{{ $departamento->nombre }}</h1>
    @error('departamento') <div class="alert alert-danger">{{ $message }}</div> @enderror
    <img src="{{ asset($departamento->url) }}" alt="{{ $departamento->nombre }}" width="400" class="img-fluid mb-3">

    <form action="{{ route('departamento.update', $departamento) }}" method="POST" class="mb-3">
        @csrf
        @method('PUT')
        <label for="nombre" class="form-label">Nombre del departamento</label>
        <input id="nombre" name="nombre" class="form-control" value="{{ old('nombre', $departamento->nombre) }}" required>
        @error('nombre') <div class="text-danger">{{ $message }}</div> @enderror
        <button class="btn btn-primary mt-3">Guardar cambios</button>
    </form>

    <form action="{{ route('departamento.destroy', $departamento) }}" method="POST">
        @csrf
        @method('DELETE')
        <button class="btn btn-danger" onclick="return confirm('¿Eliminar este departamento?')">Eliminar</button>
        <a href="{{ route('departamentos.show') }}" class="btn btn-outline-secondary">Regresar</a>
    </form>
</div>
@endsection
