@extends('dev.layouts.app')

@section('tittle', 'Inicio SAGIS')

@section('content')

    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"
                aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"
                aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"
                aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('img/ufps1.png') }}" alt="First slide" style="filter:brightness(45%)"
                    class="d-block w-100">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('img/ufps2.png') }}" style="filter:brightness(45%)" alt="Second slide"
                    class="d-block w-100">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('img/ufps3.png') }}" style="filter:brightness(45%)" alt="Third slide"
                    class="d-block w-100">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
            data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
            data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!--Sobre nosotros-->
    <section class="mt-4 mb-4">
        <div class="container">

            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <h1 class="text-center" style="color: #858585; font-weight: boild;">SOBRE NOSOTROS</h1>
                    <h1 class="text-center"> <b>Programa de Ingenieria de Sistemas </b> </h1>
                    <hr size="30" style="background-color: #b43432; ">
                    <p class="texto">El Programa de Ingeniería de Sistemas de la Universidad Francisco de Paula Santander
                        recibió licencia de funcionamiento emanada del ICFES según el Acuerdo 277 de 19 diciembre de 1985 y
                        fue aprobado mediante Resolución No. 001791 de Julio de 1991, emanada del Instituto Colombiano de
                        Fomento de la Educación Superior ICFES. Se encuentra debidamente registrado en el Sistema Nacional
                        de Información de dicha Entidad con el No. 120940030000055400111100.<br><br>
                        Según Acuerdo 045 de Julio 15 de 1996 del Consejo Superior de la UFPS se renovó la aprobación para
                        el Programa. En el año 2005 se obtuvo registro calificado por 7 Años. En el año 2012 se obtuvo la
                        renovación del registro calificado por 7 años, mediante la Resolución No. 10178 del 06 de
                        septiemebre de 2012 del M.E.N.
                    </p>
                </div>
                <div class="col-md-1"></div>
            </div>
        </div>

        </div>
    </section>

    <section class="contenido mt-5 mb-5">

        <div class="container">

            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <h1>CONTENIDO <p style="color: #b43432; display: inline;">RELEVANTE</p>
                    </h1>
                </div>
                <div class="col-md-1"></div>
            </div>
        </div>
    </section>


    <div class="container">
        <div class="row galeria">
            <div class="col-md-6  mb-5 colum">

                <a href="{{ asset('img/G1.jpg') }}" data-lightbox="galeriaS" data-title="Nombre foto"> <img
                        src="{{ asset('img/G1.jpg') }}" alt=""></a>
                <div class="titulo">Titulo imagen ejemplo </div>

            </div>

            <div class="col-md-6 mb-5 colum">
                <a href="{{ asset('img/G2.jpeg') }}" data-lightbox="galeriaS" data-title="Nombre foto"> <img
                        src="{{ asset('img/G2.jpeg') }}" alt=""></a>
                <div class="titulo">Titulo imagen ejemplo </div>
            </div>

            <div class="col-md-6 mb-5 colum">

                <a href="{{ asset('img/G3.jpg') }}" data-lightbox="galeriaS" data-title="Nombre foto"> <img
                        src="{{ asset('img/G3.jpg') }}" alt=""></a>
                <div class="titulo">Titulo imagen ejemplo </div>
            </div>

            <div class="col-md-6 mb-5 colum">

                <a href="{{ asset('img/G4.jpg') }}" data-lightbox="galeriaS" data-title="Nombre foto"> <img
                        src="{{ asset('img/G4.jpg') }}" alt=""></a>
                <div class="titulo">Titulo imagen ejemplo </div>
            </div>

        </div>


    </div>


    <section class="contenido mt-3 mb-5">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <a href=" {{ route('dev.gallery') }} "> <input class="boton2" id="botonServicios" type="button"
                    value="Ver todos"></a>

        </div>
        <div class="col-md-2"></div>
    </section>


@endsection
