@extends('layouts.plantilla')
@section('title', 'Contacto y ayuda')
@section('content')
<section class="container pb-5">
    <header class="page-header"><p class="eyebrow">Contacto y ayuda</p><h1>¿Cómo podemos orientarte?</h1><p>Encuentra información para planificar un viaje o aportar una terminal a la plataforma.</p></header>
    <div class="row g-4">
        <div class="col-md-6"><article class="content-card p-4 h-100"><span class="info-icon"><i class="bi bi-search" aria-hidden="true"></i></span><h2 class="h4">Consulta una ruta</h2><p class="text-muted">Selecciona un municipio de origen y otro de destino. Los resultados muestran horarios, tarifas registradas y transbordos.</p><a href="{{ route('home') }}#buscar-ruta" class="btn btn-primary">Buscar rutas</a></article></div>
        <div class="col-md-6"><article class="content-card p-4 h-100"><span class="info-icon"><i class="bi bi-chat-left-text" aria-hidden="true"></i></span><h2 class="h4">Sugiere una terminal</h2><p class="text-muted">Comparte el nombre, la ubicación y, si tienes, una foto. El equipo revisará la información antes de agregarla.</p><a href="{{ route('home') }}#sugerir-terminal" class="btn btn-outline-primary">Enviar una sugerencia</a></article></div>
    </div>
    <div class="content-card p-4 mt-4"><h2 class="h4 mb-3">Preguntas frecuentes</h2>
        <details class="border-bottom pb-2 mb-2"><summary class="fw-semibold">¿Puedo reservar un pasaje aquí?</summary><p class="text-muted mb-2">La plataforma permite consultar información de transporte. Para comprar un pasaje, consulta directamente con la terminal o el transportista.</p></details>
        <details class="border-bottom pb-2 mb-2"><summary class="fw-semibold">¿Qué significa una tarifa por confirmar?</summary><p class="text-muted mb-2">La tarifa del servicio aún no está registrada. Confirma el precio con el transportista antes de viajar.</p></details>
        <details><summary class="fw-semibold">¿Por qué no encuentro una ruta?</summary><p class="text-muted mb-2">Puede que no existan servicios registrados o que sus horarios no permitan una conexión. Prueba otro municipio cercano.</p></details>
    </div>
    <p class="text-muted mt-4 mb-0">El canal de contacto directo del equipo aún no está publicado en esta plataforma.</p>
</section>
@endsection
