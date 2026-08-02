@extends('layouts.plantilla')
@section('title','Explorar destinos')
@section('content')
<section class="container"><header class="page-header"><p class="eyebrow">Explora Nicaragua</p><h1>¿A dónde quieres viajar?</h1><p>Selecciona un departamento para descubrir sus municipios, terminales y horarios.</p></header><div class="row g-4">@forelse($departamentos as $departamento)<div class="col-sm-6 col-lg-4 col-xl-3"><a href="{{ route('departamentos.municipios', $departamento) }}" class="destination-card"><img src="{{ asset($departamento->url) }}" alt="{{ $departamento->nombre }}"><div class="destination-card__body"><h3>{{ $departamento->nombre }}</h3><span>Ver municipios →</span></div></a></div>@empty <div class="empty-state content-card"><h2>Aún no hay destinos</h2><p class="mb-0">Vuelve pronto para consultar las terminales disponibles.</p></div>@endforelse</div></section>
@endsection
