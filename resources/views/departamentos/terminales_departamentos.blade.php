@extends('layouts.plantilla')
@section('title','Terminales')

@section('content')
    @foreach($terminales as $terminal)
        <div class="card">
            <div class="card-header">
                <h3>{{$terminal->nombre}}</h3>
            </div>

        </div>
    @endforeach
@endsection