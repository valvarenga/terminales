@extends('layouts.plantilla')
@section('title','Ver municipios')

@section('content')
    @foreach ($municipios as $municipio)
    <ul class="list-group">
       <a href="{{route('municipio.ver', $municipio)}}"><li class="list-group-item"{{$municipio->id}}>{{$municipio->nombre}}</li></a>
    </ul>
    @endforeach
    <a href="{{route('ruta.index')}}"><button type="submit" class="btn btn-outline-primary">Regresar</button></a>
@endsection