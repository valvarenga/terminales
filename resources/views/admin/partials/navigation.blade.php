<nav class="d-flex flex-wrap gap-2 mb-4" aria-label="Administración">
    <a class="btn btn-sm {{ request()->routeIs('admin.dashboard') ? 'btn-primary' : 'btn-outline-primary' }}" href="{{ route('admin.dashboard') }}">Resumen</a>
    <a class="btn btn-sm {{ request()->routeIs('admin.suggestions.*') ? 'btn-primary' : 'btn-outline-primary' }}" href="{{ route('admin.suggestions.index') }}">Revisar sugerencias</a>
    @if(session('admin_role', 'admin') === 'admin')
    <a class="btn btn-sm {{ request()->routeIs('admin.history') ? 'btn-primary' : 'btn-outline-primary' }}" href="{{ route('admin.history') }}">Historial de cambios</a>
    <a class="btn btn-sm {{ request()->routeIs('admin.users.*') ? 'btn-primary' : 'btn-outline-primary' }}" href="{{ route('admin.users.index') }}">Usuarios y permisos</a>
    @endif
</nav>
