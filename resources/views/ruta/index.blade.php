@extends('layouts.plantilla')

@section('title', 'Ruta')

@section('content')
<div class="card">
    <div class="card-header text-center">
        <h1>Bienvenido a la página de rutas</h1>
     </div>
</div>
<!-- Start Main Body Section -->
<div class="mainbody-section text-center">
    <div class="container">
        <div class="row">
      <div class="col-md-3">
          <div class="menu-item light-red">
                <a href="{{route('newdepartamento')}}">
                        <p>Crear Departamento</p>
                    </a>
            </div>
         <div class="menu-item blue">
                <a href="{{route('newmunicipio')}}">
                        <p>Crear Municipio</p>
                    </a>
            </div>
         <div class="menu-item green">
                    <a href="{{route('newterminal')}}">
                        <p> Crear Terminal </p>
                    </a>
             </div>

     </div>

            <div class="col-md-6">
              <!-- Start Carousel Section -->
                <div class="home-slider">
                    <div id="carousel-example-generic" class="carousel slide" data-bs-ride="carousel">
                        <!-- Indicators -->
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#carousel-example-generic" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                            <button type="button" data-bs-target="#carousel-example-generic" data-bs-slide-to="1" aria-label="Slide 2"></button>
                            <button type="button" data-bs-target="#carousel-example-generic" data-bs-slide-to="2" aria-label="Slide 3"></button>
                        </div>

                        <!-- Wrapper for slides -->
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="{{url('/images/inicio.png')}}" class="img-responsive" alt=""  > 
                            </div>
                            <div class="carousel-item">
                                <img src="{{url('/images/nica2.jpg')}}" class="img-responsive" alt=""  > 
                            </div>
                            <div class="carousel-item">
                                <img src="{{url('/images/nica3.jpg')}}" class="img-responsive" alt=""  > 
                            </div>

                        </div>

                    </div>
                </div>
                <!-- Start Carousel Section -->
            <hr/>
                <div class="row">
                    <div class="col-md-6">
                        <div class="menu-item color responsive">
                            <a href="{{route('show_terminal')}}">
                                <p> terminales </p>
                            </a>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="menu-item light-orange responsive-2">
                            <a href="{{route('municipio.show')}}">
                               <p>Listar municipios</p>
                            </a>
                        </div>
                    </div>

                </div>

            </div>

            <div class="col-md-3">
              <div class="menu-item light-red">
                    <a href="{{route('newbus')}}">
                        <p> Crear Autobus </p>
                    </a>
                </div>
              <div class="menu-item color">
                    <a href="{{route('departamentos.show')}}">
                        <p> departamentos </p>
                    </a>
                </div>
             <div class="menu-item blue">
                <a href="#">
                    <p> autobuses </p>
                </a>
            </div>

            </div>
        </div>
    </div>
</div>

@endsection