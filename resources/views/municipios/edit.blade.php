@extends('layouts.plantilla')
@section('title','editar municipio')

@section('content')
<form action="{{route('municipio.update', $municipio)}}" method="POST">
    @csrf   
    @method('PUT')

        <div class="form-group">
        <label for="departamento_id">Departamento</label>
        <select name="departamento_id" id="departamento_id" class="form-control" aria-placeholder="Seleccione un departamento">
            <option value="{{$departamento->id}}" >{{$departamento->nombre}}</option>
            @foreach($todos_departamentos as $departamento)
            <option value="{{$departamento->id}}" >{{$departamento->nombre}}</option>
            @endforeach
        </select>
    </div>
    @error('departamento_id')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="form-group">
        <label for="nombre">Nombre del municipio</label>
        <input type="text" name="nombre" id="nombre" class="form-control" value="{{$municipio->nombre, old('nombre')}}">
    </div>
    @error('nombre')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror
    <button type="submit" class="btn btn-primary">Actualizar</button>
@endsection