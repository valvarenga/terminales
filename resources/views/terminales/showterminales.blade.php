@extends('layouts.plantilla')

@section('title', 'Ver terminales')

@section('content')

<ul>
    @foreach($terminales as $terminal)
    <li id="{{$terminal->id}}"><a href="{{route('terminal.edit', $terminal)}}">{{$terminal->nombre}}</a></li>
    @endforeach
    <br>
    
</ul>
@endsection