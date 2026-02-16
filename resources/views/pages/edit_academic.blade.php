@extends('layouts.app')

@section('title', 'Editar Datos Académicos')

@section('content-header')
    @include('partials.content-header', [
        'title' => 'Edición de Datos Académicos',
        'items' => [
            [
                'name' => 'Inicio',
                'route' => route('home'),
                'isActive' => null,
            ],

            [
                'name' => 'Datos Académicos',
                'route' => route('academics',),
                'isActive' => null,
            ],
            [
                'name' => $item->name,
                'route' => null,
                'isActive' => null,
            ],
            [
                'name' => 'Editar Dato Académico',
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
                            <h3 class="card-title"><strong>Cambiar Datos Académicos</strong> </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <small class="my-2 font-weight-bold float-right">Por favor llene todos los campos del formulario.</small>

                            @include('pages.formEditAcademic')
                    

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

