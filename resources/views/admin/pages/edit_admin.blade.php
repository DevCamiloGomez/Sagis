@extends('admin.layouts.app')

@section('title', 'Editar Datos Personales')

@section('content-header')
    @include('partials.content-header', [
        'title' => 'Editar Datos Personales',
        'items' => [
            [
                'name' => 'Inicio',
                'route' => route('home'),
                'isActive' => null,
            ],
            [
                'name' => 'Ajustes',
                'route' => route('admin.settings'),
                'isActive' => null,
            ],
            [
                'name' => $item->name,
                'route' => null,
                'isActive' => null,
            ],
            [
                'name' => 'Editar Datos personales',
                'route' => null,
                'isActive' => 'active',
            ],
        ],
    ])
@endsection

@section('css')
<style>
    body {
        background-color: #f4f6f9;
    }

    </style>


@endsection

@section('content')
  
<!-- Main content -->
    <section class="content">
        <div class="container-fluid col-10 my-4">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-6">
                    <div class="card">
                        <div class="card-header  border-info">
                            <h3 class="card-title"><strong>Datos personales</strong> </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <small class="my-2 font-weight-bold float-right">Por favor llene todos los campos del
                                formulario.</small>

                                @include('admin.pages.formEdit', ['editMode' => false])

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
