<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Terminales Nicaragua') · Terminales Nicaragua</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @yield('estilos')
</head>
<body>
    <nav class="navbar navbar-expand-lg site-nav sticky-top">
        <div class="container">
            <a class="navbar-brand brand" href="{{ route('home') }}"><span class="brand-mark">T</span><span>Terminales<br><small>Nicaragua</small></span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavigation" aria-controls="mainNavigation" aria-expanded="false" aria-label="Abrir menú"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="mainNavigation">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                    <li class="nav-item"><a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Inicio</a></li>
                    <li class="nav-item"><a href="{{ route('departamentos.listar') }}" class="nav-link {{ request()->routeIs('departamentos.*', 'departamento.*') ? 'active' : '' }}">Destinos</a></li>
                    <li class="nav-item"><a href="{{ route('anuncios') }}" class="nav-link {{ request()->routeIs('anuncios') ? 'active' : '' }}">Anuncios</a></li>
                    <li class="nav-item"><a href="{{ route('Acerca') }}" class="nav-link {{ request()->routeIs('Acerca') ? 'active' : '' }}">Acerca de</a></li>
                    <li class="nav-item"><a href="{{ route('contacto') }}" class="nav-link {{ request()->routeIs('contacto') ? 'active' : '' }}">Contacto</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        @if(session('success'))<div class="container pt-4"><div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div></div>@endif
        @yield('content')
    </main>

    <footer class="site-footer mt-5">
        <div class="container d-flex flex-column flex-md-row justify-content-between gap-3 py-4">
            <div><strong>Terminales Nicaragua</strong><p class="mb-0">Tu guía para planificar cada viaje.</p></div>
            <div class="footer-links"><a href="{{ route('departamentos.listar') }}">Explorar destinos</a><a href="{{ route('contacto') }}">Contacto</a></div>
        </div>
    </footer>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
    <script>$(function () { if ($('#buses').length) $('#buses').DataTable({language:{url:'//cdn.datatables.net/plug-ins/1.12.1/i18n/es-MX.json'}}); });</script>
    @yield('scripts')
</body>
</html>
