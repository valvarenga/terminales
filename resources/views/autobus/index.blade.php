@extends('layouts.plantilla')
@section('title', $copyingService ? 'Nueva salida' : 'Nuevo autobús')
@section('estilos')<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">@endsection
@section('content')
<div class="container py-4"><div class="card"><div class="card-header text-center"><h1 class="h3">{{ $copyingService ? 'Registrar otra salida' : 'Registrar servicio de autobús' }}</h1></div><div class="card-body">
@if($copyingService)<div class="alert alert-info"><strong>Recorrido reutilizado.</strong> Se copiaron el bus, la terminal, las paradas y las tarifas de <strong>{{ $autobus->nombre }}</strong>. Cambia los horarios de paso para esta nueva salida; también puedes cambiar el nombre o la placa si la realiza otro bus.</div>@endif
<form action="{{ route('autobus') }}" method="POST">@csrf @include('autobus._form')<button type="submit" class="btn btn-info btn-lg mt-4">{{ $copyingService ? 'Guardar nueva salida' : 'Guardar servicio' }}</button></form></div></div></div>
@endsection
@section('scripts')<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script><script src="{{ asset('js/bus-stops.js') }}" defer></script>@endsection
