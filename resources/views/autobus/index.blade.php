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
    @error('terminal')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div>
        <label for="nombre">Nombre del autobus
            <br>
            <input type="text" name="nombre" id="nombre" value="{{old('nombre')}}" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="Nombre aquí">
        </label>
    </div>
    @error('nombre')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div>
        <label for="placa">Placa del autobus
            <br>
            <input type="text" name="placa" id="placa" value="{{old('placa')}}" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="Placa aquí">
        </label>
    </div>
    

    <div>
        <label for="origen"> Sale de: <br>
            <input type="text" name="origen" id="origen" value="{{old('origen')}}" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="Ingrese el origen del bus">
        </label>
    </div>
    @error('origen')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div>
        <label for="hora_llegada">Hora de salida
            <br>
            <input type="time" name="hora_salida" id="hora_salida" value="{{old('hora_salida')}}" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="Hora de llegada aquí">
        </label>
    </div>
    @error('hora_salida')
    <div class="alert alert-danger">{{ $message }}</div>    
    @enderror

    <div>
        <label for="destino">Llega a:
            <br>
            <input type="text" name="destino" id="destino" value="{{old('destino')}}" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="Ingrese el destino">
        </label>
    </div>
    @error('destino')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div>
        <label for="hora_llegada">Hora de llegada
            <br>
            <input type="time" name="hora_llegada" id="hora_llegada" value="{{old('hora_llegada')}}" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="Hora de llegada aquí">
        </label>
    </div>
    @error('hora_llegada')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <br>
    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full">
        Guardar
</form>
@endsection