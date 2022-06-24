@extends('layouts.plantilla')

@section('title', 'Ver terminales')

@section('content')

<ul>
    @foreach($terminales as $terminal)
    <li id="{{$terminal->id}}"><a href="#">{{$terminal->nombre}}</a></li>
    @endforeach
</ul>
@endsection