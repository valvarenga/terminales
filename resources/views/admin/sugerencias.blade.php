@extends('layouts.plantilla')
@section('title', 'Revisar sugerencias')
@section('content')
<div class="container py-5">
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary mb-3">Volver al panel</a>
    <h1 class="h3">Revisar sugerencias de terminales</h1>
    <p class="text-muted">Vincula las aprobadas a una terminal existente. Publicar una foto requiere confirmación expresa.</p>
    @include('admin.partials.navigation')
    @if($errors->any())<div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <form method="GET" class="d-flex gap-2 align-items-end mb-4">
        <div><label for="estado" class="form-label">Estado</label><select id="estado" name="estado" class="form-select">
            @foreach(['pendiente' => 'Pendientes', 'aprobada' => 'Aprobadas', 'rechazada' => 'Rechazadas', 'todas' => 'Todas'] as $value => $label)<option value="{{ $value }}" @selected($estado === $value)>{{ $label }}</option>@endforeach
        </select></div><button class="btn btn-primary">Filtrar</button>
    </form>
    @forelse($sugerencias as $sugerencia)
        @php($restore = (string) old('sugerencia_id') === (string) $sugerencia->id)
        <article class="card mb-4" id="sugerencia-{{ $sugerencia->id }}"><div class="card-body">
            <div class="d-flex flex-wrap justify-content-between gap-2"><h2 class="h5">{{ $sugerencia->nombre_terminal }}</h2><span class="badge bg-secondary align-self-start">{{ ucfirst($sugerencia->estado) }}</span></div>
            <p><strong>Ubicación:</strong> {{ $sugerencia->ubicacion ?: 'No indicada' }}<br><small class="text-muted">Enviada: {{ $sugerencia->created_at?->format('d/m/Y H:i') }}</small></p>
            @if($sugerencia->foto)<a href="{{ route('admin.suggestions.photo', $sugerencia) }}" target="_blank" rel="noopener"><img src="{{ route('admin.suggestions.photo', $sugerencia) }}" alt="Foto propuesta de {{ $sugerencia->nombre_terminal }}" class="img-thumbnail mb-3" style="max-height:180px" loading="lazy"></a>@endif
            @if($sugerencia->estado === 'pendiente')
                <form method="POST" action="{{ route('admin.suggestions.review', $sugerencia) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="sugerencia_id" value="{{ $sugerencia->id }}">
                    <div class="row g-3">
                        <div class="col-md-4"><label for="decision-{{ $sugerencia->id }}" class="form-label">Decisión</label><select required id="decision-{{ $sugerencia->id }}" name="estado" class="form-select"><option value="aprobada" @selected(!$restore || old('estado') === 'aprobada')>Aprobar</option><option value="rechazada" @selected($restore && old('estado') === 'rechazada')>Rechazar</option></select></div>
                        <div class="col-md-8"><label for="terminal-{{ $sugerencia->id }}" class="form-label">Terminal vinculada (obligatoria al aprobar)</label><select id="terminal-{{ $sugerencia->id }}" name="terminal_id" class="form-select"><option value="">Selecciona una terminal</option>@foreach($terminales as $terminal)<option value="{{ $terminal->id }}" @selected($restore && (string) old('terminal_id') === (string) $terminal->id)>{{ $terminal->nombre }} — {{ $terminal->municipios?->nombre ?? 'Sin municipio' }}</option>@endforeach</select><small><a href="{{ route('newterminal') }}" target="_blank" rel="noopener">Registrar una terminal nueva</a> y luego actualizar esta lista.</small></div>
                        <div class="col-12"><label for="motivo-{{ $sugerencia->id }}" class="form-label">Motivo o comentario (obligatorio al rechazar)</label><textarea id="motivo-{{ $sugerencia->id }}" name="motivo_revision" class="form-control" rows="3" maxlength="2000">{{ $restore ? old('motivo_revision') : '' }}</textarea></div>
                    </div>
                    @if($sugerencia->foto)
                        <div class="form-check mt-3"><input type="checkbox" class="form-check-input" id="publicar-{{ $sugerencia->id }}" name="publicar_foto" value="1" @checked($restore && old('publicar_foto'))><label class="form-check-label" for="publicar-{{ $sugerencia->id }}">Publicar esta foto en la terminal al aprobar</label></div>
                        <div class="form-check"><input type="checkbox" class="form-check-input" id="reemplazar-{{ $sugerencia->id }}" name="reemplazar_foto" value="1" @checked($restore && old('reemplazar_foto'))><label class="form-check-label" for="reemplazar-{{ $sugerencia->id }}">Confirmo reemplazar la foto actual de la terminal si ya existe</label></div>
                    @endif
                    <button class="btn btn-primary mt-3" type="submit">Guardar revisión</button>
                </form>
            @else
                <p class="mb-1"><strong>Revisada por:</strong> {{ $sugerencia->revisada_por }} · {{ $sugerencia->revisada_at?->format('d/m/Y H:i') }}</p>
                @if($sugerencia->terminal)<p class="mb-1"><strong>Terminal vinculada:</strong> {{ $sugerencia->terminal->nombre }}</p>@endif
                @if($sugerencia->motivo_revision)<p class="mb-0" style="white-space:pre-wrap">{{ $sugerencia->motivo_revision }}</p>@endif
            @endif
        </div></article>
    @empty
        <div class="alert alert-light border">No hay sugerencias en este estado.</div>
    @endforelse
    {{ $sugerencias->links() }}
</div>
@endsection
