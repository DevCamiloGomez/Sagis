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

        $this->role = $this->roleRepository->getByAttribute('name', 'graduate');
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
                Mail::to($person->email)->queue(new MessageReceived($person,$userParams));
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
    public function store(StoreRequest $request)
    {
        try {

            DB::beginTransaction();

        
            if(!($request->file('image') == null)) {
                         /** Saving Photo */
                         $fileParams = $this->saveImage($request);
            }
   

            /** Creating Person */
            $personParams = $request->except(['code', 'company_email', 'image', '_token']);
            //$personParams = array_merge($personParams, $fileParams);

            if(!($request->file('image') == null)) {
                $personParams = array_merge($personParams,  $fileParams);
            }else{
                $personParams = array_merge($personParams);
            }
           

            $this->personRepository->create($personParams);

            /** Searching created Person */
            $person = $this->personRepository->getByAttribute('email', $request->email);

            /** Creating User */
            $userParams = $request->only(['code', 'company_email']);

            $userParams['email'] = $userParams['company_email'];
            $userParams['person_id'] = $person->id;
            $userParams['password'] = 'password';
      

            unset($userParams['company_email']);

            $this->userRepository->create($userParams);

            /**Creating PersonAcademic */
            $personAcademicParams = $request->only( ['person_id', 'program_id', 'year']);
            $personAcademicParams['person_id'] = $person->id;

           // $pregrade = $this->programRepository->getByAttribute('level_id', 1);
           // $programs = $this->progmamRepository->getByAttribute('name', "Programa de Ingeniería de Sistema");
           $programs = $this->programRepository->first()->id;

           // dd($programs);
            $personAcademicParams['program_id'] = $programs;
            $personAcademicParams['year'] = 2000;

            $this->personAcademicRepository->create($personAcademicParams);


            /** Searching User */
            $user = $this->userRepository->getByAttribute('email', $userParams['email']);
            
            //$user->roles()->attach($this->role);
            //dd($user->roles()->sync($this->role));
            $user->roles()->sync($this->role);

            Mail::to($person->email)->queue(new MessageReceived($person, $userParams));

            DB::commit();
            return redirect()->route('admin.graduates.index')->with('alert', ['title' => '¡Éxito!', 'icon' => 'success', 'message' => 'Se ha registrado correctamente.']);
        } catch (\Exception $th) {
            DB::rollBack();
            dd($th);
            return back()->with('alert', ['title' => '¡Error!', 'icon' => 'error', 'message' => 'No se ha registrado correctamente.']);
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


    public function store_jobs(StoreJobRequest $request, $id)
    {
        try {

            DB::beginTransaction();


                $data = $request->all();

                //dd($data);

                $city_id = 0;
                if($data['company_place_id']== "-2"){
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
                    $city_id = (int)$data['company_place_id'];
                }

                /* Compañia */
                //dd($data['company_id']);
                $company_id =0;
                if($data['company_id']== "-2"){
                    $companyParams = $request->only('name', 'email', 'address',  'phone');
                    $companyParams['city_id'] = $city_id;
                   // unset($companyParams['company_place_id']);
                   // dd($companyParams);
                     /* Con esta consulta se comprueba si la compañia que ingreso el usuario existe, si existe devuelve la compañia sino NULL */
                    $company= $this->companyRepository->getModelName($companyParams['name']);

                    //dd($company);

                     /* Si es NULL crea la COMPAÑIA  */
                 if(is_null($company))  $this->companyRepository->create($companyParams);

                 

                $company_id =$this->companyRepository->getModelID($companyParams['name']);

               // dd($company_id);

                }else{
                    $company_id = (int)$data['company_id'];

                }

                 $paramsPersonCompany = $request->only(['company_id', 'salary', 'in_working']);
                 $paramsPersonCompany['company_id'] =  $company_id;
                 $paramsPersonCompany['person_id'] = (int)$id;
                 $paramsPersonCompany['in_working'] = (int)$paramsPersonCompany['in_working'] ;
                 $paramsPersonCompany['start_date'] =  date('Y-m-d H:i:s');
                // dd($paramsPersonCompany);

                 
                  $this->personCompanyRepository->create($paramsPersonCompany);

            DB::commit();
            return redirect()->route('admin.graduates.show', $id)->with('alert', ['title' => '¡Éxito!', 'icon' => 'success', 'message' => 'Se ha registrado correctamente.']);
        } catch (\Exception $th) {
            DB::rollBack();
            dd($th);
            return back()->with('alert', ['title' => '¡Error!', 'icon' => 'error', 'message' => 'Se ha registrado correctamente.']);
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
            $userParams = $request->only(['code', 'company_email']);
            $userParams['email'] = $userParams['company_email'];

            unset($userParams['company_email']);

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

          //  dd($params);
          $item = $this->personRepository->getById($id);

          //dd($item->user);
           // $item = $this->userRepository->getById($id);
            //dd($item);

            $this->userRepository->update($item->user, $params);

            return  redirect()->route('admin.graduates.index')->with('alert', [
                'title' => '¡Éxito!',
                'icon' => 'success',
                'message' => 'Se ha actualizado correctamente la contraseña'
            ]);
        } catch (\Exception $th) {
            dd($th);
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
}