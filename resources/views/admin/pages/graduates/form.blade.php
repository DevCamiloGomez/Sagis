@if ($editMode)
    <!-- Form -->
    <form action="{{ route('admin.graduates.update', $item->id) }}" method="POST"  enctype="multipart/form-data">
        @csrf @method('PATCH')

        <!-- Name -->
        <div class="form-group">
            <label class="form-label">Nombres:</label>
            <input type="text" class="form-control " name="name" value="{{ $item->name }}">
        </div>
        @error('name')
            <small class="text-danger">{{ $message }}</small>
        @enderror
        <!-- ./Name -->

        <!-- Lastname -->
        <div class="form-group">
            <label class="form-label">Apellidos:</label>
            <input type="text" class="form-control " name="lastname" value="{{ $item->lastname }}">
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
                    <option value="{{ $documentType->id }}"
                        {{ isSelectedOld($item->document_type_id, $documentType->id) }}>{{ $documentType->name }}
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
            <input type="number" class="form-control " name="document" value="{{ $item->document }}">
        </div>
        @error('document')
            <small class="text-danger">{{ $message }}</small>
        @enderror
        <!-- ./Document -->

        <!-- Birthdate -->
        <div class="form-group">
            <label>Fecha de Nacimiento</label>
            <input type="date" class="form-control" name="birthdate" value="{{ $item->birthdate }}">
        </div>
        @error('birthdate')
            <small class="text-danger">{{ $message }}</small>
        @enderror
        <!-- ./Birthdate -->

        <!-- BirthdatePlaceId -->
        <div class="form-group">
            <label>Lugar de Nacimiento:</label>
            <div class="row">
                <div class="col-md-12 mb-2">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="useApi" name="location_type" value="api" class="custom-control-input" checked>
                        <label class="custom-control-label" for="useApi">Buscar en la API</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="useManual" name="location_type" value="manual" class="custom-control-input">
                        <label class="custom-control-label" for="useManual">Ingresar manualmente</label>
                    </div>
                </div>
                <div id="apiLocation" class="col-md-12">
                    <div class="row">
                        <div class="col-md-4">
                            <select name="country" id="country" class="form-control select2bs4">
                                <option value="">Seleccione un país...</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select name="state" id="state" class="form-control select2bs4">
                                <option value="">Seleccione un estado/departamento...</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select name="city" id="city" class="form-control select2bs4" required>
                                <option value="">Seleccione una ciudad...</option>
                            </select>
                            <input type="hidden" name="birthdate_place_id" id="birthdate_place_id">
                            <input type="hidden" name="city_name" id="city_name">
                        </div>
                    </div>
                </div>
                <div id="manualLocation" class="col-md-12" style="display: none;">
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" name="country_name" class="form-control manual-field" placeholder="País" value="{{ old('country_name') }}">
                            @error('country_name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="state_name" class="form-control manual-field" placeholder="Estado/Departamento" value="{{ old('state_name') }}">
                            @error('state_name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="city_name_manual" class="form-control manual-field" placeholder="Ciudad" value="{{ old('city_name_manual') }}">
                            <input type="hidden" name="birthdate_place_id" value="-2" class="manual-birthdate-place">
                            @error('city_name_manual')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @error('birthdate_place_id')
            <small class="text-danger">{{ $message }}</small>
        @enderror
        <!-- ./BirthdatePlaceId -->

        <!-- Address -->
        <div class="form-group">
            <label class="form-label">Dirección de Residencia:</label>
            <input type="text" class="form-control " name="address" value="{{ $item->address }}">
        </div>
        @error('address')
            <small class="text-danger">{{ $message }}</small>
        @enderror
        <!-- ./Address -->

        <!-- Phone -->
        <div class="form-group">
            <label class="form-label">Celular Personal:</label>
            <input type="text" class="form-control " name="phone" value="{{ $item->phone }}">
        </div>
        @error('phone')
            <small class="text-danger">{{ $message }}</small>
        @enderror
        <!-- ./Phone -->

        <!-- Telephone -->
        <div class="form-group">
            <label class="form-label">Teléfono:</label>
            <input type="text" class="form-control " name="telephone" value="{{ $item->telephone }}">
        </div>
        @error('telephone')
            <small class="text-danger">{{ $message }}</small>
        @enderror
        <!-- ./Telephone -->

        <!-- Code -->
        <div class="form-group">
            <label class="form-label">Código Institucional:</label>
            <input type="text" class="form-control " name="code" value="{{ $item->user->code }}">
        </div>
        @error('code')
            <small class="text-danger">{{ $message }}</small>
        @enderror
        <!-- ./Code -->

        <!-- CompanyEmail -->
        <div class="form-group">
            <label class="form-label">Correo Institucional(@ufps):</label>
            <input type="email" class="form-control " name="company_email" value="{{ $item->user->email }}"
                pattern=".+@ufps.edu.co" size="30">
        </div>
        @error('company_email')
            <small class="text-danger">{{ $message }}</small>
        @enderror
        <!-- ./CompanyEmail -->

        <!-- Email -->
        <div class="form-group">
            <label class="form-label">Correo Personal:</label>
            <input type="email" class="form-control " name="email" value="{{ $item->email }}" size="30">
        </div>
        @error('email')
            <small class="text-danger">{{ $message }}</small>
        @enderror
        <!-- ./Email -->

       <!-- Imagen Principal -->
       <div class="form-group">
        <label>Foto de perfil:</label>
        <div class="text-center">
            <img style="width: 340px; height: 340px" src="{{ asset($item->fullAsset() ) }}" alt="">

        </div>
        <div class="mt-2">
            <input type="file" class="form-control-file" name="image" accept="image/*" >
        </div>   
    </div>
    @error('image')
        <small class="text-danger">{{ $message }}</small>
    @enderror
    <!-- ./Imagen principal -->


        <!-- Submit -->
        <div class="mt-4">
            <div class="btn-group" role="group" aria-label="Basic example">
                <button type="submit" class="btn btn-danger">Guardar</button>
                <div class="ml-5">
                    <a class="btn btn-warning " style="color:black;
                    text-decoration: none;" href="{{ route('admin.graduates.index') }}">Regresar</a>
                </div>
            </div>
          
        </div>
        <!-- ./Submit -->
        
    </form>
    <!-- ./Form -->
@else
    <!-- Form -->
    <form action="{{ route('admin.graduates.store') }}" method="POST" enctype="multipart/form-data" id="graduateForm">
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
                    <option value="{{ $documentType->id }}"
                        {{ isSelectedOld(old('document_type_id'), $documentType->id) }}>{{ $documentType->name }}
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
            <div class="row">
                <div class="col-md-12 mb-2">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="useApi" name="location_type" value="api" class="custom-control-input" checked>
                        <label class="custom-control-label" for="useApi">Buscar en la API</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="useManual" name="location_type" value="manual" class="custom-control-input">
                        <label class="custom-control-label" for="useManual">Ingresar manualmente</label>
                    </div>
                </div>
                <div id="apiLocation" class="col-md-12">
                    <div class="row">
                        <div class="col-md-4">
                            <select name="country" id="country" class="form-control select2bs4">
                                <option value="">Seleccione un país...</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select name="state" id="state" class="form-control select2bs4">
                                <option value="">Seleccione un estado/departamento...</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select name="city" id="city" class="form-control select2bs4" required>
                                <option value="">Seleccione una ciudad...</option>
                            </select>
                            <input type="hidden" name="birthdate_place_id" id="birthdate_place_id">
                            <input type="hidden" name="city_name" id="city_name">
                        </div>
                    </div>
                </div>
                <div id="manualLocation" class="col-md-12" style="display: none;">
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" name="country_name" class="form-control manual-field" placeholder="País" value="{{ old('country_name') }}">
                            @error('country_name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="state_name" class="form-control manual-field" placeholder="Estado/Departamento" value="{{ old('state_name') }}">
                            @error('state_name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="city_name_manual" class="form-control manual-field" placeholder="Ciudad" value="{{ old('city_name_manual') }}">
                            <input type="hidden" name="birthdate_place_id" value="-2" class="manual-birthdate-place">
                            @error('city_name_manual')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
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
            <label class="form-label">Teléfono:</label>
            <input type="text" class="form-control " name="telephone" value="{{ old('telephone') }}">
        </div>
        @error('telephone')
            <small class="text-danger">{{ $message }}</small>
        @enderror
        <!-- ./Telephone -->

        <!-- Code -->
        <div class="form-group">
            <label class="form-label">Código Institucional:</label>
            <input type="text" class="form-control " name="code" value="{{ old('code') }}">
        </div>
        @error('code')
            <small class="text-danger">{{ $message }}</small>
        @enderror
        <!-- ./Code -->

        <!-- CompanyEmail -->
        <div class="form-group">
            <label class="form-label">Correo Institucional(@ufps):</label>
            <input type="email" class="form-control " name="company_email" value="{{ old('company_email') }}"
                pattern=".+@ufps.edu.co" size="30">
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
                <button type="submit" class="btn btn-danger" id="submitButton">Guardar</button>
                <div class="ml-5">
                    <a class="btn btn-warning " style="color:black;
                    text-decoration: none;" href="{{ route('admin.graduates.index') }}">Regresar</a>
                </div>
            
            </div>
          
        </div>
        <!-- ./Submit -->

       
    </form>
    <!-- ./Form -->
@endif

@section('custom_js')
    <script>
        // Variables de caché
        let countriesCache = null;
        let statesCache = {};

        // Función para mostrar mensajes de error
        function showError(message, details = '') {
            console.error(message);
            if (details) {
                console.error('Detalles:', details);
            }
        }

        // Función para verificar el estado de la cuenta
        async function checkAccountStatus() {
            try {
                console.log('Verificando estado de la cuenta Geonames...');
                const response = await fetch('/api/geonames/status');
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const data = await response.json();
                console.log('Estado de la cuenta:', data);
                return data;
            } catch (error) {
                showError('Error verificando estado de la cuenta:', error);
                return null;
            }
        }

        // Función para inicializar Select2
        function initializeSelect2(elementId, placeholder) {
            try {
                const element = $('#' + elementId);
                if (element.data('select2')) {
                    element.select2('destroy');
                }
                element.select2({
                    theme: 'bootstrap4',
                    placeholder: placeholder,
                    width: '100%',
                    allowClear: true,
                    language: {
                        noResults: function() {
                            return "No se encontraron resultados";
                        },
                        searching: function() {
                            return "Buscando...";
                        }
                    }
                }).on('select2:open', function() {
                    // Asegurarse de que el dropdown sea interactivo
                    $(this).data('select2').$dropdown.find('.select2-search__field').prop('disabled', false);
                });
                console.log('Select2 inicializado para:', elementId);
            } catch (error) {
                showError('Error inicializando Select2:', error.message);
            }
        }

        // Función para habilitar un selector
        function enableSelect(elementId) {
            try {
                const element = $('#' + elementId);
                if (!element.length) {
                    throw new Error(`Elemento no encontrado: ${elementId}`);
                }

                // Habilitar el elemento nativo
                element.prop('disabled', false);
                element.removeAttr('disabled');
                element.removeClass('disabled');

                // Actualizar Select2
                if (element.data('select2')) {
                    // Destruir y reinicializar Select2
                    element.select2('destroy');
                    initializeSelect2(elementId, element.find('option:first').text());
                }

                // Asegurarse de que el contenedor de Select2 también esté habilitado
                element.next('.select2-container').removeClass('select2-container--disabled');
                
                console.log('Selector habilitado:', elementId);
            } catch (error) {
                showError('Error habilitando selector:', error.message);
            }
        }

        // Función para deshabilitar un selector
        function disableSelect(elementId) {
            try {
                const element = $('#' + elementId);
                if (!element.length) {
                    throw new Error(`Elemento no encontrado: ${elementId}`);
                }

                // Deshabilitar el elemento nativo
                element.prop('disabled', true);
                element.attr('disabled', 'disabled');
                element.addClass('disabled');

                // Actualizar Select2
                if (element.data('select2')) {
                    element.select2('destroy');
                    initializeSelect2(elementId, element.find('option:first').text());
                }

                // Asegurarse de que el contenedor de Select2 también esté deshabilitado
                element.next('.select2-container').addClass('select2-container--disabled');
                
                console.log('Selector deshabilitado:', elementId);
            } catch (error) {
                showError('Error deshabilitando selector:', error.message);
            }
        }

        // Función para limpiar un selector
        function clearSelect(elementId, placeholder) {
            try {
                const element = $('#' + elementId);
                if (!element.length) {
                    throw new Error(`Elemento no encontrado: ${elementId}`);
                }

                // Limpiar el elemento nativo
                element.html(`<option value="">${placeholder}</option>`);
                
                // Actualizar Select2
                if (element.data('select2')) {
                    element.val(null).trigger('change');
                }

                console.log('Selector limpiado:', elementId);
            } catch (error) {
                showError('Error limpiando selector:', error.message);
            }
        }

        // Función para cargar los países
        async function loadCountries() {
            try {
                console.log('Cargando países...');
                
                // Verificar estado de la cuenta primero
                const accountStatus = await checkAccountStatus();
                if (!accountStatus) {
                    throw new Error('No se pudo verificar el estado de la cuenta Geonames');
                }

                const response = await fetch('/api/geonames/countries');
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                console.log('Respuesta de países:', data);
                
                if (data.geonames) {
                    // Guardar en caché
                    countriesCache = data.geonames;
                    populateCountries(data.geonames);
                } else {
                    throw new Error('No se encontraron países en la respuesta');
                }
            } catch (error) {
                showError('Error cargando países:', error.message);
                // Intentar cargar desde una fuente alternativa si la API falla
                loadCountriesFallback();
            }
        }

        // Función para poblar el selector de países
        function populateCountries(countries) {
            try {
                const countrySelect = document.getElementById('country');
                countrySelect.innerHTML = '<option value="">Seleccione un país...</option>';
                
                countries.sort((a, b) => a.countryName.localeCompare(b.countryName));
                
                countries.forEach(country => {
                    const option = document.createElement('option');
                    option.value = country.countryCode;
                    option.textContent = country.countryName;
                    countrySelect.appendChild(option);
                });

                initializeSelect2('country', 'Seleccione un país...');
                console.log('Países cargados correctamente');
            } catch (error) {
                showError('Error poblando países:', error.message);
            }
        }

        // Función de respaldo para cargar países
        async function loadCountriesFallback() {
            try {
                console.log('Intentando cargar países desde fuente alternativa...');
                const response = await fetch('https://restcountries.com/v3.1/all?fields=name,cca2');
                const countries = await response.json();
                
                const formattedCountries = countries.map(country => ({
                    countryCode: country.cca2,
                    countryName: country.name.common
                }));
                
                populateCountries(formattedCountries);
                console.log('Países cargados desde fuente alternativa');
            } catch (error) {
                showError('Error cargando países de respaldo:', error.message);
            }
        }

        // Función para cargar los estados/departamentos
        async function loadStates(countryCode) {
            try {
                console.log('Cargando estados para:', countryCode);
                
                // Limpiar y habilitar los selectores
                const stateSelect = document.getElementById('state');
                const citySelect = document.getElementById('city');
                
                stateSelect.innerHTML = '<option value="">Seleccione un estado/departamento...</option>';
                citySelect.innerHTML = '<option value="">Seleccione una ciudad...</option>';
                
                enableSelect('state');
                citySelect.disabled = true;

                // Usar caché si está disponible
                if (statesCache[countryCode]) {
                    console.log('Usando estados en caché para:', countryCode);
                    populateStates(statesCache[countryCode]);
                    return;
                }

                const response = await fetch(`/api/geonames/states?countryCode=${countryCode}`);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                console.log('Respuesta de estados:', data);
                
                if (data.geonames && data.geonames.length > 0) {
                    // Guardar en caché
                    statesCache[countryCode] = data.geonames;
                    populateStates(data.geonames);
                } else {
                    throw new Error('No se encontraron estados para este país');
                }

            } catch (error) {
                showError('Error cargando estados:', error.message);
            }
        }

        // Función para poblar el selector de estados
        function populateStates(states) {
            const stateSelect = document.getElementById('state');
            states.sort((a, b) => a.adminName1.localeCompare(b.adminName1));
            
            states.forEach(state => {
                const option = document.createElement('option');
                option.value = state.adminCode1;
                option.textContent = state.adminName1;
                stateSelect.appendChild(option);
            });

            console.log('Estados cargados correctamente');
            initializeSelect2('state', 'Seleccione un estado/departamento...');
        }

        // Función para cargar las ciudades
        async function loadCities(countryCode, stateCode) {
            try {
                console.log('Cargando ciudades para:', stateCode, 'en', countryCode);
                const citySelect = $('#city');
                if (!citySelect.length) {
                    throw new Error('Elemento de ciudad no encontrado');
                }

                // Limpiar el selector
                clearSelect('city', 'Seleccione una ciudad...');

                const response = await fetch(`/api/geonames/cities?countryCode=${countryCode}&stateCode=${stateCode}`);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                console.log('Respuesta de ciudades:', data);
                
                if (data.geonames && data.geonames.length > 0) {
                    data.geonames.sort((a, b) => a.name.localeCompare(b.name));
                    
                    // Limpiar opciones existentes
                    citySelect.empty().append('<option value="">Seleccione una ciudad...</option>');
                    
                    // Agregar nuevas opciones
                    data.geonames.forEach(city => {
                        const option = new Option(city.name, city.geonameId);
                        citySelect.append(option);
                    });

                    console.log('Ciudades cargadas correctamente');
                    enableSelect('city');
                    initializeSelect2('city', 'Seleccione una ciudad...');

                    // Asegurarse de que el valor seleccionado se envíe correctamente
                    citySelect.on('change', function() {
                        const selectedValue = $(this).val();
                        console.log('Ciudad seleccionada:', selectedValue);
                        if (selectedValue) {
                            $(this).prop('required', true);
                        } else {
                            $(this).prop('required', false);
                        }
                    });
                } else {
                    throw new Error('No se encontraron ciudades para este estado');
                }

            } catch (error) {
                showError('Error cargando ciudades:', error.message);
                const citySelect = $('#city');
                if (citySelect.length) {
                    clearSelect('city', 'Escriba el nombre de la ciudad...');
                    enableSelect('city');
                    initializeSelect2('city', 'Escriba el nombre de la ciudad...');
                }
            }
        }

        // Event Listeners
        $('#country').on('change', function() {
            console.log('País seleccionado:', this.value);
            const stateSelect = $('#state');
            const citySelect = $('#city');
            
            if (this.value) {
                console.log('Habilitando selector de estados...');
                clearSelect('state', 'Seleccione un estado/departamento...');
                clearSelect('city', 'Seleccione una ciudad...');
                enableSelect('state');
                disableSelect('city');
                loadStates(this.value);
            } else {
                console.log('Limpiando selectores...');
                clearSelect('state', 'Seleccione un estado/departamento...');
                clearSelect('city', 'Seleccione una ciudad...');
                disableSelect('state');
                disableSelect('city');
            }
        });

        $('#state').on('change', function() {
            console.log('Estado seleccionado:', this.value);
            const countrySelect = $('#country');
            const citySelect = $('#city');
            
            if (this.value && countrySelect.val()) {
                console.log('Habilitando selector de ciudades...');
                clearSelect('city', 'Seleccione una ciudad...');
                enableSelect('city');
                loadCities(countrySelect.val(), this.value);
            } else {
                console.log('Limpiando selector de ciudades...');
                clearSelect('city', 'Seleccione una ciudad...');
                disableSelect('city');
            }
        });

        // Inicializar al cargar la página
        $(document).ready(function() {
            console.log('DOM cargado, iniciando...');
            
            // Inicializar los selectores
            initializeSelect2('country', 'Seleccione un país...');
            initializeSelect2('state', 'Seleccione un estado/departamento...');
            initializeSelect2('city', 'Seleccione una ciudad...');

            // Configurar estados iniciales
            disableSelect('state');
            disableSelect('city');

            // Cargar países
            loadCountries().catch(error => {
                console.error('Error en la carga inicial:', error);
            });
        });

        $(document).ready(function() {
            // Manejar el cambio entre API y entrada manual
            $('input[name="location_type"]').change(function() {
                console.log('Cambio de tipo de ubicación:', this.value);
                const manualFields = $('.manual-field');
                const apiFields = $('#country, #state, #city');
                const manualBirthdatePlace = $('.manual-birthdate-place');
                const apiBirthdatePlace = $('#birthdate_place_id');
                
                if (this.value === 'api') {
                    $('#apiLocation').show();
                    $('#manualLocation').hide();
                    // Deshabilitar campos manuales
                    manualFields.prop('required', false).prop('disabled', true);
                    manualBirthdatePlace.prop('disabled', true);
                    // Habilitar campos API
                    apiFields.prop('disabled', false);
                    apiBirthdatePlace.prop('disabled', false);
                    $('#city').prop('required', true);
                    // Limpiar campos manuales
                    manualFields.val('');
                } else {
                    $('#apiLocation').hide();
                    $('#manualLocation').show();
                    // Habilitar campos manuales
                    manualFields.prop('required', true).prop('disabled', false);
                    manualBirthdatePlace.prop('disabled', false);
                    // Deshabilitar campos API
                    apiFields.prop('disabled', true).prop('required', false);
                    apiBirthdatePlace.prop('disabled', true);
                    // Limpiar campos de API
                    apiFields.val('').trigger('change');
                    apiBirthdatePlace.val('');
                }
            });

            // Manejar el cambio de ciudad
            $('#city').on('change', function() {
                const selectedValue = $(this).val();
                const selectedText = $(this).find('option:selected').text();
                console.log('Ciudad seleccionada:', selectedValue, 'Nombre:', selectedText);
                
                if (selectedValue) {
                    $('#birthdate_place_id').val(selectedValue);
                    $('#city_name').val(selectedText);
                    console.log('Valores actualizados:', {
                        birthdate_place_id: $('#birthdate_place_id').val(),
                        city_name: $('#city_name').val()
                    });
                } else {
                    $('#birthdate_place_id').val('');
                    $('#city_name').val('');
                }
            });

            // Manejar el envío del formulario
            $('#graduateForm').on('submit', function(e) {
                e.preventDefault();
                console.log('Formulario intentando enviar...');

                const locationType = $('input[name="location_type"]:checked').val();
                console.log('Tipo de ubicación:', locationType);

                // Validar campos requeridos básicos
                const requiredFields = ['name', 'lastname', 'email', 'birthdate', 'code', 'company_email', 'document_type_id', 'document'];
                let isValid = true;
                let errorMessage = '';

                requiredFields.forEach(field => {
                    const input = $(`[name="${field}"]`);
                    if (!input.val()) {
                        isValid = false;
                        errorMessage += `El campo ${input.prev('label').text().replace(':', '')} es requerido.\n`;
                    }
                });

                // Validar tipo de documento
                if ($('[name="document_type_id"]').val() === '-1') {
                    isValid = false;
                    errorMessage += 'Debe seleccionar un tipo de documento válido.\n';
                }

                // Validar ubicación según el tipo seleccionado
                if (locationType === 'api') {
                    const cityValue = $('#city').val();
                    const birthdatePlaceId = $('#birthdate_place_id').val();
                    console.log('Validando modo API:', {
                        cityValue: cityValue,
                        birthdate_place_id: birthdatePlaceId,
                        city_name: $('#city_name').val()
                    });
                    
                    if (!cityValue || !birthdatePlaceId) {
                        isValid = false;
                        errorMessage += 'Debe seleccionar una ciudad.\n';
                    }
                } else {
                    const manualFields = $('.manual-field');
                    manualFields.each(function() {
                        if (!$(this).val()) {
                            isValid = false;
                            errorMessage += `El campo ${$(this).attr('placeholder')} es requerido.\n`;
                        }
                    });
                }

                if (!isValid) {
                    alert(errorMessage);
                    return false;
                }

                // Deshabilitar campos no utilizados antes de enviar
                if (locationType === 'api') {
                    $('.manual-field, .manual-birthdate-place').prop('disabled', true);
                    // Asegurarse de que los campos de la API estén habilitados
                    $('#country, #state, #city, #birthdate_place_id').prop('disabled', false);
                } else {
                    $('#country, #state, #city, #birthdate_place_id').prop('disabled', true);
                }

                // Si todo está bien, enviar el formulario
                console.log('Enviando formulario con datos:', {
                    locationType,
                    cityValue: $('#city').val(),
                    birthdate_place_id: $('#birthdate_place_id').val(),
                    city_name: $('#city_name').val(),
                    manualFields: {
                        country: $('[name="country_name"]').val(),
                        state: $('[name="state_name"]').val(),
                        city: $('[name="city_name_manual"]').val()
                    }
                });

                this.submit();
            });

            // Inicializar el estado de los campos según el tipo de ubicación seleccionado
            const initialLocationType = $('input[name="location_type"]:checked').val();
            if (initialLocationType === 'api') {
                $('.manual-field, .manual-birthdate-place').prop('disabled', true);
            } else {
                $('#country, #state, #city, #birthdate_place_id').prop('disabled', true);
            }
        });
    </script>
@endsection
