@extends('layouts.app')

@section('tittle', 'Galería')

@section('css')
<style>
    .gallery-carousel .carousel-inner {
        border-radius: 12px;
        overflow: hidden;
        background: #000;
    }
    .gallery-carousel .carousel-item img {
        width: 100%;
        height: 480px;
        object-fit: contain;
        background: #111;
    }
    .gallery-carousel .carousel-control-prev,
    .gallery-carousel .carousel-control-next {
        width: 50px;
        height: 50px;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(170, 25, 22, 0.8);
        border-radius: 50%;
        opacity: 1;
    }
    .gallery-carousel .carousel-control-prev { left: -25px; }
    .gallery-carousel .carousel-control-next { right: -25px; }
    .gallery-carousel .carousel-indicators li {
        background-color: #aa1916;
    }
    .gallery-carousel-counter {
        text-align: center;
        color: #777;
        font-size: 0.85rem;
        margin-top: 0.5rem;
    }
</style>
@endsection

@section('content')

    <div class="container content profile mt-4">
        <div class="row margin-bottom-30">
            <div id="informacionContent" class="mb-margin-bottom-30 shadow-wrapper w-100">

                <div class="col-md-12 col-sm-12 col-xs-12"
                    style="margin-bottom:20px; border-bottom: 3px solid #aa1916; padding: 0;">
                    <h1 class="pull-left" style="font-size:36px;">Galería</h1>
                </div>

                <h1 class="tituloinformacion">{{ $item->title }}</h1>
                <p class="fecha">{{ $item->date }}</p>

                <div class="description-content mb-4">
                    {!! $item->description !!}
                </div>

                {{-- Carrusel con todas las imágenes (portada + resto) --}}
                @php
                    $allImages = collect([$imageHeader])->merge($images);
                    $totalImages = $allImages->count();
                @endphp

                @if ($totalImages > 0)
                    <div class="d-flex justify-content-center mt-2 mb-5">
                        <div style="width: 100%; max-width: 720px; position: relative;">
                            <div id="galleryCarousel" class="carousel slide gallery-carousel" data-ride="carousel" data-interval="4000">

                                {{-- Indicadores de puntos --}}
                                @if ($totalImages > 1)
                                    <ol class="carousel-indicators">
                                        @foreach ($allImages as $idx => $img)
                                            <li data-target="#galleryCarousel"
                                                data-slide-to="{{ $idx }}"
                                                class="{{ $idx === 0 ? 'active' : '' }}"></li>
                                        @endforeach
                                    </ol>
                                @endif

                                {{-- Slides --}}
                                <div class="carousel-inner">
                                    @foreach ($allImages as $idx => $img)
                                        <div class="carousel-item {{ $idx === 0 ? 'active' : '' }}">
                                            <img src="{{ asset($img->fullAsset()) }}"
                                                 alt="Imagen {{ $idx + 1 }} de {{ $item->title }}">
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Controles de navegación --}}
                                @if ($totalImages > 1)
                                    <a class="carousel-control-prev" href="#galleryCarousel" role="button" data-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Anterior</span>
                                    </a>
                                    <a class="carousel-control-next" href="#galleryCarousel" role="button" data-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Siguiente</span>
                                    </a>
                                @endif
                            </div>

                            {{-- Contador de imágenes --}}
                            <p class="gallery-carousel-counter">
                                <span id="currentSlide">1</span> / {{ $totalImages }}
                            </p>
                        </div>
                    </div>
                @endif

                <div style="clear:both"></div>
                <div style="clear:both; min-height:30px;"></div>

            </div>
        </div>
    </div>

@section('js')
<script>
    // Actualizar contador al cambiar slide
    $('#galleryCarousel').on('slid.bs.carousel', function (e) {
        document.getElementById('currentSlide').textContent = e.to + 1;
    });
</script>
@endsection

@endsection
