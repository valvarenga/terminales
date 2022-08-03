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
    </ul>
  </div>

  <table class="table">
    <thead>
      <tr>
        <th>Nombre del bus</th>
        <th>Placa</th>
        <th>Sale a:</th>
        <th>Hora de salida:</th>
    </tr>
    </thead>
    <tbody>
      @foreach ($busc as $buscar)
      <tr>
        <td>{{$buscar->nombre}}</td>
        <td>{{$buscar->placa}}</td>
        <td>{{$buscar->destino}}</td>
        <td>{{$buscar->hora_salida}}</td>
    </tr>
      @endforeach
    </tbody>
  </table>
  

  </div>
</div>
@endsection