@extends('layouts.plantilla')
@section('title','Departamentos')

@section('content')

<div class="container-fluid">
  <div class="px-lg-5">

    <!-- For demo purpose -->
    <div class="row py-5">
      <div class="col-lg-12 mx-auto text-center">
        <div class="text-warning p-5 shadow-sm rounded banner">
          <h1 class="display-4">Elija el Departamento para ver los horarios </h1>
        </div>
      </div>
    </div>
    <!-- End -->

    <div class="row">
      <!-- Gallery item -->
      <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
        <div class="bg-white rounded shadow-sm"><img src="{{url('/images/ocotal.jpg')}}" alt="" class="img-fluid card-img-top">
          <div class="mx-auto p-4" style="width: 200px;">
            <h4> <a href="#" class="text-dark fw-bolder">Nueva Segovia</a></h4>
              <button type="button" class="btn btn-secondary">Mirar los Horarios</button>
            
          </div>
        </div>
      </div>
      <!-- End -->
        
    </div> <!-- fin de la clase row -->
   
  </div> <!-- fin de la clase px-lg-5 -->
</div>   <!-- fin del container -->

@endsection




