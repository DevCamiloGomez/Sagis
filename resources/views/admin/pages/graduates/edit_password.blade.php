@extends('admin.layouts.app')

@section('title', 'Editar Contraseña')

@section('content-header')
    @include('dev.admin.partials.content-header', [
        'title' => 'Edición de Graduados',
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
                'name' => $item->name,
                'route' => null,
                'isActive' => null,
            ],
            [
                'name' => 'Editar Contraseña',
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
                            <h3 class="card-title"><b>Cambiar Contraseña del Graduado</b> </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <small class="my-2 font-weight-bold float-right">Por favor llene todos los campos del
                                formulario.</small>

                            <form action="{{ route('admin.graduates.update_password', $item->id) }}" method="post">
                                @csrf
                                @method('PATCH')

                                <!-- New Password -->
                                <div class="form-group">
                                    <label for="password">Nueva Contraseña:</label>
                                    <input type="password" class="form-control form-control-sm" name="password"
                                        placeholder="Escriba una nueva contraseña...">
                                </div>
                                @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                                <!-- ./New Password -->

                                <!-- Repeat New Password -->
                                <div class="form-group">
                                    <label for="password">Repetir Nueva Contraseña:</label>
                                    <input type="password" class="form-control form-control-sm" name="repeat_password"
                                        placeholder="Escriba una nueva contraseña...">
                                </div>
                                @error('repeat_password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                                <!-- ./Repeat New Password -->

                                <!-- Submit -->
                                <div class="form-group">
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <button class="btn btn-sm btn-danger">Guardar</button>
                                        <div class="ml-5">
                                            <a class="btn btn-sm btn-warning " style="color:black;
                                            text-decoration: none;" href="{{ route('admin.graduates.index') }}">Regresar</a>
                                        </div>
                                    </div>
                                </div>

                            
                                   
                                  
                      
                                <!-- ./Submit -->

                            </form>


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

