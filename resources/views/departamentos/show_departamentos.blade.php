@extends('layouts.plantilla')

@section('title', 'Departamentos')

@section('content')
<div class="container py-4">
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    <table class="table table-bordered align-middle">
        <thead><tr><th>Nombre</th><th>Imagen</th><th>Acciones</th></tr></thead>
        <tbody>
        @forelse($departamentos as $departamento)
            <tr>
                <td>{{ $departamento->nombre }}</td>
                <td><img src="{{ asset($departamento->url) }}" alt="{{ $departamento->nombre }}" width="150"></td>
                <td><a class="btn btn-success" href="{{ route('departamento.ver', $departamento) }}">Editar</a></td>
            </tr>
        @empty
            <tr><td colspan="3">No hay departamentos registrados.</td></tr>
        @endforelse
        </tbody>
    </table>
    <a href="{{ route('ruta.index') }}" class="btn btn-outline-primary">Regresar</a>
</div>
@endsection
