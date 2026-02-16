function cargarPrincipal() {
    desactivarInputLugares();
    //console.log('Probando')
}


function desactivarInputLugares() {
    var country = document.getElementById("country");
    if (country) country.style.display = "none";
    var state = document.getElementById("state");
    if (state) state.style.display = "none";
    var city = document.getElementById("city");
    if (city) city.style.display = "none";

    var country_input = document.getElementById('country_input');
    if (country_input) country_input.disabled = true;
    var state_input = document.getElementById('state_input');
    if (state_input) state_input.disabled = true;
    var city_input = document.getElementById('city_input');
    if (city_input) city_input.disabled = true;
}


function seleccionarNoExiste() {
    let lugaresUni = document.getElementById('lugares_u');
    let valueSelect = lugaresUni.value;

    if (valueSelect == "-2") {

        /* mostrarLugares(); */
        document.getElementById("country").style.display = "block";
        document.getElementById("state").style.display = "block";
        document.getElementById("city").style.display = "block";

        document.getElementById('country_input').disabled = false;
        document.getElementById('state_input').disabled = false;
        document.getElementById('city_input').disabled = false;

    } else {
        document.getElementById("country").style.display = "none";
        document.getElementById("state").style.display = "none";
        document.getElementById("city").style.display = "none";

        document.getElementById('country_input').value = "";
        document.getElementById('state_input').value = "";
        document.getElementById('city_input').value = "";

        document.getElementById('country_input').disabled = true;
        document.getElementById('state_input').disabled = true;
        document.getElementById('city_input').disabled = true;

    }
    //console.log(valueSelect)
}

function cargarPrincipalJobs() {
    desactivarInputLugares();
    desactivarInputDatosLab();
}

function desactivarInputDatosLab() {
    document.getElementById("name_compamy").style.display = "none";
    document.getElementById("email_company").style.display = "none";
    document.getElementById("address_company").style.display = "none";
    document.getElementById("phone_company").style.display = "none";

    document.getElementById('name_compamy_input').disabled = true;
    document.getElementById('email_company_input').disabled = true;
    document.getElementById('address_company_input').disabled = true;
    document.getElementById('phone_company_input').disabled = true;
}


function seleccionarNoExisteJobs() {
    let empresas = document.getElementById('empresas');
    let valueSelect = empresas.value;

    if (valueSelect == "-2") {

        /* mostrarDatosEmpresas(); */
        document.getElementById("name_compamy").style.display = "block";
        document.getElementById("email_company").style.display = "block";
        document.getElementById("address_company").style.display = "block";
        document.getElementById("phone_company").style.display = "block";


        document.getElementById('name_compamy_input').disabled = false;
        document.getElementById('email_company_input').disabled = false;
        document.getElementById('address_company_input').disabled = false;
        document.getElementById('phone_company_input').disabled = false;

    } else {
        document.getElementById("name_compamy").style.display = "none";
        document.getElementById("email_company").style.display = "none";
        document.getElementById("address_company").style.display = "none";
        document.getElementById("phone_company").style.display = "none";

        document.getElementById('name_compamy_input').value = "";
        document.getElementById('email_company_input').value = "";
        document.getElementById('address_company_input').value = "";
        document.getElementById('phone_company_input').value = "";


        document.getElementById('name_compamy_input').disabled = true;
        document.getElementById('email_company_input').disabled = true;
        document.getElementById('address_company_input').disabled = true;
        document.getElementById('phone_company_input').disabled = true;

    }
    //console.log(valueSelect)
}

// Funciones para la selección de ubicación de la empresa
$(document).ready(function () {
    // Manejar el cambio entre API y manual para la ubicación de la empresa
    $('input[name="location_type"]').change(function () {
        if ($(this).val() === 'api') {
            $('#apiLocationCompany').show();
            $('#manualLocationCompany').hide();
            $('.manual-field').prop('disabled', true);
        } else {
            $('#apiLocationCompany').hide();
            $('#manualLocationCompany').show();
            $('.manual-field').prop('disabled', false);
        }
    });

    // Inicialización simple de Select2 para selects de ubicación de empresa
    $('#countryCompany').select2({
        placeholder: 'Seleccione un país...',
        allowClear: true
    });
    $('#stateCompany').select2({
        placeholder: 'Seleccione un estado/departamento...',
        allowClear: true
    });
    $('#cityCompany').select2({
        placeholder: 'Seleccione una ciudad...',
        allowClear: true
    });

    // Si quieres cargar dinámicamente los estados/ciudades, deberás implementar la API y la lógica AJAX correctamente.

    // --- Lógica para el campo de Salario (Formateo con puntos) ---
    function formatNumber(n) {
        return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    $(document).on('input', '.salary-input', function () {
        $(this).val(formatNumber($(this).val()));
    });

    // Formatear valores iniciales (para edición)
    $('.salary-input').each(function () {
        if ($(this).val()) {
            $(this).val(formatNumber($(this).val()));
        }
    });

    // Limpiar puntos antes de enviar el formulario
    $(document).on('submit', 'form', function () {
        $(this).find('.salary-input').each(function () {
            var cleanValue = $(this).val().replace(/\./g, '');
            $(this).val(cleanValue);
        });
    });
});
