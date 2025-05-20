// Cache para países y estados
let countriesCache = null;
let statesCache = {};

// Función para mostrar errores
function showError(message, details = '') {
    console.error(message, details);
    // Aquí podrías agregar una notificación visual si lo deseas
}

// Verificar estado de la cuenta Geonames
async function checkAccountStatus() {
    try {
        const response = await fetch('/api/geonames/status');
        const data = await response.json();
        if (!data.status) {
            console.error('Error en la cuenta de Geonames:', data.message);
            showError('Error en la cuenta de Geonames. Por favor contacte al administrador.');
            return false;
        }
        return true;
    } catch (error) {
        console.error('Error al verificar cuenta Geonames:', error);
        showError('Error al verificar la cuenta de Geonames. Por favor intente más tarde.');
        return false;
    }
}

// Inicializar Select2
function initializeSelect2(elementId, placeholder) {
    $(`#${elementId}`).select2({
        theme: 'bootstrap4',
        placeholder: placeholder,
        allowClear: true,
        width: '100%'
    });
}

// Habilitar/deshabilitar select
function enableSelect(elementId) {
    $(`#${elementId}`).prop('disabled', false).trigger('change');
}

function disableSelect(elementId) {
    $(`#${elementId}`).prop('disabled', true).trigger('change');
}

// Cargar países
async function loadCountries() {
    try {
        if (!countriesCache) {
            const response = await fetch('/api/geonames/countries');
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const data = await response.json();
            
            if (data.error) {
                throw new Error(data.error);
            }
            
            if (!data.geonames) {
                throw new Error('No se encontraron países en la respuesta');
            }
            
            countriesCache = data.geonames;
        }

        const countrySelect = $('#country');
        countrySelect.empty().append('<option value="">Seleccione un país...</option>');
        
        countriesCache.sort((a, b) => a.countryName.localeCompare(b.countryName));
        countriesCache.forEach(country => {
            countrySelect.append(`<option value="${country.countryCode}">${country.countryName}</option>`);
        });

        enableSelect('country');
    } catch (error) {
        console.error('Error al cargar países:', error);
        showError('Error al cargar la lista de países. Por favor intente más tarde.');
        disableSelect('country');
    }
}

// Cargar estados
async function loadStates(countryCode) {
    try {
        if (!countryCode) {
            $('#state').empty().append('<option value="">Seleccione un estado/departamento...</option>').prop('disabled', true).trigger('change');
            $('#city').empty().append('<option value="">Seleccione una ciudad...</option>').prop('disabled', true).trigger('change');
            return;
        }

        if (!statesCache[countryCode]) {
            const response = await fetch(`/api/geonames/states?countryCode=${countryCode}`);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const data = await response.json();
            
            if (data.error) {
                throw new Error(data.error);
            }
            
            if (!data.geonames) {
                throw new Error('No se encontraron estados en la respuesta');
            }
            
            statesCache[countryCode] = data.geonames;
        }

        const stateSelect = $('#state');
        stateSelect.empty().append('<option value="">Seleccione un estado/departamento...</option>');
        
        statesCache[countryCode].sort((a, b) => a.adminName1.localeCompare(b.adminName1));
        statesCache[countryCode].forEach(state => {
            stateSelect.append(`<option value="${state.adminCode1}">${state.adminName1}</option>`);
        });

        enableSelect('state');
        $('#city').empty().append('<option value="">Seleccione una ciudad...</option>').prop('disabled', true).trigger('change');
    } catch (error) {
        console.error('Error al cargar estados:', error);
        showError('Error al cargar la lista de estados. Por favor intente más tarde.');
        disableSelect('state');
        disableSelect('city');
    }
}

// Cargar ciudades
async function loadCities(countryCode, stateCode) {
    try {
        if (!countryCode || !stateCode) {
            $('#city').empty().append('<option value="">Seleccione una ciudad...</option>').prop('disabled', true).trigger('change');
            return;
        }

        const response = await fetch(`/api/geonames/cities?countryCode=${countryCode}&stateCode=${stateCode}`);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.json();
        
        if (data.error) {
            throw new Error(data.error);
        }
        
        if (!data.geonames) {
            throw new Error('No se encontraron ciudades en la respuesta');
        }

        const citySelect = $('#city');
        citySelect.empty().append('<option value="">Seleccione una ciudad...</option>');
        
        data.geonames.sort((a, b) => a.name.localeCompare(b.name));
        data.geonames.forEach(city => {
            citySelect.append(`<option value="${city.geonameId}" data-name="${city.name}">${city.name}</option>`);
        });

        enableSelect('city');
    } catch (error) {
        console.error('Error al cargar ciudades:', error);
        showError('Error al cargar la lista de ciudades. Por favor intente más tarde.');
        disableSelect('city');
    }
}

// Inicializar cuando el documento esté listo
$(document).ready(async function() {
    // Verificar cuenta Geonames
    const accountStatus = await checkAccountStatus();
    if (!accountStatus) return;

    // Inicializar Select2
    initializeSelect2('country', 'Seleccione un país...');
    initializeSelect2('state', 'Seleccione un estado/departamento...');
    initializeSelect2('city', 'Seleccione una ciudad...');

    // Cargar países
    await loadCountries();

    // Eventos de cambio
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
        console.log('Ciudad seleccionada:', {
            cityId: cityId,
            cityName: selectedOption.data('name'),
            selectedOption: selectedOption
        });
        
        if (cityId) {
            $('#company_place_id').val(cityId);
            $('#city_name_company').val(selectedOption.data('name'));
            console.log('Valores establecidos:', {
                company_place_id: $('#company_place_id').val(),
                city_name: $('#city_name_company').val()
            });
        } else {
            $('#company_place_id').val('');
            $('#city_name_company').val('');
        }
    });

    // Manejar cambio entre API y manual
    $('input[name="location_type"]').on('change', function() {
        const useApi = $(this).val() === 'api';
        $('#apiLocationCompany').toggle(useApi);
        $('#manualLocationCompany').toggle(!useApi);
        
        const manualFields = $('.manual-field');
        if (useApi) {
            manualFields.prop('disabled', true).val('');
        } else {
            manualFields.prop('disabled', false);
            $('.manual-company-place').val('-2');
        }
    });

    // Agregar validación antes de enviar el formulario
    $('form').on('submit', function(e) {
        const locationType = $('input[name="location_type"]:checked').val();
        const companyPlaceId = $('#company_place_id').val();
        const companyId = $('#empresas').val();
        
        console.log('Validando formulario:', {
            locationType,
            companyPlaceId,
            companyId
        });

        if (locationType === 'api' && !companyPlaceId) {
            e.preventDefault();
            alert('Por favor seleccione una ciudad');
            return false;
        }

        if (!companyId) {
            e.preventDefault();
            alert('Por favor seleccione una empresa');
            return false;
        }

        // Validar que el company_place_id sea un número válido cuando se usa la API
        if (locationType === 'api' && isNaN(parseInt(companyPlaceId))) {
            e.preventDefault();
            alert('La ciudad seleccionada no es válida');
            return false;
        }

        return true;
    });
}); 