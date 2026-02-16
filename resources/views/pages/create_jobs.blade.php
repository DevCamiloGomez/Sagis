@extends('layouts.app')

@section('title', 'Crear Datos Laborales')

@section('cargarJS')

    
        onload = "cargarPrincipalJobs()";
  

@endsection

@section('content-header')
    @include('partials.content-header', [
        'title' => 'Creación de Datos Laborales',
        'items' => [
            [
                'name' => 'Inicio',
                'route' => route('home'),
                'isActive' => null,
            ],
            [
                'name' => 'Datos Laborales',
                'route' => route('jobs'),
                'isActive' => null,
            ],
            [
                'name' => $item->name,
                'route' => null,
                'isActive' => null,
            ],
            [
                'name' => 'Crear Dato Laboral',
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
                            <h3 class="card-title"><strong>Crear Datos Laborales</strong> </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <small class="my-2 font-weight-bold float-right">Por favor llene todos los campos del formulario.</small>

                            @include('pages.formCreateJobs')
                    

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
    <script src="{{ asset('js/app.js') }}"></script>
@endsection

