@extends('layouts.plantilla')
@section('title', 'Nueva terminal')
@section('content')

<div class="container">
  <div class="row">
<div class="card">
<div class="card-header text-center">
   <h3> Crear Nueva Terminal</h3>   
</div>
<div class="card-body">
  <form action="{{route('terminal')}}" method="POST" enctype="multipart/form-data">
    @csrf
  <div>
        <label for="departamento" class="sr-only">Departamento</label>
        <select id="departamento" name="departamento"  class="focus:ring-indigo-500 focus:border-indigo-500 h-full py-0 pl-2 pr-7 border-transparent bg-transparent text-gray-500 sm:text-sm rounded-md">
          <option value="">Seleccione un departamento</option>
          @foreach ($departamentos as $departamento)
          <option value="{{$departamento->id}}">{{$departamento->nombre}}</option>
          @endforeach
        </select>
        @error('departamento')
        <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
  </div>
  <br/>
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
    <span class="text-danger text-sm">{{ $message }}</span>
    @enderror
</div>
<br/>
<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <label for="nombre" class="sr-only">Nombre de la terminal <br>
        <input type="text" name="nombre" value="{{old('nombre')}}" id="nombre" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="Nombre aquí">
        </label>
        <input type="hidden" name="slug" value="slug">
        @error('nombre')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror
 </div>
 <br/>
 <div class="form-group">
  <label for="nombre" class="sr-only">Foto de la Terminal</label>
      <div class="input-group-btn">
         <span class="fileUpload btn btn-success">
             <input type="file" class="upload up" id="up_T" name="file_T" onchange="readURL(this);" accept="image/*" />
             </span><!-- btn-orange -->
        </div><!-- btn -->
        @error('file_T')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror
</div> <br/><!-- form-group -->
<div class="form-group">
        <label for="hora_abierta" class="sr-only">Hora de apertura: 
          <br>
        <input type="time" value="{{old('hora_apertura')}}" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" name="hora_apertura" id="hora_apertura">
        </label>
        @error('hora_apertura')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror
 </div>
 <br/>
  <div class="form-group">
        <label for="hora_cerrada">Hora de cierre: 
          <br>
        <input type="time" value="{{old('hora_cierre')}}" class="sr-only" name="hora_cierre" id="hora_cierre">
        </label>
        @error('hora_cierre')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror
  </div>
<br/>
<button type="submit" class="btn btn-primary">Crear</button>
</form>
</div><!-- fin del card-body -->
</div> <!-- fin del card -->
</div> <!-- fin del row -->
</div> <!-- fin del container -->
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