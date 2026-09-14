<aside class="admin-session-bar" aria-label="Sesión administrativa">
    <div class="container d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2 py-2">
        <div class="admin-session-profile">
            <span class="admin-session-label">Sesión administrativa</span>
            <strong>{{ session('admin_name', session('admin_actor', 'Administrador')) }}</strong>
            @if(session('admin_actor') && session('admin_actor') !== session('admin_name'))
                <span>{{ session('admin_actor') }}</span>
            @endif
            <span class="admin-session-role">{{ session('admin_role', 'admin') === 'admin' ? 'Administrador' : 'Editor' }}</span>
        </div>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-light">Cerrar sesión</button>
        </form>
    </div>
</aside>
