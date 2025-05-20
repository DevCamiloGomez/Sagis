@extends('admin.layouts.app')

@section('title', 'Registro de Contenido Informativo')

@section('cargarJS')
    onload = "cargarPrincipal2()";
@endsection

@section('css')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <!-- Summernote -->
    <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">
@endsection

@section('content-header')
    @include('admin.partials.content-header', [
        'title' => 'Contenido Informativo',
        'items' => [
            [
                'name' => 'Inicio',
                'route' => route('admin.home'),
                'isActive' => null,
            ],
            [
                'name' => 'Publicaciones',
                'route' => route('admin.posts.index'),
                'isActive' => null,
            ],
            [
                'name' => 'Registro',
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
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Formulario de Registro</h4>
                        </div>
                        <div class="card-body">
                            <p>Por favor, llenar todos los datos de información.</p>
                            <small class="text-muted">Recuerda que acá se realiza el registro de la publicación, para una
                                información más
                                detallada debes realizarla dentro del perfil de la publicación.</small>
                            <hr>
                            @include('admin.pages.posts.form', [
                                'editMode' => false,
                            ])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('js')
    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/posts.js') }}"></script>
    <!-- Summernote -->
    <script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
@endsection

@section('custom_js')
<script>
    datePickerId.max = new Date().toISOString().split("T")[0];
</script>
<script>
    //Initialize Select2 Elements
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    });

    //Initialize Summernote
    $('#summernote').summernote({
        placeholder: 'Escriba el contenido aquí...',
        tabsize: 2,
        height: 300,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'picture']],
            ['view', ['fullscreen', 'help']]
        ]
    });
</script>
@endsection
