@extends('layouts.plantilla')

@section('title', 'Nuevo departamento')

@section('content')
<div class="container">
    <div class="row">
<div class="card">
 <div class="card-header text-center">
     <h3> Crear Departamento</h3>   
</div>
<div class="card-body">
<form action="{{route('departamento.store')}}" method="POST" enctype="multipart/form-data">
    @csrf
 <div class="col-sm-offset-2 col-sm-8">
     <div class="form-group">
                <label for="nombre" class="h4 ">Nombre del Departamento</label>
                <input type="text" name="nombre" id="nombre" class="form-control" value="{{old('nombre')}}">
     </div><br/>
   <div class="form-group">
     <div class="input-group-btn">
        <span class="fileUpload btn btn-success">
            <input type="file" class="upload up" id="up" name="file_D" onchange="readURL(this);" accept="image/*" />
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
    <button type="submit" class="btn btn-primary">Crear</button>
</div>
</form>
</div><!-- fin del card-body -->
</div><!-- fin del card -->
</div><!-- fin del row -->
</div><!-- fin del container -->
@endsection