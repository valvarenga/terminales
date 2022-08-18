@extends('layouts.plantilla')
@section('title', 'Nuevo municipio')
@section('content')

<div class="container">
    <div class="row">
<div class="card">
 <div class="card-header text-center">
     <h3> Crear Municipio</h3>   
</div>
<div class="card-body">

    <form action="{{route('municipio.store')}}" method="POST" enctype="multipart/form-data">
        @csrf
    <div class="form-group">
            <label for="departamento_id" class="h4 ">Departamento</label>
            <select name="departamento_id" id="departamento_id" class="form-control" aria-placeholder="Seleccione un departamento">
                @foreach($departamentos as $departamento)
                <option value="{{$departamento->id}}" >{{$departamento->nombre}}</option>
                @endforeach
            </select>
     </div>
        @error('departamento_id')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    <br/>
     <div class="form-group">
            <label for="nombre" class="h4 ">Nombre del municipio</label>
            <input type="text" name="nombre" id="nombre" class="form-control" value="{{old('nombre')}}">
     </div>
     <br/>
    <div class="form-group">
        <label for="nombre" class="h4 ">Foto del municipio</label>
            <div class="input-group-btn">
               <span class="fileUpload btn btn-success">
                   <input type="file" class="upload up" id="up_M" name="file_M" onchange="readURL(this);" accept="image/*" />
                   </span><!-- btn-orange -->
              </div><!-- btn -->
    </div> <br/><!-- form-group -->
      @if ($errors->any())
        <div class="alert alert-danger">
                <ul>
                 @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                @endforeach
                 </ul>
              </div>
          @endif
        <br/>
        <button type="submit" class="btn btn-primary">Crear</button>
    </form>

</div><!-- fin del card-body -->
</div> <!-- fin del card -->
</div> <!-- fin del row -->
</div> <!-- fin del container -->

@endsection