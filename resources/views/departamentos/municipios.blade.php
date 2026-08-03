@extends('layouts.plantilla')
@section('title','Municipios de '.$departamento->nombre)
@section('content')
<section class="container">
    <header class="page-header"><a href="{{ route('departamentos.listar') }}" class="eyebrow">← Departamentos</a>
        <h1 class="mt-2">Municipios de {{ $departamento->nombre }}</h1>
        <p>Elige una ciudad para consultar sus terminales.</p>
    </header>
    <div class="row g-4">@forelse($municipios as $municipio)<div class="col-sm-6 col-lg-4 col-xl-3"><a href="{{ route('departamento.terminales', [$departamento, $municipio]) }}" class="destination-card"><img src="{{ asset($municipio->url_M) }}" alt="{{ $municipio->nombre }}">
                <div class="destination-card__body">
                    <h3>{{ $municipio->nombre }}</h3><span>Ver terminales →</span>
                </div>
            </a></div>@empty <div class="empty-state content-card">
            <h2>Sin municipios disponibles</h2>
            <p class="mb-0">Este departamento aún no tiene municipios registrados.</p>
        </div>@endforelse</div>
</section>
@endsection