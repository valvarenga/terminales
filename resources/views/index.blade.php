@extends('layouts.plantilla')

@section('title', 'Home')

@section('content')
<h1>Bienvenido a la página inicial</h1>

<form action="">
    <div>
        <label for="terminal">
            <br>
            <select name="terminal" id="terminal">
                <option value="">Seleccione una opción</option>
                @foreach ($terminales as $terminal)
                <option value="{{$terminal->id}}">{{$terminal->nombre}}</option>
                @endforeach
            </select>
        </label>
    </div>
</form>
<table>
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Placa</th>
            <th>Hora de salida</th>
            <th>Hora de llegada</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($autobuses as $autobus)
        <tr>
            <td>{{$autobus->nombre}}</td>
            <td>{{$autobus->placa}}</td>
            <td>{{$autobus->hora_salida}}</td>
            <td>{{$autobus->hora_llegada}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection