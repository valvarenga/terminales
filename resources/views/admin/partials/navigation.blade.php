<nav class="admin-nav d-flex flex-wrap mb-0" aria-label="Administración">
    @foreach([
        ['Resumen', 'admin.dashboard', 'admin.dashboard', 'grid'],
        ['Departamentos', 'departamentos.show', 'departamentos.show|departamento.ver|newdepartamento', 'map'],
        ['Municipios', 'municipio.show', 'municipio.show|municipio.edit|municipio.ver|newmunicipio', 'geo-alt'],
        ['Terminales', 'show_terminal', 'show_terminal|ver.terminal|terminal.edit|newterminal|ruta.index', 'building'],
        ['Autobuses', 'autobuses.list', 'autobus.*|autobuses.list|newbus', 'bus-front'],
        ['Sugerencias', 'admin.suggestions.index', 'admin.suggestions.*', 'chat-left-text'],
    ] as [$label, $route, $pattern, $icon])
        @php($active = request()->routeIs(...explode('|', $pattern)))
        <a class="btn btn-sm {{ $active ? 'btn-primary' : 'btn-outline-primary' }}" href="{{ route($route) }}" @if($active) aria-current="page" @endif><i class="bi bi-{{ $icon }}" aria-hidden="true"></i> {{ $label }}</a>
    @endforeach
    @if(session('admin_role', 'admin') === 'admin')
        @foreach([['Historial', 'admin.history', 'admin.history', 'clock-history'], ['Usuarios', 'admin.users.index', 'admin.users.*', 'people']] as [$label, $route, $pattern, $icon])
            @php($active = request()->routeIs($pattern))
            <a class="btn btn-sm {{ $active ? 'btn-primary' : 'btn-outline-primary' }}" href="{{ route($route) }}" @if($active) aria-current="page" @endif><i class="bi bi-{{ $icon }}" aria-hidden="true"></i> {{ $label }}</a>
        @endforeach
    @endif
</nav>
