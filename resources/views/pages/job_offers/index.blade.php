@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h2 class="text-center mb-5 font-weight-bold" style="color: #dd4b39;">Bolsa de Empleo - Ingeniería de
                Sistemas</h2>

            @if($jobOffers->count() > 0)
            <div class="row">
                @foreach($jobOffers as $offer)
                <div class="col-12 mb-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h3 class="h4 card-title text-primary font-weight-bold mb-0">{{ $offer->title }}</h3>
                                @if(!$offer->is_active)
                                <span class="badge badge-secondary p-2">Finalizada / Inactiva</span>
                                @endif
                            </div>
                            <h5 class="card-subtitle mb-3 text-muted">
                                <i class="fas fa-building mr-1"></i> {{ $offer->company_name }}
                            </h5>

                            <div class="mb-3">
                                @if($offer->location)
                                <span class="badge badge-light mr-2 p-2"><i
                                        class="fas fa-map-marker-alt text-danger"></i> {{ $offer->location }}</span>
                                @endif
                                @if($offer->type)
                                <span class="badge badge-light mr-2 p-2"><i class="fas fa-briefcase text-info"></i> {{
                                    $offer->type }}</span>
                                @endif
                                @if($offer->salary)
                                <span class="badge badge-light p-2"><i class="fas fa-money-bill-wave text-success"></i>
                                    {{ $offer->salary }}</span>
                                @endif
                            </div>

                            <div class="card-text mb-4 text-justify">
                                {!! Str::limit($offer->description, 200) !!}
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">Publicado el {{ $offer->created_at->format('d/m/Y') }}</small>
                                <a href="{{ route('job-offers.show', $offer) }}" class="btn btn-outline-danger">Ver
                                    Oferta Completa</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $jobOffers->links() }}
            </div>
            @else
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle mr-2"></i> No hay ofertas de empleo disponibles en este momento.
            </div>
            @endif
        </div>
    </div>
</div>
@endsection