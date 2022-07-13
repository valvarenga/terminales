@extends('layouts.plantilla')
@section('title','Terminales')
@section('content')

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

 
  
@endsection
