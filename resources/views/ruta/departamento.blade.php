@extends('layouts.plantilla')

@section('title', 'Nuevo departamento')

@section('content')

<form action="{{route('departamento.store')}}" method="POST">
    @csrf
    <div class="form-group">
        <label for="nombre">Nombre del departamento</label>
        <input type="text" name="nombre" id="nombre" class="form-control">
    </div>
    <button type="submit" class="btn btn-primary">Crear</button>
</form>

@endsection