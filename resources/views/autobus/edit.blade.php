@extends('layouts.plantilla')
@section('title', 'Editar autobús')
@section('estilos')<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">@endsection
@section('content')
<div class="container py-4"><a href="{{ route('autobuses.list') }}" class="btn btn-outline-secondary mb-3">Volver a autobuses</a><div class="card"><div class="card-header text-center"><h1 class="h3">Editar servicio de autobús</h1></div><div class="card-body"><form action="{{ route('autobus.update', $autobus) }}" method="POST">@csrf @method('PUT') @include('autobus._form')<button type="submit" class="btn btn-info btn-lg mt-4">Guardar cambios</button></form></div></div></div>
@endsection
@section('scripts')<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script><script src="{{ asset('js/bus-stops.js') }}" defer></script>@endsection
