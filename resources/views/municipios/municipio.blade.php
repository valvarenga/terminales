@extends('layouts.plantilla')
@section('title', 'Nuevo municipio')
@section('content')
<form action="{{route('municipio.store')}}" method="POST">
    @csrf

    <div class="form-group">
        <label for="departamento_id">Departamento</label>
        <select name="departamento_id" id="departamento_id" class="form-control" aria-placeholder="Seleccione un departamento">
            @foreach($departamentos as $departamento)
            <option value="{{$departamento->id}}" >{{$departamento->nombre}}</option>
            @endforeach
        </select>
    </div>
    @error('departamento_id')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="form-group">
        <label for="nombre">Nombre del municipio</label>
        <input type="text" name="nombre" id="nombre" class="form-control" value="{{old('nombre')}}">
    </div>
    @error('nombre')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror
    <button type="submit" class="btn btn-primary">Crear</button>

@endsection