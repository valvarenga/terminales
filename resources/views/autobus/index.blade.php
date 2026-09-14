@extends('layouts.plantilla')
@section('title', 'Nuevo autobús')
@section('estilos')<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">@endsection
@section('content')
<div class="container py-4"><div class="card"><div class="card-header text-center"><h1 class="h3">Registrar servicio de autobús</h1></div><div class="card-body"><form action="{{ route('autobus') }}" method="POST">@csrf @include('autobus._form')<button type="submit" class="btn btn-info btn-lg mt-4">Guardar servicio</button></form></div></div></div>
@endsection
@section('scripts')<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script><script src="{{ asset('js/bus-stops.js') }}" defer></script>@endsection
