@extends('layouts.plantilla')

@section('title', 'Terminales de '.$departamento->nombre)

@section('content')
<section class="container app-shell">
    <header class="page-header pt-0">
        <a href="{{ route('departamentos.listar') }}" class="eyebrow text-decoration-none">
            <i class="bi bi-arrow-left" aria-hidden="true"></i> Departamentos
        </a>
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mt-3">
            <div>
                <h1 class="mb-2">Terminales de {{ $departamento->nombre }}</h1>
                <p class="mb-0">Selecciona una terminal para consultar sus rutas y horarios de salida.</p>
            </div>
            @if($terminalesPorMunicipio->isNotEmpty())
                <span class="badge bg-light text-dark border rounded-pill px-3 py-2">
                    <i class="bi bi-building me-1" aria-hidden="true"></i>
                    {{ $terminalesPorMunicipio->flatten()->count() }} {{ $terminalesPorMunicipio->flatten()->count() === 1 ? 'terminal' : 'terminales' }}
                </span>
            @endif
        </div>
    </header>

    @forelse($terminalesPorMunicipio as $terminales)
        @php($municipio = $terminales->first()->municipios)
        <section class="mb-5" aria-labelledby="municipio-{{ $municipio->id }}">
            <div class="d-flex align-items-center gap-3 mb-3">
                <span class="summary-icon"><i class="bi bi-geo-alt-fill" aria-hidden="true"></i></span>
                <div>
                    <p class="eyebrow mb-1">Municipio</p>
                    <h2 class="h3 mb-0" id="municipio-{{ $municipio->id }}">{{ $municipio->nombre }}</h2>
                </div>
            </div>

            <div class="row g-4">
                @foreach($terminales as $terminal)
                    <div class="col-sm-6 col-lg-4">
                        <a href="{{ route('departamento.autobuses', $terminal) }}" class="destination-card">
                            <div class="destination-card__image terminal-preview">
                                @if($terminal->url_T)
                                    <img src="{{ asset($terminal->url_T) }}" alt="{{ $terminal->nombre }}" loading="lazy" onerror="this.hidden=true;this.nextElementSibling.hidden=false">
                                    <div class="terminal-placeholder" hidden><i class="bi bi-bus-front-fill" aria-hidden="true"></i><span>Terminal de {{ $municipio->nombre }}</span></div>
                                @else
                                    <div class="terminal-placeholder"><i class="bi bi-bus-front-fill" aria-hidden="true"></i><span>Terminal de {{ $municipio->nombre }}</span></div>
                                @endif
                            </div>
                            <div class="destination-card__body">
                                <span class="destination-kicker"><i class="bi bi-building me-1" aria-hidden="true"></i> Terminal</span>
                                <h3>{{ $terminal->nombre }}</h3>
                                @if($terminal->hora_apertura || $terminal->hora_cierre)
                                    <p class="small text-muted mb-2"><i class="bi bi-clock me-1" aria-hidden="true"></i>{{ $terminal->hora_apertura ? substr($terminal->hora_apertura, 0, 5) : 'Horario abierto' }}@if($terminal->hora_cierre) – {{ substr($terminal->hora_cierre, 0, 5) }}@endif</p>
                                @endif
                                <span>Consultar horarios <i class="bi bi-arrow-right" aria-hidden="true"></i></span>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </section>
    @empty
        <div class="empty-state content-card">
            <span class="summary-icon mb-3"><i class="bi bi-building-x" aria-hidden="true"></i></span>
            <h2 class="h4">Aún no hay terminales disponibles</h2>
            <p class="mb-3">Este departamento no tiene terminales asignadas por el momento.</p>
            <a href="{{ route('departamentos.listar') }}" class="btn btn-outline-primary">Elegir otro departamento</a>
        </div>
    @endforelse
</section>
@endsection

@section('estilos')
<style>
    .terminal-preview { height: 190px; }
    .terminal-placeholder { align-items: center; background: linear-gradient(135deg, #A8C4EC, #f4f7fc); color: #0474C4; display: flex; flex-direction: column; gap: .65rem; height: 100%; justify-content: center; }
    .terminal-placeholder i { font-size: 2.5rem; }
    .terminal-placeholder span { color: #2C444C; font-size: .85rem; font-weight: 700; }
    .destination-kicker { color: #5379AE !important; font-size: .72rem !important; letter-spacing: .07em; text-transform: uppercase; }
</style>
@endsection
