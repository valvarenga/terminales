@extends('layouts.plantilla')
@section('title', 'Ver terminal')

@section('content')
<a href="{{route('terminal.edit', $terminales)}}"><button type="submit" class="btn btn-success"> Editar</button></a>
<br>
<form action="{{route('terminal.destroy', $terminales)}}" method="POST">
    @csrf
    @method('delete')
    <button type="submit" class="btn btn-danger"> Eliminar</button>
</form>

<ul class="list-group">
    
   <strong> <li class="list-group-item active" aria-current="true">{{$terminales->nombre}}</li></strong>
    <li class="list-group-item">{{$terminales->hora_apertura}}</li>
    <li class="list-group-item">{{$terminales->hora_cierre}}</li>
    <li class="list-group-item">{{$departamento->nombre}}</li>
    <li class="list-group-item">{{$municipio->nombre}}</li>
</ul>
<br>
<div class="d-grid gap-2 col-6 mx-auto">
    <a href="{{route('show_terminal')}}"><button class="btn btn-primary" type="button">Regresar</button></a>
  </div>
@endsection