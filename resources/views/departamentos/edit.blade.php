@extends('layouts.plantilla')
@section('title', 'Editar departamento')
@section('content')
<div class="container py-4" style="max-width:800px">
<h1>Editar departamento</h1>
@if ($errors->any())<div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<form action="{{ route('departamento.update', $departamento) }}" method="POST" enctype="multipart/form-data" class="card card-body">
@csrf @method('PUT')
<div class="mb-3"><label for="nombre">Nombre</label><input class="form-control" id="nombre" name="nombre" maxlength="255" value="{{ old('nombre', $departamento->nombre) }}" required></div>
<div class="mb-3"><label for="file_D">Fotografía</label>
@if ($departamento->url)<div><img src="{{ asset($departamento->url) }}" alt="Fotografía actual" class="img-fluid rounded mb-2" style="max-height:220px"></div>
<div class="form-check mb-2"><input type="checkbox" class="form-check-input" name="remove_photo" id="remove_photo" value="1" @checked(old('remove_photo'))><label for="remove_photo" class="form-check-label">Quitar fotografía actual</label></div>@endif
<input type="file" name="file_D" id="file_D" accept="image/*" class="form-control"><div class="form-text">Máximo 2 MB. Si elige una nueva fotografía, esta reemplazará la actual.</div></div>
<div><button class="btn btn-primary" type="submit">Guardar cambios</button> <a href="{{ route('departamentos.show') }}" class="btn btn-outline-secondary">Cancelar</a></div>
</form>
<form action="{{ route('departamento.destroy', $departamento) }}" method="POST" class="mt-3">@csrf @method('DELETE')<button class="btn btn-outline-danger" onclick="return confirm('¿Eliminar este departamento?')">Eliminar departamento</button></form>
</div>
@endsection
