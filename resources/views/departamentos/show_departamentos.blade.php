@extends('layouts.plantilla')

@section('title', 'Departamentos')

@section('content')
<div class="container py-4">
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @include('partials.search-filter', ['targetId' => 'tabla-departamentos', 'placeholder' => 'Buscar departamento por nombre...'])
    <table class="table table-bordered align-middle" id="tabla-departamentos">
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
    <a href="{{ session('admin_role') ? route('admin.dashboard') : route('admin.login') }}" class="btn btn-warning btn-sm px-3">Home</a>

</div>
@endsection
