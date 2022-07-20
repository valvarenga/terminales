@extends('layouts.plantilla')

@section('tittle','Departamentos')

@section('content')
<ul>
    @foreach($departamentos as $departamento)
    <li  id="{{$departamento->id}}">
        <a href="{{route('departamento.ver', $departamento)}}">{{$departamento->nombre}}</a>

    @endforeach
    <br>
    <div class="d-grid gap-2 col-6 mx-auto">
        <a href="{{route('ruta.index')}}"><button class="btn btn-primary" type="button">Regresar</button></a>
      </div>
    
</ul>
@endsection