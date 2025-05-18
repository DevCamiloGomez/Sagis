<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Repositories\CityRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Repositories\StateRepository;
use App\Repositories\PersonRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\CountryRepository;
use App\Repositories\FacultyRepository;
use App\Repositories\ProgramRepository;
use App\Repositories\UniversityRepository;
use App\Repositories\DocumentTypeRepository;
use App\Repositories\PersonCompanyRepository;
use App\Repositories\PersonAcademicRepository;
use App\Http\Requests\Auth\ProfileUpdateRequest;
use App\Http\Requests\Graduates\StoreJobRequest;
use App\Http\Requests\Graduates\UpdateJobRequest;
use App\Http\Requests\Graduates\StoreAcademicRequest;
use App\Http\Requests\Graduates\UpdateAcademicRequest;
use App\Http\Requests\Graduates\UpdatePasswordRequest;
use App\Http\Requests\Graduates\UpdateAcademicRequestFirst;
use App\Services\S3UploadService;

class UserController extends Controller
{
    /** @var UserRepository */
    protected $userRepository;

    /** @var PersonRepository */
    protected $personRepository;

     /** @var DocumentTypeRepository */
     protected $documentTypeRepository;

     /** @var CityRepository */
     protected $cityRepository;

      /** @var PersonAcademicRepository */
      protected $personAcademicRepository;

      /** @var RoleRepository */
    protected $roleRepository;

      /** @var StateRepository */
      protected $stateRepository;

      /** @var CountryRepository */
      protected $countryRepository;


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

     /** @var S3UploadService */
     protected $s3UploadService;

    public function __construct(
        UserRepository $userRepository,
        PersonRepository $personRepository,
        DocumentTypeRepository $documentTypeRepository,
        PersonAcademicRepository $personAcademicRepository, 
        CountryRepository $countryRepository,
        RoleRepository $roleRepository,
        StateRepository $stateRepository,
        CityRepository $cityRepository,
        ProgramRepository $programRepository,
        UniversityRepository $universityRepository,
        FacultyRepository $facultyRepository,
        PersonCompanyRepository $personCompanyRepository,
        CompanyRepository $companyRepository,
        S3UploadService $s3UploadService
    ) {
        $this->middleware('auth:web');

        $this->userRepository = $userRepository;
        $this->personRepository = $personRepository;
        $this->documentTypeRepository = $documentTypeRepository;
        $this->cityRepository = $cityRepository;
        $this->personAcademicRepository = $personAcademicRepository;
        $this->roleRepository = $roleRepository;
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

    public function profile()
    {
        try {
            $user = $this->userRepository->getById(Auth::guard('web')->user()->id);     

            return view('pages.profile');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function update(ProfileUpdateRequest $request)
    {
        try {
            //$data = $request->all();

            $personParams = $request->only(['name', 'lastname', 'document_type_id', 'document', 'phone', 'telephone', 'email', 'address',
            'birthdate', 'birthdate_place_id']);

            $personParams['has_data_person'] = 1;

            $userParams = $request->only(['code', 'company_email']);
            $userParams['email'] = $userParams['company_email'];

            unset($userParams['company_email']);
            


            $person = $this->personRepository->getById(graduate_user()->person_id);

             /** Searching User */
            $user = $this->userRepository->getByAttribute('person_id',$person->id);



            
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

            return redirect()->route('profile')->with('alert', ['title' => '¡Éxito!', 'icon' => 'success', 'message' => 'Se ha actualizado correctamente.']);
        } catch (\Exception $th) {
           // DB::rollBack();
            return redirect()->route('home')->with('alert', ['title' => __('messages.error'), 'icon' => 'error', 'text' => $th->getMessage()]);
        }
    }

    public function edit(){
        try {
            $item = $this->personRepository->getById(graduate_user()->person_id);
           

            $documentTypes = $this->documentTypeRepository->all();
            $cities = $this->cityRepository->allOrderBy('countries.id');

            //dd($cities);

            $academics = $item->personAcademic;

            $laborales = $item->personCompany;

            return view('pages.edit', compact('item', 'documentTypes', 'cities', 'academics', 'laborales'));
        } catch (\Exception $th) {
            throw $th->getMessage();
        }


    }

    public function edit_password(){
        try {
            $item = $this->personRepository->getById(graduate_user()->person_id);

            return view('pages.edit_password', compact('item'));
        } catch (\Exception $th) {
            //throw $th;
        }

    }

    /**
     * @param UpdatePasswordRequest $request
     * @param int $id
     * 
     * @return \Illuminate\Http\Response
     */
    public function update_password(UpdatePasswordRequest $request)
    {
        try {
            $params = $request->all();

          $item = $this->personRepository->getById(graduate_user()->person_id);

            $this->userRepository->update($item->user, $params);

            return  redirect()->route('profile')->with('alert', [
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

    public function validate_person(){
        try {
            $person = $this->personRepository->getById(graduate_user()->person_id);
    
            $data_personParams['has_data_person'] = 1;
    
            DB::beginTransaction();
    
    
                $this->personRepository->update($person, $data_personParams);
    
    
           DB::commit();
            
           
            return back()->with('alert', ['title' => '¡Éxito!', 'message' => 'Se ha verificado correctamente.', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return $th->getMessage();
            return back()->with('alert', ['title' => '¡Error!', 'message' => 'No se ha podido verificar correctamente.', 'icon' => 'error']);
        }
    
    }

    public function show_academics(){
        try {
            $item = $this->personRepository->getById(graduate_user()->person_id);

            $academics = $item->personAcademic;

            $laborales = $item->personCompany;

            return view('pages.show_academics', compact('item', 'academics', 'laborales'));
        } catch (\Exception $th) {
            throw $th->getMessage();
        }
    }


    public function create_academic(){
        try {
          
            $item = $this->personRepository->getById(graduate_user()->person_id);


            $users = $this->userRepository->getByRole($this->role->name);

            //dd($users);
            $cities = $this->cityRepository->allOrderBy('countries.id');
            $academic_levels =  $this->personAcademicRepository->getNiveles();

            $academics = $item->personAcademic;


            return view('pages.create_academic', compact('item',  'cities', 'academics',  'users', 'academic_levels'));
    } catch (\Throwable $th) {
        throw $th;
    }
    }

    public function store_academic(StoreAcademicRequest $request){
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

    
                  $this->facultyRepository->create($facultyParams);


                $faculty_id = $this->facultyRepository->getModelID($facultyParams['name']);
                //dd($faculty_id );

                $program_params = $request->only('program_name', 'academic_level_id');
                $program_params['name'] = $program_params['program_name'];
                unset($program_params['program_name']);
                $program_params['academic_level_id'] = (int)$program_params['academic_level_id'];
                $program_params['faculty_id'] = $faculty_id;


                  
                  $this->programRepository->create($program_params);

                  $personAcademic_params = $request->only('year');
                  $program_id = $this->programRepository->getModelID($program_params['name']);
                  $personAcademic_params['program_id'] = $program_id ;
                  $personAcademic_params['person_id'] = (int) graduate_user()->person_id;


                  $this->personAcademicRepository->create( $personAcademic_params);

                  $person = $this->personRepository->getById(graduate_user()->person_id);
    
                  $data_personParams['has_data_academic'] = 1;
  
                  $this->personRepository->update($person, $data_personParams);
            DB::commit();
            return redirect()->route('academics')->with('alert', ['title' => '¡Éxito!', 'icon' => 'success', 'message' => 'Se ha registrado correctamente.']);
        } catch (\Exception $th) {
            DB::rollBack();
            dd($th);
            return back()->with('alert', ['title' => '¡Error!', 'icon' => 'error', 'message' => 'Se ha registrado correctamente.']);
        }


    }


    public function edit_academic($id_academic)
    {
        try {
            $item = $this->personRepository->getById(graduate_user()->person_id);

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

            return view('pages.edit_academic', compact('item', 'documentTypes', 'cities', 'academics', 'laborales', 'users', 'academics_full', 'data_academic', 'programs_full', 'academic_levels'));
        } catch (\Exception $th) {
            throw $th->getMessage();
        }
    }

    public function edit_academic_first($id_academic){
        try {
            $item = $this->personRepository->getById(graduate_user()->person_id);
            $users = $this->userRepository->getByRole($this->role->name);
            $documentTypes = $this->documentTypeRepository->all();
            $cities = $this->cityRepository->allOrderBy('countries.id');
            $academics_full = $this->personAcademicRepository->getUni();
             $data_academic = $this->personAcademicRepository->getById($id_academic);
             $programs_full = $this->personAcademicRepository->getProgramas();
             $academic_levels =  $this->personAcademicRepository->getNiveles();
            $academics = $item->personAcademic;
            $laborales = $item->personCompany;

            return view('pages.edit_academic_first', compact('item', 'documentTypes', 'cities', 'academics', 'laborales', 'users', 'academics_full', 'data_academic', 'programs_full', 'academic_levels'));
        } catch (\Exception $th) {
            throw $th->getMessage();
        }


    }


    public function update_academic(UpdateAcademicRequest $request, $id_academic)
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

            $person = $this->personRepository->getById(graduate_user()->person_id);
    
            $data_personParams['has_data_academic'] = 1;
    
    
    
    
            $this->personRepository->update($person, $data_personParams);


            $this->programRepository->update($program, $program_params);
            $this->facultyRepository->update($program->faculty, $faculty_params);
            $this->universityRepository->update($program->faculty->university,$university_params);
            $this->personAcademicRepository->update($data_academic, $params_person_academic);

             

            return  redirect()->route('academics')->with('alert', [
                'title' => '¡Éxito!',
                'icon' => 'success',
                'message' => 'Se ha actualizado correctamente los datos académicos.'
            ]);
        } catch (\Exception $th) {
            dd($th);
            return back()->with('alert', [
                'title' => '¡Error!',
                'icon' => 'error',
                'message' => 'No se ha podido actualizar correctamente los datos académicos.'
            ]);
        }
    }


    public function update_academic_first(UpdateAcademicRequestFirst $request, $id_academic)
    {
        try {


            $params_person_academic = $request->all();
           // dd($params_person_academic);

           $data_academic = $this->personAcademicRepository->getById($id_academic);

           //dd($params_person_academic);


            $person = $this->personRepository->getById(graduate_user()->person_id);
            $data_personParams['has_data_academic'] = 1;
    
    
    
    
            $this->personRepository->update($person, $data_personParams);
            $this->personAcademicRepository->update($data_academic, $params_person_academic);

             

            return  redirect()->route('academics')->with('alert', [
                'title' => '¡Éxito!',
                'icon' => 'success',
                'message' => 'Se ha actualizado correctamente los datos académicos.'
            ]);
        } catch (\Exception $th) {
            dd($th);
            return back()->with('alert', [
                'title' => '¡Error!',
                'icon' => 'error',
                'message' => 'No se ha podido actualizar correctamente los datos académicos.'
            ]);
        }
    }

    public function destroy_academic($id_academic){
        try {

            

            $personAcademic = $this->personAcademicRepository->getById($id_academic);
            $program = $this->programRepository->getById($personAcademic->program_id);
            $faculty = $this->facultyRepository->getById($program->faculty_id);
            $person = $this->personRepository->getById(graduate_user()->person_id);
    
            $data_personParams['has_data_academic'] = 1;

    
    
                
  

            DB::beginTransaction();

             $this->personAcademicRepository->delete($personAcademic);
             $this->programRepository->delete($program);
             $this->facultyRepository->delete($faculty);
             $this->personRepository->update($person, $data_personParams);

           DB::commit();
            
           
            return back()->with('alert', ['title' => '¡Éxito!', 'message' => 'Se ha eliminado correctamente.', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return $th->getMessage();
            return back()->with('alert', ['title' => '¡Error!', 'message' => 'No se ha podido eliminar correctamente.', 'icon' => 'error']);
        }


    }


    public function validate_academic(){
        try {
            $person = $this->personRepository->getById(graduate_user()->person_id);
    
            $data_personParams['has_data_academic'] = 1;
    
            DB::beginTransaction();
    
    
                $this->personRepository->update($person, $data_personParams);
    
    
           DB::commit();
            
           
            return back()->with('alert', ['title' => '¡Éxito!', 'message' => 'Se ha verificado correctamente.', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return $th->getMessage();
            return back()->with('alert', ['title' => '¡Error!', 'message' => 'No se ha podido verificar correctamente.', 'icon' => 'error']);
        }
    
    }

    public function show_jobs(){
        try {
            $item = $this->personRepository->getById(graduate_user()->person_id);

            $academics = $item->personAcademic;

            $laborales = $item->personCompany;

            return view('pages.show_jobs', compact('item', 'academics', 'laborales'));
        } catch (\Exception $th) {
            throw $th->getMessage();
        }
    }

    public function create_jobs()
    {
        try {
          
                $item = $this->personRepository->getById(graduate_user()->person_id);

    
                $users = $this->userRepository->getByRole($this->role->name);

                $companies =  $this->personCompanyRepository->getCompanies();
                $cities = $this->cityRepository->allOrderBy('countries.id');

    
                return view('pages.create_jobs', compact('item',  'cities',   'users', 'companies'));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function store_jobs(StoreJobRequest $request)
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
                 $paramsPersonCompany['person_id'] = (int)graduate_user()->person_id;
                 $paramsPersonCompany['in_working'] = (int)$paramsPersonCompany['in_working'] ;
                 $paramsPersonCompany['start_date'] =  date('Y-m-d H:i:s');
                // dd($paramsPersonCompany);

                $person = $this->personRepository->getById(graduate_user()->person_id);

                $data_personParams['has_data_company'] = 1;
           
           
                 $this->personRepository->update($person, $data_personParams);

                 
                  $this->personCompanyRepository->create($paramsPersonCompany);

            DB::commit();
            return redirect()->route('jobs')->with('alert', ['title' => '¡Éxito!', 'icon' => 'success', 'message' => 'Se ha registrado correctamente.']);
        } catch (\Exception $th) {
            DB::rollBack();
            dd($th);
            return back()->with('alert', ['title' => '¡Error!', 'icon' => 'error', 'message' => 'Se ha registrado correctamente.']);
        }
    }

    public function edit_jobs($id_company){

        try {
            $item = $this->personRepository->getById(graduate_user()->person_id);
             $data_company = $this->personCompanyRepository->getById($id_company);
             $companies =  $this->personCompanyRepository->getCompanies();
       
            return view('pages.edit_jobs', compact('item', 'data_company', 'companies'));
        } catch (\Exception $th) {
            throw $th->getMessage();
        }

}

public function update_jobs(UpdateJobRequest $request,  $id_job){
    try {

     $data_personCompanyParams = $request->only(['company_id', 'salary','in_working', ]);

    
     $personCompany = $this->personCompanyRepository->getById($id_job);

     $person = $this->personRepository->getById(graduate_user()->person_id);

     $data_personParams['has_data_company'] = 1;


      $this->personCompanyRepository->update($personCompany, $data_personCompanyParams);
      $this->personRepository->update($person, $data_personParams);

  

        return  redirect()->route('jobs')->with('alert', [
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

public function destroy_jobs( $id_company){
    try {
        $personCompany = $this->personCompanyRepository->getById($id_company);
        
        $person = $this->personRepository->getById(graduate_user()->person_id);

        $data_personParams['has_data_company'] = 1;

        DB::beginTransaction();

         $this->personCompanyRepository->delete($personCompany);
         $this->personRepository->update($person, $data_personParams);

       DB::commit();
        
       
        return back()->with('alert', ['title' => '¡Éxito!', 'message' => 'Se ha eliminado correctamente.', 'icon' => 'success']);
    } catch (\Exception $th) {
        DB::rollBack();
        return $th->getMessage();
        return back()->with('alert', ['title' => '¡Error!', 'message' => 'No se ha podido eliminar correctamente.', 'icon' => 'error']);
    }
}

public function validate_jobs(){
    try {
        $person = $this->personRepository->getById(graduate_user()->person_id);

        $data_personParams['has_data_company'] = 1;

        DB::beginTransaction();


            $this->personRepository->update($person, $data_personParams);


       DB::commit();
        
       
        return back()->with('alert', ['title' => '¡Éxito!', 'message' => 'Se ha verificado correctamente.', 'icon' => 'success']);
    } catch (\Exception $th) {
        DB::rollBack();
        return $th->getMessage();
        return back()->with('alert', ['title' => '¡Error!', 'message' => 'No se ha podido verificar correctamente.', 'icon' => 'error']);
    }

}

/**
     * 
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
