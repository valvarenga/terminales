@extends('layouts.plantilla')
@section('title','Terminales')

@section('content')
@if(isset($autobuses))
    
        <div class="card">
            <div class="card-header">
                <a href="{{route('departamento.autobuses',$terminal)}}">
                    <h3 >{{$terminal->nombre}}</h3>
                </a>
            </div>
        </div>
  

    @foreach($autobuses as $autobus)
        <div class="card">
            <div class="card-header">
                <a href="#">
                    <h3 {{$autobus->id}}>{{$autobus->nombre}}</h3>
                </a>
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