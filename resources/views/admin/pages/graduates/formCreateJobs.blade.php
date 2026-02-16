@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Crear Datos Laborales</h3>
                </div>
                <div class="card-body">
                    <style>
                        .select2-container--default .select2-selection--single {
                            height: 45px !important;
                            padding: 8px;
                        }
                        .select2-container--default .select2-selection--single .select2-selection__rendered {
                            line-height: 28px !important;
                        }
                        .select2-container--default .select2-selection--single .select2-selection__arrow {
                            height: 43px !important;
                        }
                    </style>
                    <form action="{{ route('admin.graduates.store_jobs', $item->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="empresas">Empresa</label>
                            <select class="form-control @error('company_id') is-invalid @enderror" id="empresas" name="company_id" required>
                                <option value="-2" {{ old('company_id') == '-2' ? 'selected' : '' }}>No existe la empresa (Crearla)</option>
                                <option value="" disabled>Seleccione una empresa existente...</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" 
                                        data-name="{{ $company->name }}"
                                        data-email="{{ $company->email ?? '' }}"
                                        data-address="{{ $company->address ?? '' }}"
                                        data-phone="{{ $company->phone ?? '' }}"
                                        {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('company_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div id="companyFields" style="display: block;">
                            <div class="form-group">
                                <label for="name">Nombre de la Empresa</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" readonly>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email">Correo de la Empresa</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" readonly>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="address">Dirección de la Empresa</label>
                                <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address') }}" readonly>
                                @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="phone">Teléfono de la Empresa</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" readonly>
                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Ubicación de la Empresa</label>
                            <div class="custom-control custom-radio">
                                <input type="radio" id="apiLocation" name="location_type" value="api" class="custom-control-input" {{ old('location_type') == 'api' ? 'checked' : '' }}>
                                <label class="custom-control-label" for="apiLocation">Buscar por API</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio" id="manualLocation" name="location_type" value="manual" class="custom-control-input" {{ old('location_type') == 'manual' ? 'checked' : '' }}>
                                <label class="custom-control-label" for="manualLocation">Ingresar Manualmente</label>
                            </div>
                            @error('location_type')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div id="apiLocationCompany" style="display: none;">
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
                            <label for="salary">Salario (Pesos Colombianos - COP)</label>
                            <input type="text" class="form-control salary-input @error('salary') is-invalid @enderror" id="salary" name="salary" value="{{ old('salary') }}" required placeholder="Ej: 1.200.000">
                            @error('salary')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>¿Está trabajando actualmente?</label>
                            <div class="custom-control custom-radio">
                                <input type="radio" id="workingYes" name="in_working" value="1" class="custom-control-input" {{ old('in_working') == '1' ? 'checked' : '' }} required>
                                <label class="custom-control-label" for="workingYes">Sí</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio" id="workingNo" name="in_working" value="0" class="custom-control-input" {{ old('in_working') == '0' ? 'checked' : '' }} required>
                                <label class="custom-control-label" for="workingNo">No</label>
                            </div>
                            @error('in_working')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('js/geonames.js') }}"></script>
<script>
console.log('Script iniciado');

// Verificar si jQuery está disponible
if (typeof jQuery === 'undefined') {
    console.error('jQuery no está cargado');
} else {
    console.log('jQuery está cargado, versión:', jQuery.fn.jquery);
}

// Función para manejar los campos de la empresa
function toggleCompanyFields() {
    const value = $('#empresas').val();
    console.log('Valor seleccionado:', value);
    
    if (value === '-2') {
        // Crear nueva empresa
        $('#companyFields').show();
        $('#companyFields input').each(function() {
            $(this).prop('disabled', false)
                   .prop('readonly', false)
                   .prop('required', true)
                   .val('');
        });
    } else if (value !== '') {
        // Empresa existente seleccionada
        const selectedOption = $('#empresas option:selected');
        const companyData = {
            name: selectedOption.data('name') || '',
            email: selectedOption.data('email') || '',
            address: selectedOption.data('address') || '',
            phone: selectedOption.data('phone') || ''
        };
        
        console.log('Datos de la empresa:', companyData);
        
        // Mostrar y llenar los campos
        $('#companyFields').show();
        $('#name').val(companyData.name);
        $('#email').val(companyData.email);
        $('#address').val(companyData.address);
        $('#phone').val(companyData.phone);
        
        // Deshabilitar los campos
        $('#companyFields input').each(function() {
            $(this).prop('disabled', true)
                   .prop('readonly', true)
                   .prop('required', false);
        });
    } else {
        // Ninguna empresa seleccionada
        $('#companyFields').hide();
        $('#companyFields input').each(function() {
            $(this).prop('disabled', true)
                   .prop('readonly', true)
                   .prop('required', false)
                   .val('');
        });
    }
}

// Inicializar cuando el documento esté listo
$(function() {
    // Inicializar Select2 para el select de empresas
    $('#empresas').select2({
        placeholder: 'Seleccione una empresa...',
        allowClear: true
    });
    
    // Agregar el evento change al select
    $('#empresas').on('change', function() {
        toggleCompanyFields();
    });
    
    // Ejecutar la función inicialmente
    toggleCompanyFields();

    // Manejar el cambio entre API y manual para la ubicación
    $('input[name="location_type"]').on('change', function() {
        const apiLocation = $('#apiLocationCompany');
        const manualLocation = $('#manualLocationCompany');
        
        if (this.value === 'api') {
            apiLocation.show();
            manualLocation.hide();
            // Hacer los campos de API requeridos
            $('#country, #state, #city').prop('required', true);
            // Quitar required de los campos manuales
            $('#country_name, #state_name, #city_name_manual').prop('required', false);
            // Inicializar los campos de ubicación
            initializeSelect2('country', 'Seleccione un país...');
            initializeSelect2('state', 'Seleccione un estado/departamento...');
            initializeSelect2('city', 'Seleccione una ciudad...');
            // Cargar países
            loadCountries();
        } else {
            apiLocation.hide();
            manualLocation.show();
            // Quitar required de los campos de API
            $('#country, #state, #city').prop('required', false);
            // Hacer los campos manuales requeridos
            $('#country_name, #state_name, #city_name_manual').prop('required', true);
        }
    });

    // Eventos de cambio para los selects de ubicación
    $('#country').on('change', function() {
        const countryCode = $(this).val();
        loadStates(countryCode);
    });

    $('#state').on('change', function() {
        const countryCode = $('#country').val();
        const stateCode = $(this).val();
        loadCities(countryCode, stateCode);
    });

    $('#city').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const cityId = $(this).val();
        if (cityId) {
            $('#company_place_id').val(cityId);
            $('#city_name_company').val(selectedOption.data('name'));
        } else {
            $('#company_place_id').val('');
            $('#city_name_company').val('');
        }
    });
    
    // Validación del formulario
    $('form').on('submit', function(e) {
        const value = $('#empresas').val();
        const locationType = $('input[name="location_type"]:checked').val();
        
        if (value === '-2') {
            // Validar campos de nueva empresa
            const name = $('#name').val();
            const email = $('#email').val();
            const address = $('#address').val();
            const phone = $('#phone').val();

            if (!name || !email || !address || !phone) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Por favor complete todos los campos de la empresa'
                });
                return false;
            }
        }
        
        // Validar campos de ubicación según el tipo seleccionado
        if (locationType === 'api') {
            const country = $('#country').val();
            const state = $('#state').val();
            const city = $('#city').val();
            
            if (!country || !state || !city) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Por favor complete todos los campos de ubicación'
                });
                return false;
            }
        } else if (locationType === 'manual') {
            const country = $('#country_name').val();
            const state = $('#state_name').val();
            const city = $('#city_name_manual').val();
            
            if (!country || !state || !city) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Por favor complete todos los campos de ubicación'
                });
                return false;
            }
        }
    });
});

// Sobrescribir las funciones problemáticas de app.js
window.desactivarInputDatosLab = function() {
    console.log('Función desactivarInputDatosLab sobrescrita');
};

window.cargarPrincipalJobs = function() {
    console.log('Función cargarPrincipalJobs sobrescrita');
};
</script>
@endsection
