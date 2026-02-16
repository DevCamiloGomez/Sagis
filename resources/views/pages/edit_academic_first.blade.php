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
    <section class="content mb-5 mt-5" >
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

                            <form action="{{ route('update_academic_first', $data_academic->id) }}" method="POST">
                                @csrf @method('PATCH')
                            
                            
                                <!-- Year -->
                                <div class="form-group">
                                    <label class="form-label">Año:</label>
                                    <input type="text" class="form-control " name="year" value="{{ $data_academic->year }}">
                                </div>
                                @error('year')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                                <!-- ./Year -->
                            
                            
                            
                            
                                <!-- Submit -->
                                <div class="mt-4">
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <button type="submit" class="btn btn-danger">Guardar</button>
                                        <div class="ml-5">
                                            <a class="btn btn btn-warning " style="color:black;
                                            text-decoration: none;" href="{{ route('academics') }}">Regresar</a>
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

