@extends('layouts.plantilla')

@section('title', 'Ver terminales')

@section('content')

<ul>
    @foreach($terminales as $terminal)
    <li  id="{{$terminal->id}}">
        <a href="{{route('ver.terminal', $terminal)}}">{{$terminal->nombre}}</a>

    @endforeach
    <br>
    <div class="d-grid gap-2 col-6 mx-auto">
        <a href="{{route('ruta.index')}}"><button class="btn btn-primary" type="button">Regresar</button></a>
      </div>
    
</ul>
@endsection