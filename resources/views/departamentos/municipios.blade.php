@extends('layouts.plantilla')
@section('title','lista de municipios')

@section('content')
@if ($municipios==[''])
    <h1>No hay datos que mostrar</h1>    
@else

<ul>
    @foreach ($municipios as $municipio)
    <a href="{{route('departamento.terminales', [$departamento, $municipio->slug])}}">
        <li {{$municipio->id}}>{{$municipio->nombre}}</li>
    </a>
    @endforeach
</ul>

@endif
@endsection