@extends('admin.layouts.app')

@section('title', 'Registrar Graduado')

@section('content-header')
    @include('dev.admin.partials.content-header', [
        'title' => 'Registro de Graduados',
        'items' => [
            [
                'name' => 'Inicio',
                'route' => route('admin.home'),
                'isActive' => null,
            ],
            [
                'name' => 'Graduados',
                'route' => route('admin.graduates.create'),
                'isActive' => null,
            ],
            [
                'name' => 'Registrar',
                'route' => null,
                'isActive' => 'active',
            ],
        ],
    ])
@endsection

@section('css')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('content')

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-6">
                    <div class="card">
                        <div class="card-header  border-info">
                            <h3 class="card-title"><b>Datos personales</b> </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <small class="my-2 font-weight-bold float-right">Por favor llene todos los campos del
                                formulario.</small>

                            @include('admin.pages.graduates.form', ['editMode' => false])

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
@endsection

@section('custom_js')
    <script>
        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })
    </script>
@endsection

