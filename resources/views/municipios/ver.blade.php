@extends('layouts.plantilla')
@section('title','Ver municipios')

@section('content')

<a href="{{route('municipio.edit', $municipio)}}"><button type="button" class="btn btn-outline-primary">Editar</button></a>
@error('municipio') <div class="alert alert-danger">{{ $message }}</div> @enderror
<form action="{{ route('municipio.destroy', $municipio) }}" method="POST">
    @csrf 
    @method('delete')
    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('¿Eliminar este municipio?')">Eliminar</button>
    <ul class="list-group">
        <li class="list-group-item active" aria-current="true">{{$municipio->nombre}}</li>
        <li class="list-group-item">{{$departamento->nombre}}</li>
    </ul>
</form>
<a href="{{route('municipio.show')}}"><button type="submit" class="btn btn-outline-primary">Regresar</button></a>
@endsection
