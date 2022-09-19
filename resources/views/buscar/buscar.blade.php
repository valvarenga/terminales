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
  <div class="row justify-content-center">
    @foreach($terminales as $terminal)
    <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
        <a href="{{route('departamento.autobuses',$terminal)}}" class="text-dark fw-bolder">
            <div class="bg-white rounded shadow-sm"><img src="{{ asset($terminal->url_T) }}" alt="" class="img-fluid card-img-top">
              <div class="mx-auto p-4 text-center" style="width: 300px;">
                     <h4>{{$terminal->nombre}} </h4>
                   
               </div>
            </div>
                </a>
    </div>

 @endforeach
</div>        

<!--
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
      
      //foreach ($busc as $buscar)
      <tr>
        <td>//$buscar->nombre}}</td>
        <td>//$buscar->placa}}</td>
        <td>//$buscar->destino}}</td>
        <td>//$buscar->hora_salida}}</td>
    </tr>
      //endforeach 
    </tbody>
  </table>
  -->
  

  </div>
</div>
@endsection