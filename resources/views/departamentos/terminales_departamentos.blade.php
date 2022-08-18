@extends('layouts.plantilla')
@section('title','Terminales')
@section('css')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css"> 
  <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css"> 
@endsection
@section('content')
<div class="container-fluid">
    <div class="px-lg-5">
<br/>
@if(isset($autobuses))
    
        <div class="card">
            <div class="card-header">

                    <h3 >{{$terminal->nombre}}</h3>
            </div>
        </div>
  
        <div class="card">
            <div class="card-body">
                <table id="buses" class="table table-dark" style="width:100%" >
                    <thead>
                        <tr>
                            <th>Nombre del bus</th>
                            <th>Placa</th>
                            <th>Sale a:</th>
                            <th>Hora de salida:</th>
                        </tr>
                    </thead>
                   
                    <tbody>
                         @foreach($autobuses as $autobus)
                        <tr>
                            <td>{{$autobus->nombre}}</td>
                            <td>{{$autobus->placa}}</td>
                            <td>{{$autobus->destino}}</td>
                            <td>{{$autobus->hora_salida}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    
@else
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
 @endif
    </div>
</div>
@endsection
