@extends('layouts.plantilla')
@section('title', 'Nueva terminal')
@section('content')

<form action="{{route('terminal')}}" class="" method="POST">
    @csrf
 

    <div>
        <label for="departamento" class="sr-only">Departamento</label>
          <br>
        <select id="departamento" name="departamento"  class="focus:ring-indigo-500 focus:border-indigo-500 h-full py-0 pl-2 pr-7 border-transparent bg-transparent text-gray-500 sm:text-sm rounded-md">
          <option value="">Seleccione un departamento</option>
          @foreach ($departamentos as $departamento)
          <option value="{{$departamento->id}}">{{$departamento->nombre}}</option>
          @endforeach
        </select>
        @error('departamento')
        <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
  </div>


  <div>
    <label for="municipio" class="sr-only">Municipio</label>
      <br>
    <select id="municipio" name="municipio" class="focus:ring-indigo-500 focus:border-indigo-500 h-full py-0 pl-2 pr-7 border-transparent bg-transparent text-gray-500 sm:text-sm rounded-md">
      <option value="">Seleccione un municipio</option>
      @foreach ($municipios as $municipio)
      <option value="{{$municipio->id}}">{{$municipio->nombre}}</option>
      @endforeach
    </select>
    @error('municipio')
    <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
</div>

    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre de la terminal <br>
        <input type="text" name="nombre" value="{{old('nombre')}}" id="nombre" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="Nombre aquí">
        </label>
        <input type="hidden" name="slug" value="slug">
        @error('nombre')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror
      </div>
    <div class="form-group">
        <label for="hora_abierta" class="block text-sm font-medium text-gray-700">Hora de apertura: 
          <br>
        <input type="time" value="{{old('hora_apertura')}}" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" name="hora_apertura" id="hora_apertura">
        </label>
        @error('hora_apertura')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror
      </div>
    <div class="form-group">
        <label for="hora_cerrada">Hora de cierre: 
          <br>
        <input type="time" value="{{old('hora_cierre')}}" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" name="hora_cierre" id="hora_cierre">
        </label>
        @error('hora_cierre')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror
      </div>
<br>
<button type="submit" class="btn btn-primary">Crear</button>
</form>
@endsection

@section('scripts')
<script>
//var id_departamento = document.getElementById('departamento');
var id_municipio = document.getElementById('municipio');
//var ruta = "{{route('municipio.ajax')}}";
$(document).ready(function(){
  $('#departamento').on('change', function(){
    var id_departamento = $(this).val();
    if(id_departamento){
      $.ajax({
        type:'GET',
        url:'{{route('municipio.ajax')}}'+'/'+id_departamento,
        dataType:'JSON',
        success:function(data){
          //console.log(data);
          id_municipio.innerHTML = `<option value="">Seleccionar Municipio...</option>`
          for(i of data){
            //console.log(i);
            id_municipio.innerHTML += `<option value="${i.id}">${i.nombre}</option>`
          }
        }
      });
    }else{
     alert ('No selecciono nada');
      // $('#municipio').empty();
    }
  });
});
</script>
@endsection