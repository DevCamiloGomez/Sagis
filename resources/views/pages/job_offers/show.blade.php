@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="mb-4">
                <a href="{{ route('job-offers.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Volver a la lista
                </a>
            </div>

            <div class="card shadow border-0">
                <div class="card-body p-5">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h1 class="card-title text-primary font-weight-bold mb-0">{{ $jobOffer->title }}</h1>
                        @if(!$jobOffer->is_active)
                        <span class="badge badge-secondary p-2">Esta oferta ha finalizado</span>
                        @endif
                    </div>
                    <h4 class="card-subtitle mb-4 text-muted border-bottom pb-3">
                        <i class="fas fa-building mr-1"></i> {{ $jobOffer->company_name }}
                    </h4>

                    <div class="row mb-4 bg-light p-3 rounded mx-0">
                        <div class="col-md-4 mb-2 mb-md-0">
                            <strong><i class="fas fa-map-marker-alt text-danger mr-1"></i> Ubicación:</strong><br>
                            {{ $jobOffer->location ?? 'No especificada' }}
                        </div>
                        <div class="col-md-4 mb-2 mb-md-0">
                            <strong><i class="fas fa-briefcase text-info mr-1"></i> Tipo:</strong><br>
                            {{ $jobOffer->type ?? 'No especificado' }}
                        </div>
                        <div class="col-md-4">
                            <strong><i class="fas fa-money-bill-wave text-success mr-1"></i> Salario:</strong><br>
                            {{ $jobOffer->salary ?? 'No especificado' }}
                        </div>
                    </div>

                    <div class="mb-5">
                        <h5 class="font-weight-bold mb-3 border-left pl-3"
                            style="border-width: 4px !important; border-color: #dd4b39 !important;">Descripción del
                            Cargo</h5>
                        <div class="text-justify">
                            {!! $jobOffer->description !!}
                        </div>
                    </div>

                    @if($jobOffer->contact_email)
                    <div class="alert alert-secondary p-4 rounded text-center">
                        <h5 class="font-weight-bold mb-3">¿Interesado en esta oferta?</h5>
                        <p class="mb-0">Envía tu hoja de vida a: <a href="mailto:{{ $jobOffer->contact_email }}"
                                class="font-weight-bold text-danger">{{ $jobOffer->contact_email }}</a></p>
                    </div>
                    @endif

                    <div class="text-right mt-4 text-muted">
                        <small>Publicado el {{ $jobOffer->created_at->format('d/m/Y') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection