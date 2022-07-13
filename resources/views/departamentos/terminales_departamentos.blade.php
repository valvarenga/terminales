@extends('layouts.plantilla')
@section('title','Terminales')
@section('css')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css"> 
  <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css"> 
@endsection
@section('content')
@if(isset($autobuses))
    
        <div class="card">
            <div class="card-header">

                    <h3 >{{$terminal->nombre}}</h3>
            </div>
        </div>
  

    
        <div class="card">
            <div class="card-body">
                <table class="table table-dark table-striped-columns" id="buses">
                    <thead>
                        <tr>
                            <th>Nombre del bus</th>
                            <th>Placa</th>
                            <th>Sale a:</th>
                            <th>Hora de salida:</th>
                        </tr>
                    </thead>
                    @foreach($autobuses as $autobus)
                    <tbody>
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
    @foreach($terminales as $terminal)
    <div class="card">
        <div class="card-header">
            <a href="{{route('departamento.autobuses',$terminal)}}">
                <h3 {{$terminal->id}}>{{$terminal->nombre}}</h3>
            </a>
        </div>
    </div>
    @endforeach
        
 @endif
@endsection
