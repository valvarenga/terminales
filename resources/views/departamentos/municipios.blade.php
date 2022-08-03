@extends('layouts.plantilla')
@section('title','lista de municipios')

@section('content')

<div class="container-fluid">
  <div class="px-lg-5">

    <!-- titulo -->
    <div class="row py-5">
      <div class="col-lg-12 mx-auto text-center">
        <div class="text-warning p-2 shadow-sm rounded banner">
          <h2 class="display-5">Elija la Ciudad </h2>
        </div>
      </div>
    </div>
    <!-- End -->
    @if (is_null($municipios))
    <h1>No hay datos que mostrar</h1>    
@else


    <div class="row">
    @foreach ($municipios as $municipio)
      <!-- card Municipios -->
      <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
       
        <a href="{{route('departamento.terminales', [$departamento, $municipio->slug])}}" class="text-dark fw-bolder">
        <div class="bg-white rounded shadow-sm"><img src="{{url('/images/ocotal.jpg')}}" alt="" class="img-fluid card-img-top">
          <div class="mx-auto p-4 text-center" style="width: 300px;">
                 <h4>{{$municipio->nombre}} </h4>
                <!--<button type="button" class="btn btn-secondary">Ver</button>-->
           </div>
        </div>
            </a>
      </div>
      <!-- End -->
         @endforeach
            
        
    </div> <!-- fin de la clase row -->
    @endif
  </div> <!-- fin de la clase px-lg-5 -->
</div>   <!-- fin del container -->
@endsection








