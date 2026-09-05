@extends('layouts.plantilla')
@section('title', 'Editar usuario')
@section('content')
<section class="container py-4"><h1 class="h2 mb-3">Editar usuario</h1>@include('admin.partials.navigation')
<div class="content-card p-3 p-md-4">@include('admin.partials.errors')<form method="POST" action="{{ route('admin.users.update', $user) }}">@csrf @method('PUT') @include('admin.partials.user-form')<p class="text-muted mt-3">Cambiar el correo, los permisos, la contraseña o el estado cerrará las sesiones anteriores de esta cuenta.</p><button class="btn btn-primary">Guardar cambios</button><a class="btn btn-outline-secondary" href="{{ route('admin.users.index') }}">Cancelar</a></form></div></section>
@endsection
