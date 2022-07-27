@extends('layouts.plantilla')

@section('title', 'Home')

@section('content')
<div class="container-fluid bg-light.bg-gradient" id="about">
        <div class="row">
          <div class="col-md-6 shadow-lg p-3 mb-5 bg-body rounded">
              <video class="img-fluid" autoplay loop muted>
        <source src="{{url('/images/video.mp4')}}" type="video/mp4" />
      </video>
    
    
          </div>
          <div class="col-md-6">
</br>
           <h1 class="text-success"> Horarios de las terminales de buses de Nicaragua </h1>
           <p class="fst-italic fs-3">
 Conoce fácilmente todos los horarios de los buses de las terminales de 
Nicaragua y ¡planea con exactitud la hora de tu próximo viaje!
            </p>

            <div class="row">
              <!--action="{{route('buscar.index')}}"-->
            <form class="row g-3" >
  <div class="col">
    <label class="text-info fs-3">Origen</label>
    <input type="text" class="form-control" placeholder="Escriba la ciudad de origen" aria-label="origen" required>
  </div>
  <div class="col">
  <label class="text-info fs-3">Destino</label>
    <input type="text" class="form-control" placeholder="Escriba la ciudad de destino" aria-label="destino" required>
  </div>
  <div class="col-12">
   <!-- <button class="btn btn-info ">Buscar</button> -->
   <a href="{{route('buscar.index')}}">buscar</a>
  </div>
  </form>
</div>

  <!--seccion de anuncios -->
</br>
<div class="row bg-primary">
    Anuncios
</div>
</br>
<div class="container ">
<div class="row ">
    <div class="col-md-7 offset-md-3">
    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
  </div>
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="{{url('/images/1.jpg')}}" class="d-block w-100" alt="Image"  >
    </div>
    <div class="carousel-item">
    <img src="{{url('/images/2.jpg')}}" class="d-block w-100" alt="Image">
    </div>
    <div class="carousel-item">
    <img src="{{url('/images/3.jpg')}}" class="d-block w-100" alt="Image" >
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>
    </div>
</div>

</div> <!-- fin de container anuncios -->
</br>       
    </div> <!--fin del container about -->
   

<div class="row bg-success fs-4 ">
    <p class="text-center text-white">Los Nicaraguenses opinan... !</p>
</div>
<!-- seccion de comentarios de facebook-->
<div class="fb-comments" data-href="http://127.0.0.1:8000" data-width="100%" data-numposts="3"></div>



@endsection


