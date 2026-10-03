@extends('layouts.plantilla')

@section('title', 'Nueva terminal')

@section('content')
<section class="page-header pb-4">
    <div class="container">
        <p class="eyebrow mb-2">Administración</p>
        <h1 class="mb-2">Crear terminal</h1>
        <p class="mb-0">Registra su ubicación, horario de atención e información principal.</p>
    </div>
</section>

<section class="container pb-5">
    <div class="form-shell">
        <form action="{{ route('terminal') }}" method="POST" enctype="multipart/form-data" class="form-section">
            @csrf
            <div class="d-flex align-items-start gap-3 mb-4">
                <span class="summary-icon"><i class="bi bi-building" aria-hidden="true"></i></span>
                <div><h2 class="h4 mb-1">Datos de la terminal</h2><p class="text-muted mb-0">Selecciona primero el departamento para cargar sus municipios.</p></div>
            </div>

            @if ($errors->any())<div class="alert alert-danger">Revisa los campos marcados e inténtalo de nuevo.</div>@endif

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="departamento" class="form-label">Departamento</label>
                    <select id="departamento" name="departamento" data-municipios-url="{{ url('ajax') }}" data-selected-municipio="{{ old('municipio') }}" class="form-select @error('departamento') is-invalid @enderror" required>
                        <option value="">Seleccione un departamento</option>
                        @foreach ($departamentos as $departamento)<option value="{{ $departamento->id }}" @selected(old('departamento') == $departamento->id)>{{ $departamento->nombre }}</option>@endforeach
                    </select>
                    @error('departamento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="municipio" class="form-label">Municipio</label>
                    <select id="municipio" name="municipio" class="form-select @error('municipio') is-invalid @enderror" disabled required><option value="">Seleccione el municipio</option></select>
                    @error('municipio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label for="nombre" class="form-label">Nombre de la terminal</label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}" id="nombre" class="form-control @error('nombre') is-invalid @enderror" placeholder="Ej. Terminal Norte" required>
                    <input type="hidden" name="slug" value="slug">
                    @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label for="file_T" class="form-label">Foto de la terminal <span class="text-muted fw-normal">(opcional)</span></label>
                    <input type="file" class="form-control @error('file_T') is-invalid @enderror" id="file_T" name="file_T" accept="image/*">
                    @error('file_T')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6"><label for="hora_apertura" class="form-label">Hora de apertura</label><input type="time" value="{{ old('hora_apertura') }}" class="form-control @error('hora_apertura') is-invalid @enderror" name="hora_apertura" id="hora_apertura">@error('hora_apertura')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label for="hora_cierre" class="form-label">Hora de cierre</label><input type="time" value="{{ old('hora_cierre') }}" class="form-control @error('hora_cierre') is-invalid @enderror" name="hora_cierre" id="hora_cierre">@error('hora_cierre')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            </div>

            <div class="d-flex flex-wrap gap-2 mt-4 pt-2">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check2-circle me-1" aria-hidden="true"></i> Crear terminal</button>
                <a href="{{ route('show_terminal') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</section>
@endsection

@section('scripts')
<script src="{{ asset('js/terminal-form.js') }}" defer></script>
@endsection
