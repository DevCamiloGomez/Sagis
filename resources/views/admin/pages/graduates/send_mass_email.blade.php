@extends('admin.layouts.app')

@section('title', 'Enviar Correo Masivo')

@section('css')
    <!-- Summernote -->
    <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">
    <style>
        .email-form-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .card {
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .note-editor {
            margin-bottom: 20px;
        }
        .email-template {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 20px;
            margin-bottom: 20px;
            background: #fff;
        }
        .email-header, .email-footer {
            color: #666;
            font-style: italic;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .email-content {
            min-height: 200px;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 15px;
            margin: 15px 0;
        }
        .template-label {
            font-weight: bold;
            color: #666;
            margin-bottom: 5px;
        }
    </style>
@endsection

@section('content-header')
    @include('admin.partials.content-header', [
        'title' => 'Enviar Correo Masivo',
        'items' => [
            [
                'name' => 'Inicio',
                'route' => route('admin.home'),
                'isActive' => null,
            ],
            [
                'name' => 'Graduados',
                'route' => route('admin.graduates.index'),
                'isActive' => null,
            ],
            [
                'name' => 'Enviar Correo Masivo',
                'route' => null,
                'isActive' => 'active',
            ],
        ],
    ])
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h4>Formulario de Correo Masivo</h4>
                        </div>
                        <div class="card-body">
                            <p>Complete los campos para enviar un correo masivo a todos los graduados.</p>
                            <hr>
                            <form action="{{ route('admin.graduates.send-mass-email') }}" method="POST">
                                @csrf
                                
                                <!-- Asunto -->
                                <div class="form-group">
                                    <label>Asunto del Correo:</label>
                                    <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror" 
                                           value="{{ old('subject') }}" required>
                                    @error('subject')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Contenido -->
                                <div class="form-group">
                                    <label>Contenido del Correo:</label>
                                    <textarea name="message" id="summernote" class="form-control @error('message') is-invalid @enderror" required>{{ old('message') }}</textarea>
                                    @error('message')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Submit -->
                                <div class="form-group">
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-paper-plane mr-1"></i> Enviar Correo
                                    </button>
                                    <a href="{{ route('admin.graduates.index') }}" class="btn btn-secondary ml-2">
                                        <i class="fas fa-arrow-left mr-1"></i> Regresar
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <!-- Summernote -->
    <script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
@endsection

@section('custom_js')
<script>
    $(function () {
        $('#summernote').summernote({
            placeholder: 'Escriba el contenido del correo aquí...',
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
            callbacks: {
                onImageUpload: function(files) {
                    // Deshabilitar la subida de imágenes
                    alert('La subida de imágenes está deshabilitada para correos masivos.');
                }
            }
        });
    });
</script>
@endsection 