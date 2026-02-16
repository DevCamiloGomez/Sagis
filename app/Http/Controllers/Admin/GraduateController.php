<?php

namespace App\Http\Controllers\Admin;

use App\Imports\UsersImport;

use Illuminate\Http\Request;

use App\Imports\PeopleImport;
use App\Mail\MessageReceived;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Repositories\CityRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Repositories\StateRepository;
use App\Repositories\PersonRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\CountryRepository;
use App\Repositories\FacultyRepository;
use App\Repositories\ProgramRepository;
use Illuminate\Support\Facades\Storage;
use App\Repositories\UniversityRepository;
use App\Repositories\DocumentTypeRepository;
use App\Http\Requests\Graduates\StoreRequest;
use App\Repositories\PersonCompanyRepository;
use App\Http\Requests\Graduates\UpdateRequest;
use App\Repositories\PersonAcademicRepository;
use App\Http\Requests\Graduates\StoreJobRequest;
use App\Http\Requests\Graduates\UpdateJobRequest;

use App\Http\Requests\Graduates\StoreAcademicRequest;
use App\Http\Requests\Graduates\UpdateAcademicRequest;
use App\Http\Requests\Graduates\UpdatePasswordRequest;
use App\Services\S3UploadService;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Person;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use App\Mail\MassEmail;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendMassEmail;

/**
 * Controlador GraduateController - Gestión específica de graduados
 * 
 * Este controlador maneja todas las operaciones relacionadas con los graduados,
 * incluyendo su registro, actualización y seguimiento.
 * 
 * Características principales:
 * - Registro de nuevos graduados
 * - Importación masiva de graduados
 * - Gestión de información académica
 * - Seguimiento laboral
 * - Generación de reportes
 * - Envío de comunicaciones
 * 
 * Middleware:
 * - auth:admin : Requiere autenticación de administrador
 * 
 * Funcionalidades especiales:
 * - Importación desde Excel (UsersImport, PeopleImport)
 * - Envío de correos (MessageReceived)
 * - Carga de archivos a S3
 * 
 * Repositorios utilizados:
 * - UserRepository: Gestión de usuarios
 * - PersonRepository: Información personal
 * - PersonAcademicRepository: Información académica
 * - PersonCompanyRepository: Información laboral
 * - Otros repositorios de soporte
 * 
 * Documentado por: Camilo Gomez
 */
class GraduateController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /** @var PersonRepository */
    protected $personRepository;

    /** @var RoleRepository */
    protected $roleRepository;

    /** @var DocumentTypeRepository */
    protected $documentTypeRepository;

    /** @var CityRepository */
    protected $cityRepository;

        /** @var StateRepository */
        protected $stateRepository;

      /** @var CountryRepository */
      protected $countryRepository;

     /** @var PersonAcademicRepository */
     protected $personAcademicRepository;

     
     /** @var PersonCompanyRepository */
     protected $personCompanyRepository;


       /** @var ProgramRepository */
       protected $programRepository;

        /** @var UniversityRepository */
        protected $universityRepository;

         /** @var CompanyRepository */
         protected $companyRepository;

        
        /** @var FacultyRepository */
        protected $facultyRepository;

    /** @var \Spatie\Permission\Models\Role */
    protected $role;

    protected $s3UploadService;

    public function __construct(
        UserRepository $userRepository,
        PersonRepository $personRepository,
        RoleRepository $roleRepository,
        DocumentTypeRepository $documentTypeRepository,
        CountryRepository $countryRepository,
        StateRepository $stateRepository,
        CityRepository $cityRepository,
        ProgramRepository $programRepository,
        PersonAcademicRepository $personAcademicRepository, 
        UniversityRepository $universityRepository,
        FacultyRepository $facultyRepository,
        PersonCompanyRepository $personCompanyRepository,
        CompanyRepository $companyRepository,
        S3UploadService $s3UploadService
    ) {
        $this->middleware('auth:admin');

        $this->userRepository = $userRepository;
        $this->personRepository = $personRepository;
        $this->roleRepository = $roleRepository;
        $this->documentTypeRepository = $documentTypeRepository;
        $this->countryRepository = $countryRepository;
        $this->stateRepository = $stateRepository;
        $this->cityRepository = $cityRepository;
        $this->programRepository = $programRepository;
        $this->personAcademicRepository = $personAcademicRepository;
        $this->universityRepository = $universityRepository;
        $this->facultyRepository =  $facultyRepository;
        $this->personCompanyRepository = $personCompanyRepository;
        $this->companyRepository = $companyRepository;
        $this->s3UploadService = $s3UploadService;

        $this->role = $this->roleRepository->getByAttribute('name', 'graduado');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $items = $this->userRepository->getByRole($this->role->name);

            $cantidadGraduates = $items->count();
    

            return view('admin.pages.graduates.index', compact('items', 'cantidadGraduates'));
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $documentTypes = $this->documentTypeRepository->all();
            $cities = $this->cityRepository->allOrderBy('countries.id');
           // $programs = $this->progmamRepository->getByAttribute('level_id', 1);

            // return $cities;

            return view('admin.pages.graduates.create', compact('documentTypes', 'cities'));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function import_excel(Request $request){

        try{
     $file = $request->file('file');


      /*   $person = $this->personRepository->last();
        dd($person); */
   
    

        //dd($request['name']);
        //dd($file);

      //  dd(new PeopleImport);
        Excel::import(new PeopleImport, $file);

        
        return back()->with('alert', ['title' => '¡Éxito!', 'icon' => 'success', 'message' => 'Se ha importado los datos correctamente.']);
    } catch (\Exception $th) {
        dd($th);
         return back()->with('alert', ['title' => '¡Error!', 'icon' => 'error', 'message' => 'No se han guardado los datos correctamente.']);
    }

    }

    public function destroy_all(){
        try {

            $people = $this->personRepository->getOnlyGraduates();

            
            

            DB::beginTransaction();

            foreach($people as $person){

                 $this->personRepository->delete($person);
            }

           

           DB::commit();
            
           
            return back()->with('alert', ['title' => '¡Éxito!', 'message' => 'Se han eliminado todos los graduados correctamente.', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            dd($th);
            return back()->with('alert', ['title' => '¡Error!', 'message' => 'No se ha podido eliminar correctamente.', 'icon' => 'error']);
        }

    }


    public function send_email(){

        try {

            $people = $this->personRepository->getOnlyGraduatesAll();

            DB::beginTransaction();

            foreach($people as $person){
                $userParams =  $person->user;
                try {
                    \Log::info('Intentando enviar correo a:', ['email' => $person->email]);
                    Mail::to($person->email)->queue(new MessageReceived($person, $userParams));
                    \Log::info('Correo encolado exitosamente');
                } catch (\Exception $e) {
                    \Log::error('Error al enviar correo:', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                        'email' => $person->email,
                        'mail_config' => [
                            'driver' => config('mail.default'),
                            'host' => config('mail.mailers.smtp.host'),
                            'port' => config('mail.mailers.smtp.port'),
                            'username' => config('mail.mailers.smtp.username'),
                        ]
                    ]);
                    // No lanzamos la excepción para que el registro continúe
                }
            }

           DB::commit();
            
           
            return back()->with('alert', ['title' => '¡Éxito!', 'message' => 'Se han enviado las credenciales a todos los graduados correctamente.', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
           // dd($th);
            return back()->with('alert', ['title' => '¡Error!', 'message' => 'No se han enviado las credenciales', 'icon' => 'error']);
        }


    }



    public function create_academic($id)
    {
        try {
          
                $item = $this->personRepository->getById($id);

                //dd($item );
    
                $users = $this->userRepository->getByRole($this->role->name);
                
    
                //$documentTypes = $this->documentTypeRepository->all();
                $cities = $this->cityRepository->allOrderBy('countries.id');
    
               // $academics_full = $this->personAcademicRepository->getUni();
    
                 //dd($academics_full);
    
                 //$data_academic = $this->personAcademicRepository->getById($id_academic);
    
                 //$programs_full = $this->personAcademicRepository->getProgramas();
                 $academic_levels =  $this->personAcademicRepository->getNiveles();

                 //dd($academic_levels);
    
                 //dd($academic_levels);
    
                 //dd($data_academic->program->academicLevel->name);
                $academics = $item->personAcademic;
    
               // $laborales = $item->personCompany;
               //dd($id_academic);
    
    
                //return $id;
    
                return view('admin.pages.graduates.create_academic', compact('item',  'cities', 'academics',  'users', 'academic_levels'));
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    public function create_jobs($id)
    {
        try {
          
                $item = $this->personRepository->getById($id);

    
                $users = $this->userRepository->getByRole($this->role->name);

                $companies =  $this->personCompanyRepository->getCompanies();
                $cities = $this->cityRepository->allOrderBy('countries.id');

    
                return view('admin.pages.graduates.create_jobs', compact('item',  'cities',   'users', 'companies'));
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validar datos básicos
            try {
                \Log::info('Iniciando validación con datos:', $request->all());

                // Asegurarse de que birthdate_place_id sea un entero cuando viene de la API
                if ($request->location_type === 'api' && $request->birthdate_place_id) {
                    $request->merge(['birthdate_place_id' => (int)$request->birthdate_place_id]);
                }

                $rules = [
                    'name' => 'required|string|max:255',
                    'lastname' => 'required|string|max:255',
                    'email' => 'required|email|unique:people,email',
                    'birthdate' => 'required|date',
                    'location_type' => 'required|in:api,manual',
                    'code' => 'required|string|unique:users,code',
                    'document_type_id' => 'required|not_in:-1|exists:document_types,id',
                    'document' => 'required|string|unique:people,document',
                    'phone' => 'nullable|string|max:20',
                    'telephone' => 'nullable|string|max:20',
                    'address' => 'nullable|string|max:255',
                    'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
                ];

                // Agregar reglas según el tipo de ubicación
                if ($request->location_type === 'api') {
                    $rules['birthdate_place_id'] = 'required|integer';
                    \Log::info('Validando modo API:', [
                        'birthdate_place_id' => $request->birthdate_place_id,
                        'tipo' => gettype($request->birthdate_place_id)
                    ]);
                    
                    // Asegurarse de que los campos manuales no interfieran
                    $request->merge([
                        'country_name' => null,
                        'state_name' => null,
                        'city_name_manual' => null
                    ]);
                } else {
                    $rules['country_name'] = 'required|string|max:255';
                    $rules['state_name'] = 'required|string|max:255';
                    $rules['city_name_manual'] = 'required|string|max:255';
                    // Asegurarse de que el campo birthdate_place_id no interfiera
                    $request->merge(['birthdate_place_id' => null]);
                }

                \Log::info('Reglas de validación aplicadas:', $rules);

                $validator = validator($request->all(), $rules, [
                    'document_type_id.not_in' => 'Debe seleccionar un tipo de documento válido.',
                    'document_type_id.exists' => 'El tipo de documento seleccionado no es válido.',
                    'code.unique' => 'El código institucional ya está registrado.',
                    'document.unique' => 'El número de documento ya está registrado.',
                    'email.unique' => 'El correo personal ya está registrado.',
                    'name.required' => 'El nombre es requerido.',
                    'lastname.required' => 'Los apellidos son requeridos.',
                    'email.required' => 'El correo personal es requerido.',
                    'birthdate.required' => 'La fecha de nacimiento es requerida.',
                    'birthdate_place_id.required' => 'Debe seleccionar una ciudad cuando usa la API.',
                    'birthdate_place_id.integer' => 'El ID de la ciudad debe ser un número válido.',
                    'country_name.required' => 'El país es requerido cuando ingresa manualmente.',
                    'state_name.required' => 'El estado/departamento es requerido cuando ingresa manualmente.',
                    'city_name_manual.required' => 'La ciudad es requerida cuando ingresa manualmente.',
                    'code.required' => 'El código institucional es requerido.',
                    'document_type_id.required' => 'El tipo de documento es requerido.',
                    'document.required' => 'El número de documento es requerido.'
                ]);

                if ($validator->fails()) {
                    \Log::error('Errores de validación:', [
                        'errors' => $validator->errors()->toArray(),
                        'data' => $request->all(),
                        'rules' => $rules
                    ]);
                    throw new \Illuminate\Validation\ValidationException($validator);
                }

                $validated = $validator->validated();
                \Log::info('Datos validados correctamente:', $validated);

            } catch (\Illuminate\Validation\ValidationException $e) {
                \Log::error('Error de validación detallado:', [
                    'errors' => $e->errors(),
                    'data' => $request->all(),
                    'rules' => $rules ?? []
                ]);
                throw $e;
            }

            // Manejar la ubicación según el tipo de entrada
            if ($request->location_type === 'manual') {
                if (!$request->country_name || !$request->state_name || !$request->city_name_manual) {
                    throw new \Illuminate\Validation\ValidationException(
                        validator()->make([], []),
                        response()->json([
                            'country_name' => ['El país es requerido.'],
                            'state_name' => ['El estado/departamento es requerido.'],
                            'city_name_manual' => ['La ciudad es requerida.']
                        ], 422)
                    );
                }

                // Crear o encontrar el país
                $country = Country::firstOrCreate(
                    ['name' => Str::lower($request->country_name)],
                    ['slug' => Str::slug($request->country_name)]
                );

                // Crear o encontrar el estado
                $state = State::firstOrCreate(
                    [
                        'country_id' => $country->id,
                        'name' => $request->state_name
                    ],
                    ['slug' => Str::slug($request->state_name)]
                );

                // Crear o encontrar la ciudad
                $city = City::firstOrCreate(
                    [
                        'state_id' => $state->id,
                        'name' => $request->city_name_manual
                    ],
                    [
                        'slug' => Str::slug($request->city_name_manual),
                        'geoname_id' => null
                    ]
                );

                $birthdatePlaceId = $city->id;
            } else {
                // Validar que el ID de la ciudad exista en la base de datos
                $city = City::where('geoname_id', $request->birthdate_place_id)->first();
                if (!$city) {
                    // Si la ciudad no existe, intentar crearla usando la API de Geonames
                    try {
                        \Log::info('Intentando obtener datos de Geonames para ID:', ['geonameId' => $request->birthdate_place_id]);
                        
                        $geonamesResponse = Http::get('http://api.geonames.org/getJSON', [
                            'geonameId' => $request->birthdate_place_id,
                            'username' => config('services.geonames.username', 'camilogomez666')
                        ]);

                        \Log::info('Respuesta de Geonames:', ['response' => $geonamesResponse->json()]);

                        if ($geonamesResponse->successful() && isset($geonamesResponse['geonameId'])) {
                            $data = $geonamesResponse->json();
                            
                            // Buscar o crear el país
                            $country = Country::firstOrCreate(
                                ['name' => Str::lower($data['countryName'])],
                                ['slug' => Str::slug($data['countryName'])]
                            );

                            // Buscar o crear el estado
                            $state = State::firstOrCreate(
                                [
                                    'country_id' => $country->id,
                                    'name' => $data['adminName1']
                                ],
                                ['slug' => Str::slug($data['adminName1'])]
                            );

                            // Crear la ciudad
                            $city = City::create([
                                'state_id' => $state->id,
                                'name' => $data['name'],
                                'slug' => Str::slug($data['name']),
                                'geoname_id' => $data['geonameId']
                            ]);

                            \Log::info('Ciudad creada exitosamente:', ['city' => $city->toArray()]);
                        } else {
                            throw new \Illuminate\Validation\ValidationException(
                                validator()->make([], []),
                                response()->json(['birthdate_place_id' => ['La ciudad seleccionada no es válida o no se pudo obtener de la API.']], 422)
                            );
                        }
                    } catch (\Exception $e) {
                        \Log::error('Error al obtener datos de Geonames:', [
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString()
                        ]);
                        throw new \Illuminate\Validation\ValidationException(
                            validator()->make([], []),
                            response()->json(['birthdate_place_id' => ['Error al obtener los datos de la ciudad desde la API: ' . $e->getMessage()]], 422)
                        );
                    }
                }
                $birthdatePlaceId = $city->id;
            }

            // Manejar la imagen si se proporciona
            $personParams = $request->only(['name', 'lastname', 'document_type_id', 'document', 'phone', 'telephone', 'email', 'address', 'birthdate']);
            $personParams['birthdate_place_id'] = $birthdatePlaceId;

            if ($request->hasFile('image')) {
                $fileParams = $this->saveImage($request);
                $personParams = array_merge($personParams, $fileParams);
            }

            // Crear la persona
            $person = $this->personRepository->create($personParams);

            // Crear el usuario
            $userParams = [
                'code' => $request->code,
                'email' => $request->email,
                'person_id' => $person->id,
                'password' => Hash::make('password')
            ];

            \Log::info('Creando usuario con parámetros:', [
                'email' => $userParams['email'],
                'code' => $userParams['code'],
                'has_password' => !empty($userParams['password'])
            ]);

            $user = $this->userRepository->create($userParams);

            \Log::info('Usuario creado:', [
                'id' => $user->id,
                'email' => $user->email,
                'has_password' => !empty($user->password)
            ]);

            // Asignar rol
            $user->roles()->sync($this->role);

            // Crear datos académicos por defecto
            $personAcademicParams = [
                'person_id' => $person->id,
                'program_id' => $this->programRepository->first()->id,
                'year' => date('Y')
            ];

            $this->personAcademicRepository->create($personAcademicParams);

            // Enviar email
            try {
                Mail::to($person->email)->send(new MessageReceived($person, [
                    'email' => $userParams['email'],
                    'password' => 'password' // Enviamos la contraseña sin hashear
                ]));
            } catch (\Exception $e) {
                \Log::error('Error al enviar correo:', [
                    'error' => $e->getMessage(),
                    'email' => $person->email
                ]);
            }

            DB::commit();
            return redirect()->route('admin.graduates.index')
                ->with('alert', ['title' => '¡Éxito!', 'icon' => 'success', 'message' => 'Se ha registrado correctamente.']);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            \Log::error('Error de validación al crear graduado:', [
                'errors' => $e->errors(),
                'data' => $request->all(),
                'rules' => $rules ?? []
            ]);
            
            $errorMessages = collect($e->errors())->flatten()->join(', ');
            return back()
                ->withInput()
                ->with('alert', [
                    'title' => '¡Error de validación!', 
                    'icon' => 'error', 
                    'message' => $errorMessages
                ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al crear graduado:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $request->all()
            ]);
            
            return back()
                ->withInput()
                ->with('alert', [
                    'title' => '¡Error!', 
                    'icon' => 'error', 
                    'message' => 'Error al registrar el graduado: ' . $e->getMessage()
                ]);
        }
    }


    public function store_academic(StoreAcademicRequest $request, $id)
    {
        try {

            DB::beginTransaction();


                $data = $request->all();

                $city_id = 0;
                if($data['university_place_id']== "-2"){
                    /* Pais */
                    $countryParams = $request->only('country_name');
                    $countryParams['name'] = $countryParams['country_name'];
                    unset($countryParams['country_name']);

                    $countryParams['slug'] = strtoupper(substr($countryParams['name'], 0, 3));


                     /* Con esta consulta se comprueba si el pais que ingreso el usuario existe, si existe devuelve el pais sino NULL */
                    $country= $this->countryRepository->getPais($countryParams['name']);
                    /* Si es NULL crea el PAIS  */
                    if(is_null($country))  $this->countryRepository->create($countryParams);




                    /* Estado/Departamento */
                    $stateParams = $request->only('state_name');
                    $country_id = $this->countryRepository->getPaisID($countryParams['name']);

                    $stateParams['name'] = $stateParams['state_name'];
                    unset($stateParams['state_name']);

                    $stateParams['slug'] = strtoupper(substr($stateParams['name'], 0, 3));

                    $stateParams['country_id'] = $country_id;

                     /* Con esta consulta se comprueba si el estado que ingreso el usuario existe, si existe devuelve el estado sino NULL */
                     $state= $this->stateRepository->getEstado($stateParams['name']);

                    //dd($state);
                    
                     /* Si es NULL crea el ESTADO  */
                     if(is_null($state))  $this->stateRepository->create($stateParams);


                     /* Ciudad */
                     $cityParams = $request->only('city_name');
                     $state_id= $this->stateRepository->getStateID($stateParams['name']);
   
                     $cityParams['name'] = $cityParams['city_name'];
                     unset($cityParams['city_name']);

                     $cityParams['slug'] = strtoupper(substr($cityParams['name'], 0, 3));

                     $cityParams['state_id'] = $state_id;

                      /* Con esta consulta se comprueba si la ciudad que ingreso el usuario existe, si existe devuelve la ciudad sino NULL */
                      $city= $this->cityRepository->getCity($cityParams['name']);
                    
                      /* Si es NULL crea la CIUDAD  */
                      if(is_null($city))  $this->cityRepository->create($cityParams);  


                      $city_id = $this->cityRepository->getCityID($cityParams['name']);
                    
                }else{
                    $city_id = (int)$data['university_place_id'];
                }

                /* Universidad */

                $universityParams = $request->only('university_name', 'address');
                $universityParams['name'] = $universityParams['university_name'];
                unset($universityParams['university_name']);

                $universityParams['city_id'] = $city_id;

                 /* Con esta consulta se comprueba si la universidad que ingreso el usuario existe, si existe devuelve la universidad sino NULL */
                 $university= $this->universityRepository->getUniversity($universityParams['name']);
                    
                 /* Si es NULL crea la UNIVERSIDAD  */
                 if(is_null($university))  $this->universityRepository->create($universityParams);

                 /* Facultad */
                 $facultyParams = $request->only('faculty_name');
                 $university_id = $this->universityRepository->getUniversityID($universityParams['name']);

                 $facultyParams['name'] = $facultyParams['faculty_name'];
                 unset($facultyParams['faculty_name']);

                 $facultyParams['university_id'] = $university_id;

                  /* Con esta consulta se comprueba si la facultad que ingreso el usuario existe, si existe devuelve la facultad sino NULL */
                /*   $faculty= $this->facultyRepository->getModelName($facultyParams['name']); */
                    
                  /* Si es NULL crea la FACULTAD  */
                  /* if(is_null($faculty))   */
                  $this->facultyRepository->create($facultyParams);

                  /* Problema viene de aca */
                $faculty_id = $this->facultyRepository->getModelID($facultyParams['name']);
                //dd($faculty_id );

                $program_params = $request->only('program_name', 'academic_level_id');
                $program_params['name'] = $program_params['program_name'];
                unset($program_params['program_name']);
                $program_params['academic_level_id'] = (int)$program_params['academic_level_id'];
                $program_params['faculty_id'] = $faculty_id;

                  /* Con esta consulta se comprueba si el programa que ingreso el usuario existe, si existe devuelve el programa sino NULL */
                /*   $program= $this->programRepository->getModelName($program_params['name']);
 */
                  //dd($program);
                    
                  /* Si es NULL crea el PROGRAMA  */
                /*   if(is_null($program))   */
                  
                  $this->programRepository->create($program_params);

                  $personAcademic_params = $request->only('year');
                  $program_id = $this->programRepository->getModelID($program_params['name']);
                  $personAcademic_params['program_id'] = $program_id ;
                  $personAcademic_params['person_id'] = (int)$id;


                  $this->personAcademicRepository->create( $personAcademic_params);
            DB::commit();
            return redirect()->route('admin.graduates.show', $id)->with('alert', ['title' => '¡Éxito!', 'icon' => 'success', 'message' => 'Se ha registrado correctamente.']);
        } catch (\Exception $th) {
            DB::rollBack();
            dd($th);
            return back()->with('alert', ['title' => '¡Error!', 'icon' => 'error', 'message' => 'Se ha registrado correctamente.']);
        }
    }


    public function store_jobs(Request $request, $id)
    {
        try {
            \Log::info('Iniciando store_jobs con datos:', $request->all());

            // Validar datos requeridos
            $validator = validator($request->all(), [
                'company_id' => 'required',
                'salary' => 'required',
                'in_working' => 'required|in:0,1',
                'location_type' => 'required|in:api,manual',
            ], [
                'company_id.required' => 'Debe seleccionar una empresa',
                'salary.required' => 'El salario es requerido',
                'in_working.required' => 'Debe indicar si está trabajando actualmente',
                'in_working.in' => 'El valor de estado laboral no es válido',
                'location_type.required' => 'Debe seleccionar el tipo de ubicación',
                'location_type.in' => 'El tipo de ubicación no es válido'
            ]);

            if ($validator->fails()) {
                \Log::error('Error de validación:', [
                    'errors' => $validator->errors()->toArray(),
                    'data' => $request->all()
                ]);
                throw new \Illuminate\Validation\ValidationException($validator);
            }

            DB::beginTransaction();

            $data = $request->all();
            $city_id = null;

            // Manejar la ubicación de la empresa
            if ($request->location_type === 'api') {
                if (!$request->company_place_id) {
                    throw new \Illuminate\Validation\ValidationException(
                        validator()->make([], []),
                        response()->json([
                            'company_place_id' => ['Debe seleccionar una ciudad de la lista.']
                        ], 422)
                    );
                }

                // Buscar la ciudad por geoname_id
                $city = City::where('geoname_id', $request->company_place_id)->first();
                
                if (!$city) {
                    // Si la ciudad no existe, intentar crearla usando la API de Geonames
                    try {
                        \Log::info('Intentando obtener datos de Geonames para ID:', ['geonameId' => $request->company_place_id]);
                        
                        $geonamesResponse = Http::get('http://api.geonames.org/getJSON', [
                            'geonameId' => $request->company_place_id,
                            'username' => config('services.geonames.username', 'camilogomez666')
                        ]);

                        \Log::info('Respuesta de Geonames:', ['response' => $geonamesResponse->json()]);

                        if ($geonamesResponse->successful() && isset($geonamesResponse['geonameId'])) {
                            $data = $geonamesResponse->json();
                            
                            // Buscar o crear el país
                            $country = Country::firstOrCreate(
                                ['name' => $data['countryName']],
                                ['slug' => Str::slug($data['countryName'])]
                            );

                            // Buscar o crear el estado
                            $state = State::firstOrCreate(
                                [
                                    'country_id' => $country->id,
                                    'name' => $data['adminName1']
                                ],
                                ['slug' => Str::slug($data['adminName1'])]
                            );

                            // Crear la ciudad
                            $city = City::create([
                                'state_id' => $state->id,
                                'name' => $data['name'],
                                'slug' => Str::slug($data['name']),
                                'geoname_id' => $data['geonameId']
                            ]);

                            \Log::info('Ciudad creada exitosamente:', ['city' => $city->toArray()]);
                        } else {
                            throw new \Exception('No se pudo obtener la información de la ciudad desde Geonames.');
                        }
                    } catch (\Exception $e) {
                        \Log::error('Error al obtener datos de Geonames:', [
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString()
                        ]);
                        throw new \Exception('Error al obtener los datos de la ciudad: ' . $e->getMessage());
                    }
                }
                
                $city_id = $city->id;
            } else {
                // Crear o obtener país
                $countryParams = [
                    'name' => $request->country_name,
                    'slug' => strtoupper(substr($request->country_name, 0, 3))
                ];
                $country = $this->countryRepository->getPais($countryParams['name']);
                if (is_null($country)) {
                    $country = $this->countryRepository->create($countryParams);
                }

                // Crear o obtener estado
                $stateParams = [
                    'name' => $request->state_name,
                    'slug' => strtoupper(substr($request->state_name, 0, 3)),
                    'country_id' => $country->id
                ];
                $state = $this->stateRepository->getEstado($stateParams['name']);
                if (is_null($state)) {
                    $state = $this->stateRepository->create($stateParams);
                }

                // Crear o obtener ciudad
                $cityParams = [
                    'name' => $request->city_name_manual,
                    'slug' => strtoupper(substr($request->city_name_manual, 0, 3)),
                    'state_id' => $state->id
                ];
                $city = $this->cityRepository->getCiudad($cityParams['name']);
                if (is_null($city)) {
                    $city = $this->cityRepository->create($cityParams);
                }
                $city_id = $city->id;
            }

            if ($request->company_id === '-2') {
                \Log::info('Creando nueva empresa');
                if (!$request->name || !$request->email || !$request->address || !$request->phone) {
                    throw new \Illuminate\Validation\ValidationException(
                        validator()->make([], []),
                        response()->json([
                            'name' => ['El nombre de la empresa es requerido.'],
                            'email' => ['El correo de la empresa es requerido.'],
                            'address' => ['La dirección de la empresa es requerida.'],
                            'phone' => ['El teléfono de la empresa es requerido.']
                        ], 422)
                    );
                }

                // Preparar los datos de la empresa
                $companyData = [
                    'name' => $request->name,
                    'email' => $request->email,
                    'address' => $request->address,
                    'phone' => $request->phone,
                    'city_id' => $city_id,
                    'country_id' => $request->location_type === 'api' ? $city->state->country_id : $country->id
                ];

                \Log::info('Buscando empresa existente con datos:', $companyData);

                // Buscar empresa existente por nombre
                $existingCompany = $this->companyRepository->getByAttribute('name', $request->name);

                if ($existingCompany) {
                    \Log::info('Empresa encontrada:', ['company' => $existingCompany->toArray()]);
                    $company_id = $existingCompany->id;
                } else {
                    \Log::info('Creando nueva empresa con datos:', $companyData);

                    try {
                        // Intentar crear la empresa usando updateOrCreate
                        $company = $this->companyRepository->updateOrCreate(
                            ['name' => $request->name],
                            $companyData
                        );

                        if (!$company) {
                            throw new \Exception('No se pudo crear la empresa');
                        }
                        $company_id = $company->id;
                        \Log::info('Empresa creada/actualizada exitosamente:', ['company' => $company->toArray()]);
                    } catch (\Exception $e) {
                        \Log::error('Error al crear empresa:', [
                            'error' => $e->getMessage(),
                            'data' => $companyData
                        ]);
                        throw new \Exception('Error al crear la empresa: ' . $e->getMessage());
                    }
                }
            } else {
                $company_id = $request->company_id;
            }

            // Verificar si ya existe un registro de trabajo para esta persona y empresa
            $existingJob = $this->personCompanyRepository->getByAttribute('person_id', $id);
            if ($existingJob && $existingJob->company_id == $company_id) {
                throw new \Exception('Ya existe un registro de trabajo para esta persona en esta empresa.');
            }

            \Log::info('Creando registro de trabajo con datos:', [
                'person_id' => $id,
                'company_id' => $company_id,
                'company_place_id' => $city_id,
                'salary' => $request->salary,
                'in_working' => $request->in_working,
                'start_date' => now()
            ]);

            // Crear el registro de trabajo
            $job = $this->personCompanyRepository->create([
                'person_id' => $id,
                'company_id' => $company_id,
                'company_place_id' => $city_id,
                'salary' => $request->salary,
                'in_working' => $request->in_working,
                'start_date' => now()
            ]);

            \Log::info('Registro de trabajo creado exitosamente:', ['job' => $job->toArray()]);

            DB::commit();
            return redirect()->route('admin.graduates.show', $id)
                ->with('alert', ['title' => '¡Éxito!', 'icon' => 'success', 'message' => 'Datos laborales creados exitosamente']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            \Log::error('Error de validación al crear datos laborales:', [
                'errors' => $e->errors(),
                'data' => $request->all()
            ]);
            return back()
                ->withInput()
                ->with('alert', ['title' => '¡Error!', 'icon' => 'error', 'message' => collect($e->errors())->flatten()->join(', ')]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al crear datos laborales:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return back()
                ->withInput()
                ->with('alert', ['title' => '¡Error!', 'icon' => 'error', 'message' => 'Error al crear los datos laborales: ' . $e->getMessage()]);
        }
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $item = $this->personRepository->getById($id);

            $academics = $item->personAcademic;

            $laborales = $item->personCompany;

            return view('admin.pages.graduates.show', compact('item', 'academics', 'laborales'));
        } catch (\Exception $th) {
            throw $th->getMessage();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $item = $this->personRepository->getById($id);

            $documentTypes = $this->documentTypeRepository->all();
            $cities = $this->cityRepository->allOrderBy('countries.id');

            $academics = $item->personAcademic;

            $laborales = $item->personCompany;

            return view('admin.pages.graduates.edit', compact('item', 'documentTypes', 'cities', 'academics', 'laborales'));
        } catch (\Exception $th) {
            throw $th->getMessage();
        }
    }

    /**
     * Show the form for editing the Graduate's password.
     * 
     * @param int $id
     * 
     * @return \Illuminate\Http\Response
     */
    public function edit_password($id)
    {
        try {
            $item = $this->personRepository->getById($id);

            return view('admin.pages.graduates.edit_password', compact('item'));
        } catch (\Exception $th) {
            //throw $th;
        }
    }



    public function edit_academic($id, $id_academic)
    {
        try {
            $item = $this->personRepository->getById($id);

            $users = $this->userRepository->getByRole($this->role->name);
            

            $documentTypes = $this->documentTypeRepository->all();
            $cities = $this->cityRepository->allOrderBy('countries.id');

            $academics_full = $this->personAcademicRepository->getUni();

             //dd($academics_full);

             $data_academic = $this->personAcademicRepository->getById($id_academic);

             $programs_full = $this->personAcademicRepository->getProgramas();
             $academic_levels =  $this->personAcademicRepository->getNiveles();

             //dd($academic_levels);

             //dd($data_academic->program->academicLevel->name);
            $academics = $item->personAcademic;

            $laborales = $item->personCompany;
           //dd($id_academic);


            //return $id;

            return view('admin.pages.graduates.edit_academic', compact('item', 'documentTypes', 'cities', 'academics', 'laborales', 'users', 'academics_full', 'data_academic', 'programs_full', 'academic_levels'));
        } catch (\Exception $th) {
            throw $th->getMessage();
        }
    }


    public function edit_jobs($id, $id_company){

        try {
            $item = $this->personRepository->getById($id);

             $data_company = $this->personCompanyRepository->getById($id_company);

             $companies =  $this->personCompanyRepository->getCompanies();
             //dd($companies);

             //dd($data_company);


            return view('admin.pages.graduates.edit_jobs', compact('item', 'data_company', 'companies'));
        } catch (\Exception $th) {
            throw $th->getMessage();
        }


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        try {
            // $data = $request->all();

             //dd($data);

            $personParams = $request->only(['name', 'lastname', 'document_type_id', 'document', 'phone', 'telephone', 'email', 'address',
            'birthdate', 'birthdate_place_id']);

            //dd($data );
            $userParams = $request->only(['code']);
            $userParams['email'] = $request->email;

           //   dd($userParams );

          
            
            //$person = $this->personRepository->getByAttribute('email', $request->email);
            $person = $this->personRepository->getById($id);

             /** Searching User */
             //$user = $this->userRepository->getByAttribute('email', $userParams['email']);
            $user = $this->userRepository->getByAttribute('person_id',$person->id);
            // $user = $this->userRepository->getById($person->id);


            

            if(!($request->file('image') == null)) {
                /** Saving Photo */
                $fileParams = $this->saveImage($request);
            }


              //$personParams = array_merge($personParams, $fileParams);
  
              if(!($request->file('image') == null)) {
                  $personParams = array_merge($personParams,  $fileParams);
              }else{
                  $personParams = array_merge($personParams);
              }



            $this->personRepository->update($person, $personParams);

            $this->userRepository->update($user, $userParams);

            
           // DB::beginTransaction();

          //  DB::commit();

            return redirect()->route('admin.graduates.index')->with('alert', ['title' => '¡Éxito!', 'icon' => 'success', 'message' => 'Se ha actualizado correctamente.']);
        } catch (\Exception $th) {
           // DB::rollBack();
           dd($th);
            return redirect()->route('admin.home')->with('alert', ['title' => __('messages.error'), 'icon' => 'error', 'text' => $th->getMessage()]);
        }
    }

    /**
     * @param UpdatePasswordRequest $request
     * @param int $id
     * 
     * @return \Illuminate\Http\Response
     */
    public function update_password(UpdatePasswordRequest $request, $id)
    {
        try {
            $params = $request->all();
            $item = $this->personRepository->getById($id);
            // Hashear el password antes de guardar
            if (!empty($params['password'])) {
                $params['password'] = \Illuminate\Support\Facades\Hash::make($params['password']);
            }
            $this->userRepository->update($item->user, $params);
            return  redirect()->route('admin.graduates.index')->with('alert', [
                'title' => '¡Éxito!',
                'icon' => 'success',
                'message' => 'Se ha actualizado correctamente la contraseña'
            ]);
        } catch (\Exception $th) {
            return back()->with('alert', [
                'title' => '¡Error!',
                'icon' => 'error',
                'message' => 'No se ha podido actualizar correctamente la contraseña'
            ]);
        }
    }


    public function update_academic(UpdateAcademicRequest $request, $id, $id_academic)
    {
        try {


            $params_person_academic = $request->all();

        
             

           $data_academic = $this->personAcademicRepository->getById($id_academic);

             //dd($data_academic);
             $program = $this->programRepository->getById($data_academic->program_id);
            
             //dd($params_person_academic);
             $program_params = $request->only(['program_name', 'academic_level_id']);

             $program_params['name'] = $program_params['program_name'];

            unset($program_params['program_name']);


             $program_params['academic_level_id'] = (int) $program_params['academic_level_id'];

           

            $university_params =$request->only(['university_name']);
            $university_params['name'] = $university_params['university_name'];

            unset($university_params['university_name']);

            $faculty_params =$request->only(['faculty_name']);
            $faculty_params['name'] = $faculty_params['faculty_name'];
            unset($faculty_params['faculty_name']);


            $this->programRepository->update($program, $program_params);
            $this->facultyRepository->update($program->faculty, $faculty_params);
            $this->universityRepository->update($program->faculty->university,$university_params);
            $this->personAcademicRepository->update($data_academic, $params_person_academic);

             

            return  redirect()->route('admin.graduates.show', $id)->with('alert', [
                'title' => '¡Éxito!',
                'icon' => 'success',
                'message' => 'Se ha actualizado correctamente los datos academicos.'
            ]);
        } catch (\Exception $th) {
            dd($th);
            return back()->with('alert', [
                'title' => '¡Error!',
                'icon' => 'error',
                'message' => 'No se ha podido actualizar correctamente los datos academicos.'
            ]);
        }
    }

    public function update_jobs(UpdateJobRequest $request, $id, $id_job){
        try {

         $data_personCompanyParams = $request->only(['company_id', 'salary','in_working', ]);

        
         $personCompany = $this->personCompanyRepository->getById($id_job);


          $this->personCompanyRepository->update($personCompany, $data_personCompanyParams);

      

            return  redirect()->route('admin.graduates.show', $id)->with('alert', [
                'title' => '¡Éxito!',
                'icon' => 'success',
                'message' => 'Se ha actualizado correctamente los datos laborales.'
            ]);
        } catch (\Exception $th) {
            dd($th);
            return back()->with('alert', [
                'title' => '¡Error!',
                'icon' => 'error',
                'message' => 'No se ha podido actualizar correctamente los datos laborales.'
            ]);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $graduate = $this->userRepository->getById($id);

            $person = $this->personRepository->getById($graduate->person_id);
            
            

            DB::beginTransaction();

            $this->personRepository->delete($person);

           DB::commit();
            
           
            return back()->with('alert', ['title' => '¡Éxito!', 'message' => 'Se ha eliminado correctamente.', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return $th->getMessage();
            return back()->with('alert', ['title' => '¡Error!', 'message' => 'No se ha podido eliminar correctamente.', 'icon' => 'error']);
        }

    }

    public function destroy_academic($id, $id_academic)
    {
        try {
            DB::beginTransaction();

            // 1. Primero eliminamos el registro de person_academic
            $personAcademic = $this->personAcademicRepository->getById($id_academic);
            $this->personAcademicRepository->delete($personAcademic);

            // 2. Verificamos si el programa tiene más referencias antes de eliminarlo
            $program = $this->programRepository->getById($personAcademic->program_id);
            $hasMoreReferences = DB::table('person_academic')
                ->where('program_id', $program->id)
                ->exists();

            if (!$hasMoreReferences) {
                // 3. Si no hay más referencias, podemos eliminar el programa
                $faculty = $this->facultyRepository->getById($program->faculty_id);
                $this->programRepository->delete($program);

                // 4. Verificamos si la facultad tiene más programas antes de eliminarla
                $hasMorePrograms = DB::table('programs')
                    ->where('faculty_id', $faculty->id)
                    ->exists();

                if (!$hasMorePrograms) {
                    $this->facultyRepository->delete($faculty);
                }
            }

            DB::commit();
            return back()->with('alert', [
                'title' => '¡Éxito!', 
                'message' => 'Se ha eliminado correctamente el registro académico.', 
                'icon' => 'success'
            ]);

        } catch (\Exception $th) {
            DB::rollBack();
            \Log::error('Error al eliminar registro académico: ' . $th->getMessage());
            return back()->with('alert', [
                'title' => '¡Error!', 
                'message' => 'No se ha podido eliminar el registro académico. Es posible que esté siendo utilizado por otros registros.', 
                'icon' => 'error'
            ]);
        }
    }

    public function destroy_jobs($id, $id_company){
        try {
            $personCompany = $this->personCompanyRepository->getById($id_company);
            

            DB::beginTransaction();

             $this->personCompanyRepository->delete($personCompany);

           DB::commit();
            
           
            return back()->with('alert', ['title' => '¡Éxito!', 'message' => 'Se ha eliminado correctamente.', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return $th->getMessage();
            return back()->with('alert', ['title' => '¡Error!', 'message' => 'No se ha podido eliminar correctamente.', 'icon' => 'error']);
        }
    }

    /**
     * @param StoreRequest $request
     * @param array $params
     */
    public function saveImage($request): array
    {
        $file = $request->file('image');
        $result = $this->s3UploadService->uploadFile($file, 'people');
        
        return [
            'image_url' => dirname($result['url']) . '/',
            'image' => basename($result['url'])
        ];
    }

    public function showMassEmailForm()
    {
        return view('admin.pages.graduates.send_mass_email');
    }

    public function sendMassEmail(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Obtener todos los graduados
        $graduates = $this->personRepository->getOnlyGraduatesAll();
        $totalGraduates = $graduates->count();

        \Log::info('Iniciando envío de correo masivo', [
            'subject' => $request->subject,
            'total_graduados' => $totalGraduates,
            'mail_config' => [
                'driver' => config('mail.default'),
                'mailer' => config('mail.mailer'),
                'host' => config('mail.mailers.smtp.host'),
                'port' => config('mail.mailers.smtp.port'),
                'encryption' => config('mail.mailers.smtp.encryption'),
                'username' => config('mail.mailers.smtp.username'),
                'from_address' => config('mail.from.address'),
                'from_name' => config('mail.from.name')
            ]
        ]);

        if ($totalGraduates === 0) {
            \Log::warning('No se encontraron graduados en la base de datos');
            return redirect()->back()->with('alert', [
                'title' => '¡Advertencia!',
                'icon' => 'warning',
                'message' => 'No se encontraron graduados en la base de datos.'
            ]);
        }

        try {
            foreach ($graduates as $graduate) {
                if (empty($graduate->email)) continue;

                // Encolar el correo usando el mailable MassEmail
                Mail::to($graduate->email)->queue(new MassEmail(
                    $request->subject,
                    $request->message,
                    $graduate,
                    false // includeCredentials
                ));
            }

            Log::info('Correos masivos encolados exitosamente', [
                'total' => $totalGraduates,
                'subject' => $request->subject
            ]);

            return redirect()->back()->with('alert', [
                'title' => '¡Proceso Iniciado!',
                'icon' => 'success',
                'message' => "Se han encolado {$totalGraduates} correos para su envío. Puedes seguir navegando mientras se envían en segundo plano."
            ]);

        } catch (\Exception $e) {
            Log::error('Error al encolar correos masivos', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('alert', [
                'title' => '¡Error!',
                'icon' => 'error',
                'message' => 'Error al procesar los correos: ' . $e->getMessage()
            ])->withInput();
        }
    }

    public function destroy_first_graduates()
    {
        try {
            DB::beginTransaction();

            // Obtener los primeros 110 graduados
            $graduates = $this->personRepository->getOnlyGraduatesAll()->take(110);
            
            Log::info('Iniciando eliminación de graduados', [
                'total_a_eliminar' => $graduates->count()
            ]);

            $eliminados = 0;
            foreach ($graduates as $graduate) {
                try {
                    $this->personRepository->delete($graduate);
                    $eliminados++;
                    Log::info('Graduado eliminado', [
                        'id' => $graduate->id,
                        'nombre' => $graduate->name,
                        'email' => $graduate->email
                    ]);
                } catch (\Exception $e) {
                    Log::error('Error al eliminar graduado', [
                        'id' => $graduate->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            DB::commit();

            Log::info('Proceso de eliminación completado', [
                'total_eliminados' => $eliminados
            ]);

            return back()->with('alert', [
                'title' => '¡Éxito!',
                'icon' => 'success',
                'message' => "Se han eliminado {$eliminados} graduados correctamente."
            ]);

        } catch (\Exception $th) {
            DB::rollBack();
            Log::error('Error en eliminación masiva de graduados', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString()
            ]);
            
            return back()->with('alert', [
                'title' => '¡Error!',
                'icon' => 'error',
                'message' => 'Error al eliminar los graduados: ' . $th->getMessage()
            ]);
        }
    }
}