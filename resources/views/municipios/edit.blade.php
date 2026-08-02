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
        <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre', $municipio->nombre) }}">
    </div>
    @error('nombre')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror
    <div class="row"><div class="col-md-6 form-group"><label for="latitud">Latitud aproximada</label><input type="number" step="0.0000001" name="latitud" id="latitud" class="form-control" value="{{ old('latitud', $municipio->latitud) }}"></div><div class="col-md-6 form-group"><label for="longitud">Longitud aproximada</label><input type="number" step="0.0000001" name="longitud" id="longitud" class="form-control" value="{{ old('longitud', $municipio->longitud) }}"></div></div>
    @error('latitud')<div class="alert alert-danger">{{ $message }}</div>@enderror @error('longitud')<div class="alert alert-danger">{{ $message }}</div>@enderror
    <button type="submit" class="btn btn-primary">Actualizar</button>
</form>
@endsection
