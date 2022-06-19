@extends('layouts.plantilla')
@section('title', 'Nuevo Autobus')

@section('content')

<form action="{{route('autobus')}}" method="POST">
    @csrf
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
    <div>
        <label for="nombre">Nombre del autobus
            <br>
            <input type="text" name="nombre" id="nombre" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="Nombre aquí">
        </label>
    </div>
    <div>
        <label for="placa">Placa del autobus
            <br>
            <input type="text" name="placa" id="placa" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="Placa aquí">
        </label>
    </div>
    <div>
        <label for="origen"> Sale de: <br>
            <input type="text" name="origen" id="origen" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="Ingrese el origen del bus">
        </label>
    </div>
    <div>
        <label for="hora_llegada">Hora de salida
            <br>
            <input type="time" name="hora_salida" id="hora_salida" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="Hora de llegada aquí">
        </label>
    </div>
    <div>
        <label for="destino">Llega a:
            <br>
            <input type="text" name="destino" id="destino" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="Ingrese el destino">
        </label>
    </div>
    <div>
        <label for="hora_llegada">Hora de llegada
            <br>
            <input type="time" name="hora_llegada" id="hora_llegada" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="Hora de llegada aquí">
        </label>
    </div>
    <br>
    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full">
        Guardar
</form>
@endsection