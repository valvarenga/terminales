@extends('layouts.plantilla')
@section('title', 'Editar terminal')
@section('content')
<div class="container py-4" style="max-width:800px">
<h1>Editar terminal</h1>
@if ($errors->any())<div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<form action="{{ route('terminal.update', $terminal) }}" method="POST" enctype="multipart/form-data" class="card card-body">
@csrf @method('PUT')
<div class="mb-3"><label for="departamento">Departamento</label><select class="form-select" id="departamento" name="departamento" required>@foreach ($todos_departamentos as $opcionDepartamento)<option value="{{ $opcionDepartamento->id }}" @selected(old('departamento', $terminal->departamento_id) == $opcionDepartamento->id)>{{ $opcionDepartamento->nombre }}</option>@endforeach</select></div>
<div class="mb-3"><label for="municipio">Municipio</label><select class="form-select" id="municipio" name="municipio" required><option value="">Seleccione un municipio</option>@foreach ($todos_municipios as $opcionMunicipio) @if ($opcionMunicipio->departamento_id == old('departamento', $terminal->departamento_id))<option value="{{ $opcionMunicipio->id }}" @selected(old('municipio', $terminal->municipio_id) == $opcionMunicipio->id)>{{ $opcionMunicipio->nombre }}</option>@endif @endforeach</select></div>
<div class="mb-3"><label for="nombre">Nombre</label><input class="form-control" id="nombre" name="nombre" maxlength="255" value="{{ old('nombre', $terminal->nombre) }}" required></div>
<div class="mb-3"><label for="hora_apertura">Hora de apertura</label><input class="form-control" type="time" id="hora_apertura" name="hora_apertura" value="{{ old('hora_apertura', substr($terminal->hora_apertura, 0, 5)) }}" required></div>
<div class="mb-3"><label for="hora_cierre">Hora de cierre</label><input class="form-control" type="time" id="hora_cierre" name="hora_cierre" value="{{ old('hora_cierre', substr($terminal->hora_cierre, 0, 5)) }}" required></div>
<div class="mb-3"><label for="file_T">Fotografía</label>
@if ($terminal->url_T)<div><img src="{{ asset($terminal->url_T) }}" alt="Fotografía actual" class="img-fluid rounded mb-2" style="max-height:220px"></div>
<div class="form-check mb-2"><input type="checkbox" class="form-check-input" name="remove_photo" id="remove_photo" value="1" @checked(old('remove_photo'))><label for="remove_photo" class="form-check-label">Quitar fotografía actual</label></div>@endif
<input type="file" name="file_T" id="file_T" accept="image/*" class="form-control"><div class="form-text">Máximo 2 MB. Si elige una nueva fotografía, esta reemplazará la actual.</div></div>
<div><button class="btn btn-primary" type="submit">Guardar cambios</button> <a href="{{ route('show_terminal') }}" class="btn btn-outline-secondary">Cancelar</a></div>
</form>
</div>
@endsection
@section('scripts')
<script>
(() => {
 const departamentos = document.getElementById('departamento');
 const municipios = document.getElementById('municipio');
 const opciones = @json($todos_municipios);
 departamentos.addEventListener('change', () => {
   municipios.replaceChildren(new Option('Seleccione un municipio', ''));
   opciones.filter(item => String(item.departamento_id) === departamentos.value).forEach(item => municipios.add(new Option(item.nombre, item.id)));
 });
})();
</script>
@endsection
