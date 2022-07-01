@extends('layouts.plantilla')
@section('title', 'Editar terminal')
@section('content')

<form action="{{route('terminal')}}" class="" method="POST">
    @csrf


    <div>
        <label for="departamento" class="sr-only">Departamento</label>
          <br>
        <select id="departamento" name="departamento" class="focus:ring-indigo-500 focus:border-indigo-500 h-full py-0 pl-2 pr-7 border-transparent bg-transparent text-gray-500 sm:text-sm rounded-md">
          <option value="{{$terminal->departamento_id}}">{{$departamentos->nombre}}</option>
          
          @foreach ($departamentos as $departamento)
          <option value="{{$departamento->id}}">{{$departamento->nombre}}</option>
          @endforeach
        </select>
  </div>


  <div>
    <label for="municipio" class="sr-only">Municipio</label>
      <br>
    <select id="municipio" name="municipio" class="focus:ring-indigo-500 focus:border-indigo-500 h-full py-0 pl-2 pr-7 border-transparent bg-transparent text-gray-500 sm:text-sm rounded-md">
      <option value="{{$terminal->municipio_id}}">{{$terminal->municipio_id}}</option>
      @foreach ($municipios as $municipio)
      <option value="{{$municipio->id}}">{{$municipio->nombre}}</option>
      @endforeach
    </select>
</div>

    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre de la terminal <br>
        <input type="text" name="nombre" id="nombre" value="{{$terminal->nombre}}" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md">
        </label>
      </div>
    <div class="form-group">
        <label for="hora_abierta" class="block text-sm font-medium text-gray-700">Hora de apertura: 
          <br>
        <input type="time" value="{{$terminal->hora_apertura}}" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" name="hora_apertura" id="hora_apertura">
        </label>
      </div>
    <div class="form-group">
        <label for="hora_cerrada">Hora de cierre: 
          <br>
        <input type="time" value="{{$terminal->hora_cierre}}" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" name="hora_cierre" id="hora_cierre">
        </label>
      </div>
<br>
<button type="submit" class="btn btn-primary">Editar</button>
</form>
@endsection