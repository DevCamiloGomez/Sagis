@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Editar Imagen del Carrusel</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.carousel.index') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
                <form action="{{ route('admin.carousel.update', $carousel) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="title">Título</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title', $carousel->title) }}" 
                                   placeholder="Ingrese el título de la imagen">
                            @error('title')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="type">Tipo de Carrusel</label>
                            <select class="form-control @error('type') is-invalid @enderror" 
                                    id="type" name="type">
                                <option value="main" {{ old('type', $carousel->type) == 'main' ? 'selected' : '' }}>Carrusel Principal</option>
                                <option value="section" {{ old('type', $carousel->type) == 'section' ? 'selected' : '' }}>Carrusel de Sección</option>
                            </select>
                            @error('type')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Imagen Actual</label>
                            <div class="mb-2">
                                <img src="{{ $carousel->fullAsset() }}" 
                                     alt="{{ $carousel->title }}" 
                                     class="img-thumbnail" 
                                     style="max-height: 200px;">
                            </div>
                            <label for="image">Cambiar Imagen</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('image') is-invalid @enderror" 
                                       id="image" name="image" accept="image/*">
                                <label class="custom-file-label" for="image">Seleccionar nueva imagen</label>
                            </div>
                            <small class="form-text text-muted">
                                Formatos permitidos: JPG, JPEG, PNG, GIF. Tamaño máximo: 2MB. Dejar en blanco para mantener la imagen actual.
                            </small>
                            @error('image')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="order">Orden</label>
                            <input type="number" class="form-control @error('order') is-invalid @enderror" 
                                   id="order" name="order" value="{{ old('order', $carousel->order) }}" min="0">
                            <small class="form-text text-muted">
                                El orden determina la posición de la imagen en el carrusel
                            </small>
                            @error('order')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" 
                                       id="is_active" name="is_active" value="1" 
                                       {{ old('is_active', $carousel->is_active) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Activo</label>
                            </div>
                            <small class="form-text text-muted">
                                Las imágenes inactivas no se mostrarán en el carrusel
                            </small>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>
<script>
$(document).ready(function () {
    bsCustomFileInput.init();
});
</script>
@endpush 