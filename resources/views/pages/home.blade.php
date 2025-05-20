@extends('layouts.app')

@section('tittle', 'Inicio')

@section('content')
<style>
    .hero-section {
        position: relative;
        height: 600px;
        overflow: hidden;
    }
    .carousel-item {
        height: 600px;
        transition: transform .6s ease-in-out;
    }
    .carousel-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
    }
    .carousel-inner {
        height: 100%;
    }
    .carousel-fade .carousel-item {
        opacity: 0;
        transition: opacity .6s ease-in-out;
    }
    .carousel-fade .carousel-item.active {
        opacity: 1;
    }
    .carousel-fade .carousel-item-next.carousel-item-left,
    .carousel-fade .carousel-item-prev.carousel-item-right {
        opacity: 1;
    }
    .carousel-fade .carousel-item-next,
    .carousel-fade .carousel-item-prev,
    .carousel-fade .carousel-item.active {
        transform: translateX(0);
    }
    .carousel-caption {
        background: rgba(170, 25, 22, 0.85);
        padding: 2rem;
        border-radius: 10px;
        max-width: 800px;
        margin: 0 auto;
        bottom: 20%;
    }
    .carousel-caption h1 {
        font-size: 2.5rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: #ffffff;
    }
    .carousel-caption p {
        font-size: 1.2rem;
        color: #ffffff;
        margin-bottom: 1.5rem;
    }
    .btn-ufps {
        background-color: #aa1916;
        color: white;
        padding: 12px 30px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        border: 2px solid #aa1916;
    }
    .btn-ufps:hover {
        background-color: transparent;
        color: #ffffff;
        border: 2px solid #ffffff;
    }
    .stats-section {
        background-color: #f8f9fa;
        padding: 4rem 0;
    }
    .stat-card {
        background: white;
        border-radius: 10px;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-5px);
    }
    .stat-icon {
        font-size: 2.5rem;
        color: #aa1916;
        margin-bottom: 1rem;
    }
    .stat-number {
        font-size: 2rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 0.5rem;
    }
    .stat-title {
        color: #666;
        font-size: 1.1rem;
    }
    .features-section {
        padding: 4rem 0;
    }
    .feature-card {
        padding: 2rem;
        text-align: center;
        border-radius: 10px;
        background: white;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
    }
    .feature-icon {
        font-size: 2.5rem;
        color: #aa1916;
        margin-bottom: 1rem;
    }
    .feature-title {
        font-size: 1.5rem;
        color: #333;
        margin-bottom: 1rem;
    }
    .feature-text {
        color: #666;
        line-height: 1.6;
    }
    .gallery-section {
        padding: 4rem 0;
        background-color: #f8f9fa;
    }
    .gallery-item {
        position: relative;
        margin-bottom: 30px;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }
    .gallery-item:hover {
        transform: translateY(-5px);
    }
    .gallery-item img {
        width: 100%;
        height: 300px;
        object-fit: cover;
    }
    .gallery-caption {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(170, 25, 22, 0.85);
        color: white;
        padding: 1rem;
        text-align: center;
    }
    .latest-updates {
        padding: 4rem 0;
    }
    .update-card {
        background: white;
        border-radius: 10px;
        padding: 0;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .update-card:hover {
        transform: translateY(-5px);
    }
    .update-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 10px 10px 0 0;
    }
    .update-content-wrapper {
        padding: 1.5rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }
    .update-icon {
        font-size: 2rem;
        color: #aa1916;
        margin-bottom: 1rem;
    }
    .update-title {
        font-size: 1.25rem;
        color: #333;
        margin-bottom: 0.5rem;
        font-weight: 600;
    }
    .update-date {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }
    .update-content {
        color: #666;
        line-height: 1.6;
        margin-bottom: 1rem;
        flex-grow: 1;
    }
    .update-button {
        margin-top: auto;
    }
    .section-title {
        color: #aa1916;
        font-size: 2rem;
        font-weight: 600;
        margin-bottom: 2rem;
        text-align: center;
    }
</style>

<div class="hero-section">
    <div id="carouselExampleIndicators" class="carousel slide carousel-fade" data-bs-ride="carousel">
        <div class="carousel-indicators">
            @foreach($mainCarousel as $key => $image)
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $key }}" class="{{ $key === 0 ? 'active' : '' }}" aria-current="true" aria-label="Slide {{ $key + 1 }}"></button>
            @endforeach
        </div>
        <div class="carousel-inner">
            @foreach($mainCarousel as $key => $image)
            <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                <img src="{{ $image->fullAsset() }}" class="d-block w-100" alt="{{ $image->title }}">
            </div>
            @endforeach
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

<section class="gallery-section">
    <div class="container">
        <h2 class="section-title">Galería UFPS</h2>
        <div class="row">
            @foreach($sectionCarousel as $image)
            <div class="col-md-4 mb-4">
                <div class="gallery-item">
                    <img src="{{ $image->fullAsset() }}" alt="{{ $image->title }}" class="img-fluid">
                    <div class="gallery-caption">
                        <h5>{{ $image->title }}</h5>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="latest-updates">
    <div class="container">
        <h2 class="section-title">Últimas Actualizaciones</h2>
        <div class="row">
            <div class="col-md-6 col-lg-3">
                <div class="update-card">
                    @if($lastNotice && $lastNotice->hasImages())
                        <img src="{{ $lastNotice->imageHeader()->fullAsset() }}" alt="{{ $lastNotice->title }}" class="update-image">
                    @endif
                    <div class="update-content-wrapper">
                        <div class="update-icon">
                            <i class="fas fa-newspaper"></i>
                        </div>
                        <h3 class="update-title">Última Noticia</h3>
                        @if($lastNotice)
                            <div class="update-date">{{ $lastNotice->created_at->format('d \d\e F, Y') }}</div>
                            <p class="update-content">
                                {{ Str::limit($lastNotice->title, 100) }}
                            </p>
                            <div class="update-button">
                                <a href="{{ route('notices.show', $lastNotice->id) }}" class="btn btn-sm btn-outline-danger">Leer más</a>
                            </div>
                        @else
                            <p class="update-content">No hay noticias disponibles</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="update-card">
                    @if($lastEvent && $lastEvent->hasImages())
                        <img src="{{ $lastEvent->imageHeader()->fullAsset() }}" alt="{{ $lastEvent->title }}" class="update-image">
                    @endif
                    <div class="update-content-wrapper">
                        <div class="update-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <h3 class="update-title">Próximo Evento</h3>
                        @if($lastEvent)
                            <div class="update-date">{{ $lastEvent->created_at->format('d \d\e F, Y') }}</div>
                            <p class="update-content">
                                {{ Str::limit($lastEvent->title, 100) }}
                            </p>
                            <div class="update-button">
                                <a href="{{ route('events.show', $lastEvent->id) }}" class="btn btn-sm btn-outline-danger">Ver detalles</a>
                            </div>
                        @else
                            <p class="update-content">No hay eventos disponibles</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="update-card">
                    @if($lastCourse && $lastCourse->hasImages())
                        <img src="{{ $lastCourse->imageHeader()->fullAsset() }}" alt="{{ $lastCourse->title }}" class="update-image">
                    @endif
                    <div class="update-content-wrapper">
                        <div class="update-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <h3 class="update-title">Nuevo Curso</h3>
                        @if($lastCourse)
                            <div class="update-date">{{ $lastCourse->created_at->format('d \d\e F, Y') }}</div>
                            <p class="update-content">
                                {{ Str::limit($lastCourse->title, 100) }}
                            </p>
                            <div class="update-button">
                                <a href="{{ route('courses.show', $lastCourse->id) }}" class="btn btn-sm btn-outline-danger">Ver curso</a>
                            </div>
                        @else
                            <p class="update-content">No hay cursos disponibles</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="update-card">
                    @if($lastVideo && $lastVideo->hasVideos())
                        <div class="update-image" style="position: relative;">
                            <img src="https://img.youtube.com/vi/{{ $lastVideo->videoHeader()->asset_url }}/maxresdefault.jpg" alt="{{ $lastVideo->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                                <i class="fas fa-play-circle" style="font-size: 3rem; color: #aa1916;"></i>
                            </div>
                        </div>
                    @endif
                    <div class="update-content-wrapper">
                        <div class="update-icon">
                            <i class="fas fa-video"></i>
                        </div>
                        <h3 class="update-title">Último Video</h3>
                        @if($lastVideo)
                            <div class="update-date">{{ $lastVideo->created_at->format('d \d\e F, Y') }}</div>
                            <p class="update-content">
                                {{ Str::limit($lastVideo->title, 100) }}
                            </p>
                            <div class="update-button">
                                <a href="{{ route('videos.show', $lastVideo->id) }}" class="btn btn-sm btn-outline-danger">Ver video</a>
                            </div>
                        @else
                            <p class="update-content">No hay videos disponibles</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
