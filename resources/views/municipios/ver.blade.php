@extends('layouts.plantilla')
@section('title','Ver municipios')

@section('content')

<a href="{{route('municipio.edit', $municipio)}}"><button type="button" class="btn btn-outline-primary">Editar</button></a>
<form action="" method="POST">
    @csrf 
    @method('delete')
    <button type="button" class="btn btn-outline-danger">Eliminar</button>
    <ul class="list-group">
        <li class="list-group-item active" aria-current="true">{{$municipio->nombre}}</li>
        <li class="list-group-item">{{$departamento->nombre}}</li>
    </ul>
</form>
<a href="{{route('municipio.show')}}"><button type="submit" class="btn btn-outline-primary">Regresar</button></a>
@endsection