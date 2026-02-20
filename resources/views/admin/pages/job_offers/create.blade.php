@extends('admin.layouts.app')

@section('title', isset($jobOffer) ? 'Editar Oferta' : 'Nueva Oferta')

@section('content-header')
@include('admin.partials.content-header', [
'title' => isset($jobOffer) ? 'Editar Oferta de Empleo' : 'Crear Nueva Oferta de Empleo',
'items' => [
[
'name' => 'Inicio',
'route' => route('admin.home'),
'isActive' => null,
],
[
'name' => 'Ofertas de Empleo',
'route' => route('admin.job-offers.index'),
'isActive' => null,
],
[
'name' => isset($jobOffer) ? 'Editar' : 'Crear',
'route' => null,
'isActive' => 'active',
],
],
])
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ isset($jobOffer) ? 'Formulario de Edición' : 'Formulario de Creación' }}</h3>
            </div>
            <form
                action="{{ isset($jobOffer) ? route('admin.job-offers.update', $jobOffer) : route('admin.job-offers.store') }}"
                method="POST">
                @csrf
                @if(isset($jobOffer))
                @method('PUT')
                @endif

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">Título del Cargo <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                                    name="title" value="{{ old('title', $jobOffer->title ?? '') }}" required>
                                @error('title')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="company_name">Nombre de la Empresa <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('company_name') is-invalid @enderror"
                                    id="company_name" name="company_name"
                                    value="{{ old('company_name', $jobOffer->company_name ?? '') }}" required>
                                @error('company_name')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="location">Ubicación</label>
                                <input type="text" class="form-control @error('location') is-invalid @enderror"
                                    id="location" name="location"
                                    value="{{ old('location', $jobOffer->location ?? '') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="salary">Salario</label>
                                <input type="text" class="form-control @error('salary') is-invalid @enderror"
                                    id="salary" name="salary" value="{{ old('salary', $jobOffer->salary ?? '') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="type">Tipo de Contrato</label>
                                <select class="form-control" name="type" id="type">
                                    <option value="">Seleccionar...</option>
                                    <option value="Tiempo Completo" {{ (old('type', $jobOffer->type ?? '') == 'Tiempo
                                        Completo') ? 'selected' : '' }}>Tiempo Completo</option>
                                    <option value="Medio Tiempo" {{ (old('type', $jobOffer->type ?? '') == 'Medio
                                        Tiempo') ? 'selected' : '' }}>Medio Tiempo</option>
                                    <option value="Remoto" {{ (old('type', $jobOffer->type ?? '') == 'Remoto') ?
                                        'selected' : '' }}>Remoto</option>
                                    <option value="Híbrido" {{ (old('type', $jobOffer->type ?? '') == 'Híbrido') ?
                                        'selected' : '' }}>Híbrido</option>
                                    <option value="Prácticas" {{ (old('type', $jobOffer->type ?? '') == 'Prácticas') ?
                                        'selected' : '' }}>Prácticas</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="contact_email">Email de Contacto</label>
                        <input type="email" class="form-control @error('contact_email') is-invalid @enderror"
                            id="contact_email" name="contact_email"
                            value="{{ old('contact_email', $jobOffer->contact_email ?? '') }}">
                    </div>

                    <div class="form-group">
                        <label for="description">Descripción de la Oferta <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                            name="description" rows="5"
                            required>{{ old('description', $jobOffer->description ?? '') }}</textarea>
                        @error('description')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active"
                                value="1" {{ old('is_active', $jobOffer->is_active ?? true) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_active">Oferta Activa</label>
                        </div>
                    </div>

                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <a href="{{ route('admin.job-offers.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('css')
<!-- Summernote -->
<link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">
@endsection

@section('js')
<!-- Summernote -->
<script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
<script>
    $(function () {
        $('#description').summernote({
            placeholder: 'Escriba la descripción de la oferta aquí...',
            tabsize: 2,
            height: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'picture']],
                ['view', ['fullscreen', 'help']]
            ],
            language: 'es-ES'
        });
    });
</script>
@endsection