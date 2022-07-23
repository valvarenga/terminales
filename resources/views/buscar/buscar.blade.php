@extends('layouts.plantilla')

@section('title', 'Busqueda')

@section('content')
<div class="container-fluid">
  <div class="px-lg-5">

<div class="row py-5">
    <div class="col-lg-12 mx-auto text-center">
      <div class="text-warning p-5 shadow-sm rounded banner">
        <h1 class="display-4">Horarios de los Buses </h1>
      </div>
    </div>
  </div>
  <div>
    <ul>
      @foreach($terminales as $terminal)
      <li  id="{{$terminal->id}}">
          <a href="{{route('ver.terminal', $terminal)}}">{{$terminal->nombre}}</a>
  
      @endforeach
  </div>

  </div>
</div>
@endsection