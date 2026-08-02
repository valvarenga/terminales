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
                    <p class="text-muted">Crear o editar departamentos.</p>
                    <a href="{{ route('newdepartamento') }}" class="btn btn-primary">Ir a departamentos</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <h2 class="h5">Municipios</h2>
                    <p class="text-muted">Gestionar municipios y sus datos.</p>
                    <a href="{{ route('newmunicipio') }}" class="btn btn-primary">Ir a municipios</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <h2 class="h5">Terminales y autobuses</h2>
                    <p class="text-muted">Administrar terminales, horarios y servicios.</p>
                    <a href="{{ route('ruta.index') }}" class="btn btn-primary">Ir a terminales</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
