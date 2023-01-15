@extends('layouts.plantilla')
@section('title', 'Nuevo Autobus')
@section('content')

<div class="container">
    <div class="row">
<div class="card">
    <div class="card-header text-center">
        <h3 >Horarios</h3>
    </div>
</div>

<div class="card-body">
<form action="{{route('autobus')}}" method="POST">
    @csrf
<div class="form-group">
        <label for="terminal" class="h4">Terminal </label>
            <select name="terminal" id="terminal" class="form-select" aria-label="Default select example">
                <option value="">Seleccione una opción</option>
                @foreach ($terminales as $terminal)
                <option value="{{$terminal->id}}">{{$terminal->nombre}}</option>
                @endforeach
            </select>
        
    </div>
    @error('terminal')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror
     <br/> 

 <div class="form-group">
     <label for="nombre" class="h4 ">Nombre del Autobus</label>
        <input type="text" name="nombre" id="nombre" class="form-control" value="{{old('nombre')}}" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="Nombre aquí">
    </div>   
 @error('nombre')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror
   <br/>

<div class="form-group"> 
     <label for="placa" class="h4">Placa del autobus</label>
     <input type="text" name="placa" id="placa" class="form-control" value="{{old('placa')}}" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="Placa aquí">
</div>
    <br/>
 <div class="form-group"> 
    <label for="origen" class="h4"> Sale de:  </label>
     <input type="text" name="origen" id="origen"  class="form-control" value="{{old('origen')}}" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="Ingrese el origen del bus">
 </div>
    @error('origen')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror
   <br/>
<div class="form-group"> 
        <label for="hora_llegada" class="h4">Hora de salida</label>
            <input type="time" name="hora_salida" id="hora_salida"  class="form-control" value="{{old('hora_salida')}}" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="Hora de llegada aquí">
 </div>
    @error('hora_salida')
    <div class="alert alert-danger">{{ $message }}</div>    
    @enderror
<br/>
<div class="form-group"> 
    <label for="destino" class="h4">Llega a:</label>
    <input type="text" name="destino" id="destino"  class="form-control" value="{{old('destino')}}" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="Ingrese el destino">
 </div>
    @error('destino')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror
 <br/>
 <div class="form-group"> 
    <label for="hora_llegada" class="h4">Hora de llegada</label>
    <input type="time" name="hora_llegada" id="hora_llegada"  class="form-control" value="{{old('hora_llegada')}}" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="Hora de llegada aquí">
</div>
    @error('hora_llegada')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror
<br/>
<div class="form-group">
    <label for="categoria" class="h4">Categoria </label>
        <select name="categoria" id="categoria" class="form-select" aria-label="Default select example">
            <option value="Expreso" selected>Expreso</option>
            <option value="Ruteado">Ruteado</option>
        </select>
    
</div>
<br/> 
<button type="submit" class="btn btn-info btn-lg"><span class="glyphicon glyphicon-ok"></span>
 Guardar
</button>
</form>
</div>
    </div>
    </div>
@endsection