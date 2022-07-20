@extends('layouts.plantilla')

@section('title', 'Ruta')

@section('content')
<h1>Bienvenido a la página de rutas</h1>

<nav>
    <ul class="menu">
        <li><a href="{{route('newdepartamento')}}"><span class="icofont-ui-add">Crear Departamento</span></a></li>
        <li><a href="{{route('newmunicipio')}}">Crear Municipio</a></li>
        <li><a href="{{route('newterminal')}}">Crear Terminal</a></li>
        <li><a href="{{route('newbus')}}">Crear Autobus</a></li>
    </ul>
</nav>

<nav class="menu">
    <ul>
        <li><a href="{{route('show_terminal')}}">Listar terminales</a></li>
        <li><a href="{{route('municipio.show')}}">Listar municipios</a></li>
        <li><a href="{{route('departamentos.show')}}">Listar departamentos</a></li>
        <li><a href="#">Listar autobuses</a></li>
    </ul>
</nav>
@endsection