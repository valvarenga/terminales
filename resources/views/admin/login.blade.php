@extends('layouts.plantilla')

@section('title', 'Acceso administrativo')

@section('content')
<div class="container login-page py-5 my-lg-4">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            <div class="card border-0 overflow-hidden">
                <div class="card-body p-4 p-lg-5">
                    <span class="summary-icon mb-3"><i class="bi bi-shield-lock" aria-hidden="true"></i></span>
                    <p class="eyebrow mb-2">Área protegida</p>
                    <h1 class="h2 mb-2">Acceso administrativo</h1>
                    <p class="text-muted mb-4">Ingresa tus credenciales para gestionar la información de transporte.</p>

                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert" tabindex="-1" data-error-summary>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.login.submit') }}" method="POST">
                        @csrf
                        <input type="hidden" name="redirect" value="{{ $redirect }}">

                        <div class="mb-3">
                            <label for="username" class="form-label">Usuario o correo del equipo</label>
                            <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" autocomplete="username" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <div class="input-group"><input type="password" class="form-control" id="password" name="password" autocomplete="current-password" required><button type="button" class="btn btn-outline-secondary" aria-controls="password" aria-pressed="false" data-password-toggle hidden>Mostrar</button></div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-box-arrow-in-right me-1" aria-hidden="true"></i> Ingresar</button>
                    </form>
                    <a href="{{ route('home') }}" class="d-inline-flex align-items-center mt-4"><i class="bi bi-arrow-left me-2" aria-hidden="true"></i> Volver al inicio</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
