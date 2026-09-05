@extends('layouts.plantilla')
@section('title', 'Panel administrativo')
@section('content')
<section class="container py-4">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div><p class="eyebrow">Administración</p><h1 class="h2">Un vistazo a Terminales</h1><p class="text-muted mb-0">Gestiona la información y descubre qué viajes buscan los pasajeros.</p></div>
        <form method="POST" action="{{ route('admin.logout') }}">@csrf<button class="btn btn-outline-secondary">Cerrar sesión</button></form>
    </div>
    @include('admin.partials.navigation')
    <div class="row g-3 mb-4">
        @foreach($totals as $label => $total)
        <div class="col-6 col-lg-3"><div class="content-card p-3"><p class="text-muted mb-1">{{ $label }}</p><strong class="fs-2">{{ number_format($total) }}</strong></div></div>
        @endforeach
    </div>
    <div class="row g-3 mb-4">
        @foreach([['Departamentos','departamentos.show','newdepartamento'], ['Municipios','municipio.show','newmunicipio'], ['Terminales','show_terminal','newterminal'], ['Autobuses','autobuses.list','newbus']] as [$label,$list,$create])
        <div class="col-sm-6 col-lg-3"><div class="content-card p-3 h-100"><h2 class="h5">{{ $label }}</h2><div class="d-flex flex-wrap gap-2"><a class="btn btn-outline-primary btn-sm" href="{{ route($list) }}">Ver y editar</a><a class="btn btn-primary btn-sm" href="{{ route($create) }}">Registrar</a></div></div></div>
        @endforeach
    </div>
    <section class="content-card p-3 p-md-4 mb-4">
        <h2 class="h4">Información por completar</h2>
        <div class="d-flex flex-wrap gap-2 mb-3"><a class="btn btn-outline-primary" href="{{ route('admin.suggestions.index') }}">{{ $pendingSuggestions }} sugerencias pendientes</a><span class="badge bg-light text-dark border p-3">{{ $withoutFare }} servicios sin tarifa</span><span class="badge bg-light text-dark border p-3">{{ $incompleteServices }} servicios sin municipios vinculados</span></div>
        @forelse($attentionServices as $service)
        <div class="d-flex justify-content-between gap-3 align-items-center border-top py-2"><div><strong>{{ $service->nombre }}</strong><span class="small text-muted d-block">{{ $service->origen }} → {{ $service->destino }} · {{ $service->tarifa === null ? 'Falta tarifa' : '' }} {{ !$service->municipio_origen_id || !$service->municipio_destino_id ? ' · Faltan municipios' : '' }}</span></div><a class="btn btn-sm btn-outline-primary" href="{{ route('autobus.edit', $service) }}">Completar</a></div>
        @empty<p class="text-muted mb-0">Los servicios tienen tarifa y municipios registrados.</p>@endforelse
        <p class="small text-muted mt-3 mb-0">Se muestran hasta 8 servicios. Una ficha completa no garantiza que el horario haya sido verificado recientemente.</p>
    </section>
    <section class="content-card p-3 p-md-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3"><div><h2 class="h4 mb-1">Búsquedas de pasajeros</h2><p class="small text-muted mb-0">Últimos {{ $days }} días. Datos recopilados desde la activación de esta función.</p></div><form method="GET" class="d-flex gap-2 align-items-center"><label for="days">Período</label><select id="days" name="days" class="form-select">@foreach([7,30,90] as $period)<option value="{{ $period }}" @selected($period === $days)>{{ $period }} días</option>@endforeach</select><button class="btn btn-primary">Aplicar</button></form></div>
        <div class="row g-3 mb-4"><div class="col-sm-4"><div class="border rounded p-3"><span class="text-muted">Consultas</span><strong class="d-block fs-3">{{ number_format($totalSearches) }}</strong></div></div><div class="col-sm-4"><div class="border rounded p-3"><span class="text-muted">Sin resultados</span><strong class="d-block fs-3">{{ number_format($emptySearches) }}</strong></div></div><div class="col-sm-4"><div class="border rounded p-3"><span class="text-muted">Con opciones de viaje</span><strong class="d-block fs-3">{{ $totalSearches ? round(100 * ($totalSearches - $emptySearches) / $totalSearches) . '%' : '—' }}</strong></div></div></div>
        <div class="row g-4">@foreach([['Trayectos más buscados',$popular], ['Búsquedas sin rutas registradas',$unserved]] as [$heading,$items])<div class="col-lg-6"><h3 class="h5">{{ $heading }}</h3>@if($items->isEmpty())<p class="text-muted">Todavía no hay consultas para este período.</p>@else<div class="table-responsive"><table class="table align-middle"><thead><tr><th>Trayecto</th><th class="text-end">Consultas</th></tr></thead><tbody>@foreach($items as $item)<tr><td>{{ $item->origin_name }} → {{ $item->destination_name }}</td><td class="text-end">{{ $item->total }}</td></tr>@endforeach</tbody></table></div>@endif</div>@endforeach</div>
        <p class="small text-muted mb-0">Se cuentan consultas, no personas. Las recargas consecutivas del mismo trayecto se omiten durante un minuto. No se guardan IP ni identidades de visitantes.</p>
    </section>
</section>
@endsection
