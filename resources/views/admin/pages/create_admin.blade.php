@extends('admin.layouts.app')

@section('title', 'Registrar Admin')

@section('content-header')
@include('dev.admin.partials.content-header', [
'title' => 'Registro de Administradores',
'items' => [
[
'name' => 'Inicio',
'route' => route('admin.home'),
'isActive' => null,
],
[
'name' => 'Ajustes',
'route' => route('admin.settings'),
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
                        <h3 class="card-title"><strong>Datos personales</strong> </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <small class="my-2 font-weight-bold float-right">Por favor llene todos los campos del
                            formulario.</small>

                        <!-- Form -->
                        <form action="{{ route('admin.settings.store_admin') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <!-- Name -->
                            <div class="form-group">
                                <label class="form-label">Nombres:</label>
                                <input type="text" class="form-control " name="name" value="{{ old('name') }}">
                            </div>
                            @error('name')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <!-- ./Name -->

                            <!-- Lastname -->
                            <div class="form-group">
                                <label class="form-label">Apellidos:</label>
                                <input type="text" class="form-control " name="lastname" value="{{ old('lastname') }}">
                            </div>
                            @error('lastname')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <!-- ./Lastname -->

                            <!-- DocumentType -->
                            <div class="form-group">
                                <label>Tipo de Documento:</label>
                                <select name="document_type_id" class="form-control select2bs4">
                                    <option value="-1">Seleccione un tipo de documento..</option>
                                    @forelse ($documentTypes as $documentType)
                                    <option value="{{ $documentType->id }}" {{ isSelectedOld(old('document_type_id'),
                                        $documentType->id) }}>{{ $documentType->name }}
                                    </option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                            @error('document_type_id')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <!-- ./DocumentType -->

                            <!-- Document -->
                            <div class="form-group">
                                <label class="form-label">Documento:</label>
                                <input type="text" class="form-control " name="document" value="{{ old('document') }}">
                            </div>
                            @error('document')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <!-- ./Document -->

                            <!-- Birthdate -->
                            <div class="form-group">
                                <label>Fecha de Nacimiento</label>
                                <input type="date" class="form-control" name="birthdate" value="{{ old('birthdate') }}">
                            </div>
                            @error('birthdate')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <!-- ./Birthdate -->

                            <!-- BirthdatePlaceId -->
                            <div class="form-group">
                                <label>Lugar de Nacimiento:</label>
                                <select name="birthdate_place_id" value="{{ old('birthdate_place_id') }}"
                                    class="form-control select2bs4">
                                    <option value="-1">Seleccione el lugar de Nacimiento..</option>
                                    @forelse ($cities as $city)
                                    <option value="{{ $city->id }}" {{ isSelectedOld(old('birthdate_place_id'), $city->
                                        id) }}>
                                        {{ 'País: ' . $city->state->country->name . ' Departamento: ' .
                                        $city->state->name . ' Ciudad: ' . $city->name }}
                                    </option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                            @error('birthdate_place_id')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <!-- ./BirthdatePlaceId -->

                            <!-- Address -->
                            <div class="form-group">
                                <label class="form-label">Dirección de Residencia:</label>
                                <input type="text" class="form-control " name="address" value="{{ old('address') }}">
                            </div>
                            @error('address')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <!-- ./Address -->

                            <!-- Phone -->
                            <div class="form-group">
                                <label class="form-label">Celular Personal:</label>
                                <input type="text" class="form-control " name="phone" value="{{ old('phone') }}">
                            </div>
                            @error('phone')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <!-- ./Phone -->

                            <!-- Telephone -->
                            <div class="form-group">
                                <label class="form-label">Teléfono(Opcional):</label>
                                <input type="text" class="form-control " name="telephone"
                                    value="{{ old('telephone') }}">
                            </div>
                            @error('telephone')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <!-- ./Telephone -->


                            <!-- CompanyEmail -->
                            <div class="form-group">
                                <label class="form-label">Correo Institucional(@ufps):</label>
                                <input type="email" class="form-control " name="company_email"
                                    value="{{ old('company_email') }}" pattern=".+@ufps.edu.co" size="30">
                            </div>
                            @error('company_email')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <!-- ./CompanyEmail -->

                            <!-- Email -->
                            <div class="form-group">
                                <label class="form-label">Correo Personal:</label>
                                <input type="email" class="form-control " name="email" value="{{ old('email') }}"
                                    size="30">
                            </div>
                            @error('email')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <!-- ./Email -->

                            <!-- File -->
                            <div class="form-group">
                                <label for="exampleFormControlFile1">Foto de Perfil</label>
                                <input type="file" class="form-control-file" name="image" accept="image/*">
                            </div>
                            @error('image')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <!-- ./File -->

                            <!-- Submit -->
                            <div class="mt-4">
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <button type="submit" class="btn btn-danger">Guardar</button>
                                    <div class="ml-5">
                                        <a class="btn btn btn-warning " style="color:black;
                                        text-decoration: none;" href="{{ route('admin.settings') }}">Regresar</a>
                                    </div>
                                </div>

                            </div>
                            <!-- ./Submit -->


                        </form>
                        <!-- ./Form -->

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
