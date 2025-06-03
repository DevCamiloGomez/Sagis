@extends('layouts.app')

@section('content')
<form action="{{ route('store_jobs') }}" method="POST" >
    @csrf 


    <div class="form-group">
        <label class="form-label">Empresa:</label>
        <select name="company_id" class="form-control select2bs4" id="empresas">
            <option value="-1" disabled selected>Seleccione la empresa.</option>
            <option value="-2">No existe la empresa (Crearla).</option>
            @forelse ($companies as $company)
            <option value="{{ $company->id }}" >
                {{$company->name}}
            </option>
            @empty
            @endforelse
        </select>
    </div>
    @error('company_id')
    <small class="text-danger">{{ $message }}</small>
    @enderror
  


    <div class="form-group"  id="name_compamy">
        <label class="form-label">Nombre Empresa:</label>
        <input type="text" class="form-control " name="name"  id="name_compamy_input" required>
    </div>
    @error('name')
        <small class="text-danger">{{ $message }}</small>
    @enderror


    <div class="form-group"  id="email_company">
        <label class="form-label">Correo electrónico (Empresa):</label>
        <input type="email" class="form-control " name="email" id="email_company_input" required >
    </div>
    @error('email')
        <small class="text-danger">{{ $message }}</small>
    @enderror

    
    <div class="form-group" id="address_company">
        <label class="form-label">Dirección de la empresa:</label>
        <input type="text" class="form-control " name="address" id="address_company_input" required >
    </div>
    @error('address')
        <small class="text-danger">{{ $message }}</small>
    @enderror


    <div class="form-group" id="phone_company">
        <label class="form-label">Celular de la empresa:</label>
        <input type="text" class="form-control " name="phone"  id="phone_company_input" required>
    </div>
    @error('phone')
        <small class="text-danger">{{ $message }}</small>
    @enderror


    <div class="form-group">
        <label>Ubicación de la Empresa</label>
        <div class="custom-control custom-radio">
            <input type="radio" id="apiLocation" name="location_type" value="api" class="custom-control-input" checked>
            <label class="custom-control-label" for="apiLocation">Buscar por API</label>
        </div>
        <div class="custom-control custom-radio">
            <input type="radio" id="manualLocation" name="location_type" value="manual" class="custom-control-input">
            <label class="custom-control-label" for="manualLocation">Ingresar Manualmente</label>
        </div>
    </div>
    <div id="apiLocationCompany" style="display: block;">
        <div class="form-group">
            <label for="country">País</label>
            <select class="form-control" id="country" name="country">
                <option value="">Seleccione un país...</option>
            </select>
        </div>
        <div class="form-group">
            <label for="state">Estado/Departamento</label>
            <select class="form-control" id="state" name="state">
                <option value="">Seleccione un estado/departamento...</option>
            </select>
        </div>
        <div class="form-group">
            <label for="city">Ciudad</label>
            <select class="form-control" id="city" name="city">
                <option value="">Seleccione una ciudad...</option>
            </select>
        </div>
        <input type="hidden" id="company_place_id" name="company_place_id" value="{{ old('company_place_id') }}">
        <input type="hidden" id="city_name_company" name="city_name_company" value="{{ old('city_name_company') }}">
    </div>
    <div id="manualLocationCompany" style="display: none;">
        <div class="form-group">
            <label for="country_name">País</label>
            <input type="text" class="form-control @error('country_name') is-invalid @enderror" id="country_name" name="country_name" value="{{ old('country_name') }}">
            @error('country_name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group">
            <label for="state_name">Estado/Departamento</label>
            <input type="text" class="form-control @error('state_name') is-invalid @enderror" id="state_name" name="state_name" value="{{ old('state_name') }}">
            @error('state_name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group">
            <label for="city_name_manual">Ciudad</label>
            <input type="text" class="form-control @error('city_name_manual') is-invalid @enderror" id="city_name_manual" name="city_name_manual" value="{{ old('city_name_manual') }}">
            @error('city_name_manual')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>


    <div class="form-group">
        <label class="form-label">Salario:</label>
        <input type="text" class="form-control " name="salary" >
    </div>
    @error('salary')
        <small class="text-danger">{{ $message }}</small>
    @enderror

    


    <div class="form-group">
        <label class="form-label">¿Está actualmente trabajando? (SI O NO):</label>
        <select name="in_working" class="form-control select2bs4">
            <option value="-1" disabled selected>Seleccione SI o NO.</option>


            <option value="0">
                NO
            </option>

            <option value="1" >
                SI
            </option>

        </select>
    </div>
    @error('in_working')
    <small class="text-danger">{{ $message }}</small>
    @enderror
  


    <!-- Submit -->
    <div class="mt-4">
        <div class="btn-group" role="group" aria-label="Basic example">
            <button type="submit" class="btn btn-danger" >Guardar</button>
            <div class="ml-5">
                <a class="btn btn btn-warning " style="color:black;
                text-decoration: none;" href="{{ route('jobs') }}">Regresar</a>
            </div>
        </div>

    </div>
    <!-- ./Submit -->

</form>
@endsection

@section('js')
<script src="{{ asset('js/geonames.js') }}"></script>
<script>
$(function() {
    // Inicializar Select2 para el select de empresas
    $('#empresas').select2({
        placeholder: 'Seleccione una empresa...',
        allowClear: true
    });

    // Mostrar/ocultar campos de ubicación según la opción seleccionada
    function toggleLocationFields() {
        const locationType = $('input[name="location_type"]:checked').val();
        if (locationType === 'api') {
            $('#apiLocationCompany').show();
            $('#manualLocationCompany').hide();
            initializeSelect2('country', 'Seleccione un país...');
            initializeSelect2('state', 'Seleccione un estado/departamento...');
            initializeSelect2('city', 'Seleccione una ciudad...');
            loadCountries();
        } else {
            $('#apiLocationCompany').hide();
            $('#manualLocationCompany').show();
        }
    }

    // Ejecutar al cargar la página
    toggleLocationFields();

    // Cambiar campos al cambiar la opción
    $('input[name="location_type"]').on('change', function() {
        toggleLocationFields();
    });
});
</script>
@endsection
