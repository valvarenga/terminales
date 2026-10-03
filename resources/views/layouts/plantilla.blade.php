<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Terminales Nicaragua') · Terminales Nicaragua</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @hasSection('datatables')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
    @endif
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ filemtime(public_path('css/style.css')) }}">
    @yield('estilos')
</head>
@php
    $currentRoute = request()->route();
    $isAdminArea = $currentRoute && in_array('admin', $currentRoute->gatherMiddleware(), true);
@endphp
<body class="{{ $isAdminArea ? 'admin-page' : 'public-page' }}">
    <a class="skip-link" href="#main-content">Saltar al contenido</a>
    <nav class="navbar navbar-expand-lg site-nav sticky-top" aria-label="Navegación principal">
        <div class="container">
            <a class="navbar-brand brand" href="{{ route('home') }}"><span class="brand-mark">T</span><span>Terminales<br><small>Nicaragua</small></span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavigation" aria-controls="mainNavigation" aria-expanded="false" aria-label="Abrir menú"><span class="navbar-toggler-icon" aria-hidden="true"></span></button>
            <div class="collapse navbar-collapse" id="mainNavigation">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                    <li class="nav-item"><a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" @if(request()->routeIs('home')) aria-current="page" @endif>Inicio</a></li>
                    <li class="nav-item"><a href="{{ route('departamentos.listar') }}" class="nav-link {{ request()->routeIs('departamentos.*', 'departamento.*') ? 'active' : '' }}" @if(request()->routeIs('departamentos.*', 'departamento.*')) aria-current="page" @endif>Destinos</a></li>
                    <li class="nav-item"><a href="{{ route('anuncios') }}" class="nav-link {{ request()->routeIs('anuncios') ? 'active' : '' }}" @if(request()->routeIs('anuncios')) aria-current="page" @endif>Anuncios</a></li>
                    <li class="nav-item"><a href="{{ route('Acerca') }}" class="nav-link {{ request()->routeIs('Acerca') ? 'active' : '' }}" @if(request()->routeIs('Acerca')) aria-current="page" @endif>Acerca de</a></li>
                    <li class="nav-item"><a href="{{ route('contacto') }}" class="nav-link {{ request()->routeIs('contacto') ? 'active' : '' }}" @if(request()->routeIs('contacto')) aria-current="page" @endif>Contacto</a></li>
                    <li class="nav-item nav-search"><a href="{{ route('home') }}#buscar-ruta" class="btn btn-primary"><i class="bi bi-search" aria-hidden="true"></i> Buscar ruta</a></li>
                </ul>
            </div>
        </div>
    </nav>

    @if($isAdminArea && session('admin_authenticated'))
        @include('admin.partials.session-bar')
        <div class="container admin-nav-wrap">@include('admin.partials.navigation')</div>
    @endif

        <main id="main-content" tabindex="-1">

            @if(session('success'))<div class="container pt-4"><div class="alert alert-success border-0 shadow-sm" role="status">{{ session('success') }}</div></div>@endif
            @yield('content')
        </main>

        <footer class="site-footer mt-5">
            <div class="container d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4 py-5">
                <div class="d-flex align-items-center gap-3"><span class="brand-mark footer-brand-mark">T</span><div><strong class="d-block">Terminales Nicaragua</strong><p class="mb-0">Tu guía para planificar cada viaje.</p></div></div>
                <div class="footer-links"><a href="{{ route('departamentos.listar') }}">Explorar destinos</a><a href="{{ route('anuncios') }}">Anuncios</a><a href="{{ route('contacto') }}">Contacto</a></div>
            </div>
            <div class="footer-bottom"><div class="container py-3">Información de transporte para viajar mejor por Nicaragua.</div></div>
        </footer>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        @hasSection('datatables')
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
            <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
            <script>$(function () { $('#buses').DataTable({language:{url:'//cdn.datatables.net/plug-ins/1.12.1/i18n/es-MX.json'}}); });</script>
        @endif
        <script src="{{ asset('js/list-filter.js') }}" defer></script>
        <script src="{{ asset('js/ui-feedback.js') }}?v={{ filemtime(public_path('js/ui-feedback.js')) }}" defer></script>
        @yield('scripts')
    </body>
    </html>
