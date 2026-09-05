@extends('layouts.plantilla')
@section('title', 'Editar municipio')
@section('content')
<div class="container py-4" style="max-width:800px">
<h1>Editar municipio</h1>
@if ($errors->any())<div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<form action="{{ route('municipio.update', $municipio) }}" method="POST" enctype="multipart/form-data" class="card card-body">
@csrf @method('PUT')
<div class="mb-3"><label for="departamento">Departamento</label><select class="form-select" id="departamento" name="departamento_id" required>@foreach ($todos_departamentos as $opcionDepartamento)<option value="{{ $opcionDepartamento->id }}" @selected(old('departamento_id', $municipio->departamento_id) == $opcionDepartamento->id)>{{ $opcionDepartamento->nombre }}</option>@endforeach</select></div>
<div class="mb-3"><label for="nombre">Nombre</label><input class="form-control" id="nombre" name="nombre" maxlength="255" value="{{ old('nombre', $municipio->nombre) }}" required></div>
<div class="mb-3"><label for="latitud">Latitud aproximada</label><input class="form-control" type="number" id="latitud" name="latitud" step="any" min="-90" max="90" value="{{ old('latitud', $municipio->latitud) }}"></div>
<div class="mb-3"><label for="longitud">Longitud aproximada</label><input class="form-control" type="number" id="longitud" name="longitud" step="any" min="-180" max="180" value="{{ old('longitud', $municipio->longitud) }}"></div>
<div class="mb-3"><label for="file_M">Fotografía</label>
@if ($municipio->url_M)<div><img src="{{ asset($municipio->url_M) }}" alt="Fotografía actual" class="img-fluid rounded mb-2" style="max-height:220px"></div>
<div class="form-check mb-2"><input type="checkbox" class="form-check-input" name="remove_photo" id="remove_photo" value="1" @checked(old('remove_photo'))><label for="remove_photo" class="form-check-label">Quitar fotografía actual</label></div>@endif
<input type="file" name="file_M" id="file_M" accept="image/*" class="form-control"><div class="form-text">Máximo 2 MB. Si elige una nueva fotografía, esta reemplazará la actual.</div></div>
<div><button class="btn btn-primary" type="submit">Guardar cambios</button> <a href="{{ route('municipio.show') }}" class="btn btn-outline-secondary">Cancelar</a></div>
</form>
</div>
@endsection
