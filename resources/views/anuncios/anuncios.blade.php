@extends('layouts.plantilla')
@section('title', 'Anuncios')
@section('content')
<section class="container pb-5">
    <header class="page-header"><p class="eyebrow">Negocios locales</p><h1>Da a conocer tu negocio.</h1><p>Los anuncios ayudan a mantener disponible la plataforma y permiten que más viajeros conozcan tu negocio.</p></header>
    <div class="row g-4">
        @foreach([
            ['Básico', 'Presencia en el inicio', ['Un banner de tu negocio en la página de inicio.', 'Hasta una actualización del diseño del banner.']],
            ['Intermedio', 'Más espacios para tu negocio', ['Un banner en las vistas de la plataforma.', 'Hasta tres actualizaciones del diseño del banner.']],
            ['Avanzado', 'Una página para tu negocio', ['Una vista dedicada con información de tu negocio.', 'Imágenes, descripción y mapa de ubicación.']],
        ] as [$plan, $heading, $features])
            <div class="col-lg-4"><article class="content-card plan-card"><span class="info-icon"><i class="bi bi-megaphone" aria-hidden="true"></i></span><p class="plan-label">Plan {{ $plan }}</p><h2 class="h4">{{ $heading }}</h2><ul class="mt-3 mb-0">@foreach($features as $feature)<li>{{ $feature }}</li>@endforeach</ul></article></div>
        @endforeach
    </div>
    <div class="content-card p-4 mt-4 d-flex flex-wrap justify-content-between align-items-center gap-3"><div><h2 class="h5">Planes de costo mensual</h2><p class="text-muted mb-0">Los precios y el canal de cotización aún no están publicados.</p></div><a href="{{ route('Acerca') }}" class="btn btn-outline-primary">Conocer el proyecto</a></div>
</section>
@endsection
