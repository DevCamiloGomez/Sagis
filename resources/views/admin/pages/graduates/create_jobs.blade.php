@extends('admin.layouts.app')

@section('title', 'Crear Datos Laborales')

@section('css')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('content-header')
    @include('dev.admin.partials.content-header', [
        'title' => 'Creación de Datos Laborales',
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
                'name' => 'Datos Generales',
                'route' => route('admin.graduates.show', $item->id),
                'isActive' => null,
            ],
            [
                'name' => $item->name,
                'route' => null,
                'isActive' => null,
            ],
            [
                'name' => 'Datos Laborales',
                'route' => null,
                'isActive' => 'active',
            ],
        ],
    ])
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-6">
                    <div class="card">
                        <div class="card-header  border-info">
                            <h3 class="card-title"><strong>Crear Datos Laborales del Graduado</strong> </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <small class="my-2 font-weight-bold float-right">Por favor llene todos los campos del formulario.</small>

                            @include('admin.pages.graduates.formCreateJobs')
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
@endsection

@section('js')
    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- Geonames -->
    <script src="{{ asset('js/geonames.js') }}"></script>
    <!-- App.js -->
    <script src="{{ asset('js/app.js') }}"></script>
@endsection

@section('custom_js')
    <script>
        // Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        });
    </script>
@endsection

