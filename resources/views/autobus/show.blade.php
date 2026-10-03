@extends('layouts.plantilla')

@section('title', 'Detalle del autobús')

@section('estilos')
<style>
    .bus-detail-page { --bus-primary: #0465aa; --bus-primary-dark: #06457f; --bus-soft: #eaf3fc; --bus-ink: #262b40; --bus-muted: #52627a; --bus-line: #d9e3ef; color: var(--bus-ink); }
    .bus-detail-page h1, .bus-detail-page h2, .bus-detail-page h3 { font-family: inherit; }
    .bus-detail-page .bus-back { display: inline-flex; align-items: center; min-height: 44px; color: var(--bus-primary-dark); font-weight: 600; }
    .bus-detail-page .bus-hero { background: linear-gradient(120deg, #06457f, #262b40); border-radius: 1.25rem; color: #fff; position: relative; }
    .bus-detail-page .bus-hero-content > div { min-width: 0; }
    .bus-detail-page h1 { font-size: clamp(1.8rem, 4vw, 2.8rem); overflow-wrap: anywhere; }
    .bus-detail-page .category-badge { background: #ffffff18; border: 1px solid #ffffff45; color: #fff; font-size: .85rem; white-space: normal; text-align: left; }
    .bus-detail-page .hero-route { display: flex; flex-wrap: wrap; align-items: center; gap: .75rem; margin-top: 1.25rem; color: #e4eefb; font-size: 1.1rem; }
    .bus-detail-page .hero-route strong { display: block; color: #fff; font-weight: 600; overflow-wrap: anywhere; }
    .bus-detail-page .hero-route small { display: block; font-size: .75rem; letter-spacing: .08em; text-transform: uppercase; margin-bottom: .25rem; }
    .bus-detail-page .summary-card { background: #fff; border: 1px solid var(--bus-line); border-radius: 1rem; height: 100%; padding: 1.25rem; display: flex; align-items: center; gap: 1rem; }
    .bus-detail-page .summary-card > div { min-width: 0; }
    .bus-detail-page .summary-icon { align-items: center; background: var(--bus-soft); border-radius: .8rem; color: var(--bus-primary-dark); display: inline-flex; flex-shrink: 0; font-size: 1.2rem; height: 2.75rem; justify-content: center; width: 2.75rem; }
    .bus-detail-page .summary-label { color: var(--bus-muted); font-size: .78rem; font-weight: 600; letter-spacing: .05em; text-transform: uppercase; }
    .bus-detail-page .summary-value { color: var(--bus-ink); font-size: clamp(1.1rem, 2vw, 1.5rem); font-weight: 700; overflow-wrap: anywhere; font-variant-numeric: tabular-nums; }
    .bus-detail-page .route-panel { background: #fff; border: 1px solid var(--bus-line); border-radius: 1rem; box-shadow: 0 .4rem 1.5rem #262b4005; }
    .bus-detail-page .panel-heading { padding-bottom: 1.25rem; border-bottom: 1px solid var(--bus-line); }
    .bus-detail-page .text-muted { color: var(--bus-muted) !important; }
    .bus-detail-page .route-timeline { list-style: none; padding: 0; margin: 0; }
    .bus-detail-page .route-stop { display: grid; gap: 1rem; grid-template-columns: 2.25rem minmax(0, 1fr) auto; min-height: 6rem; position: relative; padding-bottom: 1.5rem; }
    .bus-detail-page .route-stop:last-child { min-height: 0; padding-bottom: 0; }
    .bus-detail-page .route-stop:not(:last-child)::after { background: var(--bus-line); content: ''; height: calc(100% - 2rem); left: 1.08rem; position: absolute; top: 2rem; width: 2px; }
    .bus-detail-page .stop-marker { align-items: center; background: #fff; border: 2px solid var(--bus-line); border-radius: 50%; color: var(--bus-primary-dark); display: flex; font-size: .8rem; font-weight: 700; height: 2.25rem; justify-content: center; position: relative; width: 2.25rem; z-index: 1; }
    .bus-detail-page .route-stop:first-child .stop-marker, .bus-detail-page .route-stop:last-child .stop-marker { background: var(--bus-primary-dark); border-color: var(--bus-primary-dark); color: #fff; }
    .bus-detail-page .route-stop h3 { font-size: 1rem; font-weight: 700; overflow-wrap: anywhere; }
    .bus-detail-page .stop-meta { color: var(--bus-muted); font-size: .95rem; }
    .bus-detail-page .fare-chip { align-self: start; background: var(--bus-soft); border-radius: .5rem; color: var(--bus-primary-dark); font-size: .9rem; font-weight: 700; padding: .4rem .75rem; font-variant-numeric: tabular-nums; }
    .bus-detail-page .terminal-list { display: grid; gap: .75rem; }
    .bus-detail-page .terminal-item { align-items: center; background: #f4f7fc; border-radius: .8rem; display: flex; gap: .75rem; padding: 1rem; overflow-wrap: anywhere; }
    .bus-detail-page .terminal-item > div { min-width: 0; }
    .bus-detail-page .action-bar { display: flex; flex-wrap: wrap; gap: .75rem; }
    .bus-detail-page .btn { min-height: 44px; display: inline-flex; align-items: center; justify-content: center; padding: .65rem 1rem; font-weight: 600; }
    .bus-detail-page .action-delete { border-color: #fecaca; color: #fecaca; }
    .bus-detail-page .action-delete:hover { background: #fff1f2; border-color: #fff1f2; color: #9f1239; }
    .bus-detail-page a:focus-visible, .bus-detail-page button:focus-visible { outline: 3px solid #0474c4; outline-offset: 4px; box-shadow: 0 0 0 2px #fff; }
    .bus-detail-page .bus-hero a:focus-visible, .bus-detail-page .bus-hero button:focus-visible { outline-color: #fff; box-shadow: 0 0 0 2px #06457f; }
    .bus-detail-page .empty-route { background: #f4f7fc; border: 1px dashed var(--bus-line); border-radius: .75rem; }
    @media (max-width: 767.98px) {
        .bus-detail-page .summary-card { display: block; padding: 1rem; }
        .bus-detail-page .summary-icon { margin-bottom: .75rem; }
        .bus-detail-page .bus-hero { border-radius: 1rem; }
        .bus-detail-page .action-bar > a { flex: 1 1 100%; }
        .bus-detail-page .action-bar > form { margin-top: .25rem; }
        .bus-detail-page .action-bar .btn { width: 100%; }
        .bus-detail-page .route-stop { grid-template-columns: 2.25rem minmax(0, 1fr); gap: .5rem .75rem; }
        .bus-detail-page .fare-chip { grid-column: 2; justify-self: start; }
        .bus-detail-page .panel-heading { flex-wrap: wrap; }
    }
</style>
@endsection

@section('content')
@php
    $originName = $autobus->origenMunicipio?->nombre ?? $autobus->origen ?? 'Origen por confirmar';
    $destinationName = $autobus->destinoMunicipio?->nombre ?? $autobus->destino ?? 'Destino por confirmar';
    $departureTime = $autobus->hora_salida ? formato_hora($autobus->hora_salida, 'Por confirmar') : 'Por confirmar';
    $arrivalTime = $autobus->hora_llegada ? formato_hora($autobus->hora_llegada, 'Por confirmar') : 'Por confirmar';
@endphp

<section class="bus-detail-page container py-4 py-lg-5">
    <a href="{{ route('autobuses.list') }}" class="bus-back mb-3">
        <i class="bi bi-arrow-left me-1" aria-hidden="true"></i> Volver a autobuses
    </a>

    <header class="bus-hero p-4 p-lg-5 mb-4 shadow-sm">
        <div class="bus-hero-content d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-4">
            <div>
                <span class="badge category-badge rounded-pill px-3 py-2 mb-3">
                    <i class="bi bi-bus-front-fill me-1" aria-hidden="true"></i>
                    {{ $autobus->categoria ?: 'Servicio de autobús' }}
                </span>
                <h1 class="display-6 fw-bold mb-2">{{ $autobus->nombre }}</h1>
                <div class="hero-route">
                    <div><small>Origen</small><strong>{{ $originName }}</strong></div>
                    <i class="bi bi-arrow-right mx-2" aria-hidden="true"></i>
                    <div><small>Destino</small><strong>{{ $destinationName }}</strong></div>
                </div>
            </div>

            <div class="action-bar">
                <a href="{{ route('autobus.duplicate', $autobus) }}" class="btn btn-light fw-semibold">
                    <i class="bi bi-plus-circle me-1" aria-hidden="true"></i> Registrar otra salida
                </a>
                <a href="{{ route('autobus.edit', $autobus) }}" class="btn btn-outline-light fw-semibold">
                    <i class="bi bi-pencil-square me-1" aria-hidden="true"></i> Editar
                </a>
                @if(session('admin_role', 'admin') === 'admin')
                    <form action="{{ route('autobus.destroy', $autobus) }}" method="POST" onsubmit="return confirm('¿Eliminar este autobús? Esta acción no se puede deshacer.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn action-delete">
                            <i class="bi bi-trash3 me-1" aria-hidden="true"></i> Eliminar
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </header>

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3"><div class="summary-card"><span class="summary-icon"><i class="bi bi-clock" aria-hidden="true"></i></span><div><div class="summary-label mb-1">Salida</div><div class="summary-value">{{ $departureTime }}</div></div></div></div>
        <div class="col-6 col-lg-3"><div class="summary-card"><span class="summary-icon"><i class="bi bi-clock-fill" aria-hidden="true"></i></span><div><div class="summary-label mb-1">Llegada</div><div class="summary-value">{{ $arrivalTime }}</div></div></div></div>
        <div class="col-6 col-lg-3"><div class="summary-card"><span class="summary-icon"><i class="bi bi-cash-coin" aria-hidden="true"></i></span><div><div class="summary-label mb-1">Tarifa</div><div class="summary-value">{{ $autobus->tarifa !== null ? 'C$ '.number_format((float) $autobus->tarifa, 2) : 'Por confirmar' }}</div></div></div></div>
        <div class="col-6 col-lg-3"><div class="summary-card"><span class="summary-icon"><i class="bi bi-card-text" aria-hidden="true"></i></span><div><div class="summary-label mb-1">Placa</div><div class="summary-value">{{ $autobus->placa ?: 'No registrada' }}</div></div></div></div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <article class="route-panel p-4 h-100">
                <div class="d-flex justify-content-between align-items-start gap-3 mb-4 panel-heading">
                    <div><h2 class="h4 mb-1">Recorrido y paradas</h2><p class="text-muted mb-0">Horario y tarifa acumulada desde el origen.</p></div>
                    <span class="badge bg-light text-dark border rounded-pill">{{ $autobus->paradas->count() }} {{ $autobus->paradas->count() === 1 ? 'parada' : 'paradas' }}</span>
                </div>

                <ol class="route-timeline" aria-label="Paradas del recorrido">
                @forelse($autobus->paradas as $parada)
                    <li class="route-stop">
                        <span class="stop-marker" aria-hidden="true">{{ $loop->iteration }}</span>
                        <div class="pt-1">
                            <h3 class="h6 mb-1">{{ $parada->municipio?->nombre ?? 'Municipio no disponible' }}</h3>
                            <div class="stop-meta"><i class="bi bi-clock me-1" aria-hidden="true"></i>{{ $parada->hora_paso ? formato_hora($parada->hora_paso, 'Hora por confirmar') : 'Hora por confirmar' }}@if($loop->first)<span class="ms-2">Salida</span>@elseif($loop->last)<span class="ms-2">Destino</span>@endif</div>
                        </div>
                        <span class="fare-chip">{{ $parada->tarifa_acumulada !== null ? 'C$ '.number_format((float) $parada->tarifa_acumulada, 2) : 'Tarifa por confirmar' }}</span>
                    </li>
                @empty
                    <li class="text-center empty-route p-4"><i class="bi bi-signpost-split fs-2 text-muted" aria-hidden="true"></i><p class="fw-semibold mt-2 mb-1">No hay paradas registradas</p><p class="small text-muted mb-0">Agrega las paradas para consultar el recorrido y sus horarios.</p><a href="{{ route('autobus.edit', $autobus) }}" class="btn btn-outline-primary mt-3">Agregar paradas</a></li>
                @endforelse
                </ol>
            </article>
        </div>

        <div class="col-lg-4">
            <aside class="route-panel p-4 h-100">
                <h2 class="h4 mb-1">Terminales asociadas</h2>
                <p class="text-muted mb-4">Puntos de operación de este servicio.</p>
                <div class="terminal-list">
                    @forelse($autobus->terminales as $terminal)
                        <div class="terminal-item"><span class="summary-icon flex-shrink-0"><i class="bi bi-building" aria-hidden="true"></i></span><div><div class="summary-label">Terminal</div><div class="fw-semibold">{{ $terminal->nombre }}</div></div></div>
                    @empty
                        <div class="alert alert-light border mb-0"><i class="bi bi-info-circle me-1" aria-hidden="true"></i> No hay terminales asociadas.</div>
                    @endforelse
                </div>
                <hr class="my-4">
                <dl class="row small mb-0">
                    <dt class="col-5 text-muted fw-semibold">Categoría</dt><dd class="col-7 text-end fw-semibold">{{ $autobus->categoria ?: 'No registrada' }}</dd>
                    <dt class="col-5 text-muted fw-semibold mb-0">Placa</dt><dd class="col-7 text-end fw-semibold mb-0">{{ $autobus->placa ?: 'No registrada' }}</dd>
                </dl>
            </aside>
        </div>
    </div>
</section>
@endsection
