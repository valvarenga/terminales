@extends('layouts.plantilla')
@section('title','Terminales')

@section('content')
@if(isset($autobuses))
    
        <div class="card">
            <div class="card-header">

                    <h3 >{{$terminal->nombre}}</h3>
            </div>
        </div>
  

    @foreach($autobuses as $autobus)
        <div class="card">
            <div class="card-header">
                <table class="table table-dark table-striped-columns">
                    <thead>
                        <tr>
                            <th>Nombre del bus</th>
                            <th>Placa</th>
                            <th>Sale a:</th>
                            <th>Hora de salida:</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{$autobus->nombre}}</td>
                            <td>{{$autobus->placa}}</td>
                            <td>{{$autobus->destino}}</td>
                            <td>{{$autobus->hora_salida}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    @endforeach
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