@extends('layouts.plantilla')
@section('title', 'Acerca del proyecto')
@section('content')
<section class="container pb-5">
    <header class="page-header"><p class="eyebrow">Conoce el proyecto</p><h1>Información para viajar mejor.</h1><p>Terminales Nicaragua reúne destinos, terminales y horarios para ayudarte a planificar tu viaje.</p></header>
    <div class="row g-4">
        <div class="col-lg-8"><article class="content-card info-content p-4 p-lg-5">
            <span class="info-icon"><i class="bi bi-signpost-split" aria-hidden="true"></i></span>
            <h2 class="h4">¿Por qué existe?</h2><p>Consultar la salida de un bus suele requerir llamar o visitar una terminal. Este proyecto busca hacer esa información más accesible para quienes viven en Nicaragua y para quienes visitan el país.</p>
            <h2 class="h4 mt-4">Un proyecto independiente</h2><p>NicaBuses es un proyecto personal y voluntario desarrollado por Victor Alvarenga, Roberto Perez Lira y Nestor Lozano. No está asociado con cooperativas, transportistas ni terminales de buses.</p>
            <h2 class="h4 mt-4">Una comunidad que aporta</h2><p class="mb-0">Puedes ayudarnos compartiendo la plataforma o sugiriendo terminales que aún no aparecen. Las sugerencias se revisan antes de publicarse.</p>
        </article></div>
        <aside class="col-lg-4"><div class="content-card p-4"><h2 class="h5">Planifica tu próxima salida</h2><p class="text-muted">Encuentra rutas directas y opciones con transbordos entre municipios.</p><a href="{{ route('home') }}#buscar-ruta" class="btn btn-primary w-100 mb-3">Buscar una ruta</a><a href="{{ route('home') }}#sugerir-terminal" class="btn btn-outline-primary w-100">Sugerir una terminal</a></div></aside>
    </div>
</section>
@endsection
