<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Mail\MessageReceived;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Repositories\CityRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Repositories\AdminRepository;
use App\Repositories\StateRepository;
use App\Repositories\PersonRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\CountryRepository;
use App\Repositories\FacultyRepository;
use App\Repositories\ProgramRepository;
use App\Services\S3UploadService;
use Illuminate\Support\Facades\Storage;
use App\Repositories\UniversityRepository;
use App\Http\Requests\Admins\UpdateRequest;
use App\Http\Requests\Admins\StoreRequest;
use App\Repositories\DocumentTypeRepository;
use App\Repositories\PersonCompanyRepository;
use App\Repositories\PersonAcademicRepository;
use App\Http\Requests\Graduates\UpdatePasswordRequest;

/**
 * Controlador AdminController - Gestión administrativa del sistema
 * 
 * Este controlador maneja todas las operaciones administrativas del sistema,
 * incluyendo la gestión de usuarios, graduados y configuraciones generales.
 * 
 * Características principales:
 * - Gestión de usuarios administradores
 * - Administración de graduados
 * - Configuración del sistema
 * - Gestión de roles y permisos
 * - Reportes y estadísticas
 * 
 * Middleware:
 * - auth:admin : Requiere autenticación de administrador
 * 
 * Repositorios utilizados:
 * - AdminRepository: Gestión de administradores
 * - UserRepository: Gestión de usuarios
 * - PersonRepository: Gestión de información personal
 * - RoleRepository: Gestión de roles
 * - Otros repositorios de soporte
 * 
 * Servicios:
 * - S3UploadService: Servicio de carga de archivos a AWS S3
 * 
 *  
 */
class AdminController extends Controller
{

    /**
     * @var AdminRepository
     */
    protected $adminRepository;
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
        AdminRepository $adminRepository,
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
        $this->adminRepository = $adminRepository;
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
        $this->role = $this->roleRepository->getByAttribute('name', 'superadmin');
    /*     $this->role = $this->roleRepository->getByAttribute('name', 'graduate'); */
    }


        public function settings(){
            try {
              
                /** Cantidad de Admins */
                $getCountAdmins = $this->adminRepository->getByRole('superadmin')->count();
                $admin = $this->adminRepository->getById(admin_user()->id);  

                $admins = $this->adminRepository->getByRole('superadmin');
              
                return view('admin.pages.settings', compact( 'getCountAdmins', 'admin', 'admins'));
            } catch (\Exception $th) {
                throw $th;
            }

            
        }

        
    
        public function edit(){
            try {
                $item = $this->personRepository->getById(admin_user()->person_id);

                //dd($item);
               
    
                $documentTypes = $this->documentTypeRepository->all();
                $cities = $this->cityRepository->allOrderBy('countries.id');
    
                $academics = $item->personAcademic;
    
                $laborales = $item->personCompany;
    
                return view('admin.pages.edit', compact('item', 'documentTypes', 'cities', 'academics', 'laborales'));
            } catch (\Exception $th) {
                throw $th->getMessage();
            }
    
    
        }

       public function edit_admin($id){
            try {
                $item = $this->personRepository->getById($id);

                //dd($item);
               
    
                $documentTypes = $this->documentTypeRepository->all();
                $cities = $this->cityRepository->allOrderBy('countries.id');
    
                $academics = $item->personAcademic;
    
                $laborales = $item->personCompany;
    
                return view('admin.pages.edit_admin', compact('item', 'documentTypes', 'cities', 'academics', 'laborales'));
            } catch (\Exception $th) {
                throw $th->getMessage();
            }


        }
    
        public function edit_password(){
            try {
                $item = $this->personRepository->getById(admin_user()->person_id);
    
                return view('admin.pages.edit_password', compact('item'));
            } catch (\Exception $th) {
                //throw $th;
            }
    
        }

        public function edit_password_admin($id){
            try {
                $item = $this->personRepository->getById($id);
    
                return view('admin.pages.edit_password_admin', compact('item'));
            } catch (\Exception $th) {
                //throw $th;
            }
    
        }


        public function update(UpdateRequest $request)
        {
            try {
                //$data = $request->all();
    
                $personParams = $request->only(['name', 'lastname', 'document_type_id', 'document', 'phone', 'telephone', 'email', 'address',
                'birthdate', 'birthdate_place_id']);
    
                $personParams['has_data_person'] = 1;

                //dd($personParams);
    
                $adminParams = $request->only(['company_email']);
                $adminParams['email'] = $adminParams['company_email'];
    
                unset($adminParams['company_email']);

                
                
    
    
                $person = $this->personRepository->getById(admin_user()->person_id);
    
                 // Searching admin 
                $admin = $this->adminRepository->getByAttribute('person_id',$person->id);

               // dd($admin);
    
    
    
                
                if(!($request->file('image') == null)) {
                     //Saving Photo 
                    $fileParams = $this->saveImage($request);
                }
    
    
                  //$personParams = array_merge($personParams, $fileParams);
      
                  if(!($request->file('image') == null)) {
                      $personParams = array_merge($personParams,  $fileParams);
                  }else{
                      $personParams = array_merge($personParams);
                  }
    
                $this->personRepository->update($person, $personParams);
    
                $this->adminRepository->update($admin, $adminParams);
    
                
               // DB::beginTransaction();
    
              //  DB::commit();
    
                return redirect()->route('admin.settings')->with('alert', ['title' => '¡Éxito!', 'icon' => 'success', 'message' => 'Se ha actualizado correctamente.']);
            } catch (\Exception $th) {
                dd($th);
               // DB::rollBack();
                return redirect()->route('admin.settings')->with('alert', ['title' => __('messages.error'), 'icon' => 'error', 'text' => $th->getMessage()]);
            }
        }


        public function update_admin(UpdateRequest $request, $id)
        {
            try {
                //$data = $request->all();
    
                $personParams = $request->only(['name', 'lastname', 'document_type_id', 'document', 'phone', 'telephone', 'email', 'address',
                'birthdate', 'birthdate_place_id']);
    
                $personParams['has_data_person'] = 1;

             
    
                $adminParams = $request->only(['company_email']);
                $adminParams['email'] = $adminParams['company_email'];
    
                unset($adminParams['company_email']);

    
                $person = $this->personRepository->getById($id);
            
                 // Searching admin 
                $admin = $this->adminRepository->getByAttribute('person_id',$person->id);
           
    
                
                if(!($request->file('image') == null)) {
                     //Saving Photo 
                    $fileParams = $this->saveImage($request);
                }
    
    
                  //$personParams = array_merge($personParams, $fileParams);
      
                  if(!($request->file('image') == null)) {
                      $personParams = array_merge($personParams,  $fileParams);
                  }else{
                      $personParams = array_merge($personParams);
                  }
    
                $this->personRepository->update($person, $personParams);
    
                $this->adminRepository->update($admin, $adminParams);
    
                
               // DB::beginTransaction();
    
              //  DB::commit();
    
                return redirect()->route('admin.settings')->with('alert', ['title' => '¡Éxito!', 'icon' => 'success', 'message' => 'Se ha actualizado correctamente.']);
            } catch (\Exception $th) {
                dd($th);
               // DB::rollBack();
                return redirect()->route('admin.settings')->with('alert', ['title' => __('messages.error'), 'icon' => 'error', 'text' => $th->getMessage()]);
            }
        }

        
    
        
        public function update_password(UpdatePasswordRequest $request)
        {
            try {
             $params = $request->all();
              $item = $this->personRepository->getById(admin_user()->person_id);

    
                $this->adminRepository->update($item->admin, $params);
    
                return  redirect()->route('admin.settings')->with('alert', [
                    'title' => '¡Éxito!',
                    'icon' => 'success',
                    'message' => 'Se ha actualizado correctamente la contraseña.'
                ]);
            } catch (\Exception $th) {
                dd($th);
                return back()->with('alert', [
                    'title' => '¡Error!',
                    'icon' => 'error',
                    'message' => 'No se ha podido actualizar correctamente la contraseña.'
                ]);
            }
        }

        public function update_password_admin(UpdatePasswordRequest $request, $id)
        {
            try {
             $params = $request->all();
              $item = $this->personRepository->getById($id);
             
                $this->adminRepository->update($item->admin, $params);
    
                return  redirect()->route('admin.settings')->with('alert', [
                    'title' => '¡Éxito!',
                    'icon' => 'success',
                    'message' => 'Se ha actualizado correctamente la contraseña.'
                ]);
            } catch (\Exception $th) {
                dd($th);
                return back()->with('alert', [
                    'title' => '¡Error!',
                    'icon' => 'error',
                    'message' => 'No se ha podido actualizar correctamente la contraseña.'
                ]);
            }
        }


        public function create_admin()
        {
        try {
            $documentTypes = $this->documentTypeRepository->all();
            $cities = $this->cityRepository->allOrderBy('countries.id');

            return view('admin.pages.create_admin', compact('documentTypes', 'cities'));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function store_admin(StoreRequest $request)
    {
        try {

            /* DB::beginTransaction(); */

        
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

            /** Creating Admin */
            $adminParams = $request->only(['company_email']);

            $adminParams['email'] = $adminParams['company_email'];
            $adminParams['person_id'] = $person->id;
            $adminParams['password'] = 'password';
            unset($adminParams['company_email']);


            $this->adminRepository->create($adminParams);

            /**Creating PersonAcademic */
           /*  $personAcademicParams = $request->only( ['person_id', 'program_id', 'year']);
            $personAcademicParams['person_id'] = $person->id;

    
           $programs = $this->programRepository->first()->id;

         
            $personAcademicParams['program_id'] = $programs;
            $personAcademicParams['year'] = 2000;

            $this->personAcademicRepository->create($personAcademicParams); */


            /** Searching Admin */
            $admin = $this->adminRepository->getByAttribute('email', $adminParams['email']);
            
            //$user->roles()->attach($this->role);
            $admin->roles()->sync($this->role);

           /*  Mail::to($person->email)->queue(new MessageReceived($person, $userParams)); */

            DB::commit();
            return redirect()->route('admin.settings')->with('alert', ['title' => '¡Éxito!', 'icon' => 'success', 'message' => 'Se ha registrado correctamente.']);
        } catch (\Exception $th) {
            DB::rollBack();
            //dd($th);
            return back()->with('alert', ['title' => '¡Error!', 'icon' => 'error', 'message' => 'No se ha registrado correctamente.']);
        }
    }

    public function destroy_admin($id)
    {
        try {
            $admin = $this->adminRepository->getById($id);
            $person = $this->personRepository->getById($admin->person_id);

            DB::beginTransaction();

            $this->personRepository->delete($person);

           DB::commit();
            
           
            return back()->with('alert', ['title' => '¡Éxito!', 'message' => 'Se ha eliminado correctamente.', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return back()->with('alert', ['title' => '¡Error!', 'message' => 'No se ha podido eliminar correctamente.', 'icon' => 'error']);
        }

    }





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
