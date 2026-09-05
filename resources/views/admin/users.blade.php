@extends('layouts.plantilla')
@section('title', 'Usuarios y permisos')
@section('content')
<section class="container py-4">
    <h1 class="h2 mb-3">Usuarios y permisos</h1>
    @include('admin.partials.navigation')
    @include('admin.partials.errors')
    <p class="text-muted">Los editores pueden registrar y modificar datos y revisar sugerencias. Los administradores también pueden eliminar registros, consultar el historial y gestionar cuentas.</p>
    <div class="content-card p-3 p-md-4 mb-4"><h2 class="h4">Cuentas del equipo</h2><div class="table-responsive"><table class="table align-middle"><thead><tr><th>Nombre</th><th>Correo</th><th>Permisos</th><th>Estado</th><th>Acciones</th></tr></thead><tbody>@forelse($users as $account)<tr><td>{{ $account->name }}</td><td>{{ $account->email }}</td><td>{{ $account->role === 'admin' ? 'Administrador' : 'Editor' }}</td><td>{{ $account->is_active ? 'Activa' : 'Desactivada' }}</td><td><a class="btn btn-outline-primary btn-sm" href="{{ route('admin.users.edit', $account) }}">Editar</a></td></tr>@empty<tr><td colspan="5">Todavía no hay cuentas del equipo. Puedes crear la primera aquí.</td></tr>@endforelse</tbody></table></div>{{ $users->links() }}<p class="small text-muted mb-0">El acceso principal configurado en el servidor continúa disponible y no aparece en esta lista.</p></div>
    <div class="content-card p-3 p-md-4"><h2 class="h4">Crear usuario</h2><form method="POST" action="{{ route('admin.users.store') }}">@csrf @include('admin.partials.user-form')<button class="btn btn-primary mt-3">Crear usuario</button></form></div>
</section>
@endsection
