<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\CountryRepository;
use App\Repositories\PersonAcademicRepository;
use App\Repositories\PersonCompanyRepository;
use App\Repositories\PersonRepository;
use App\Repositories\PostRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Faker\Generator as Faker;
class HomeController extends  Controller
{
    /** @var PersonRepository */
    protected $personRepository;

    /** @var UserRepository */
    protected $userRepository;

    /** @var RoleRepository */
    protected $roleRepository;

    /** @var PostRepository */
    protected $postRepository;

    /** @var CountryRepository */
    protected $countryRepository;

    /** @var PersonCompanyRepository */
    protected $personCompanyRepository;

    /** @var PersonAcademicRepository */
    protected $personAcademicRepository;

    /** @var Faker */
    protected $faker;

    public function __construct(
        PersonRepository $personRepository,
        UserRepository $userRepository,
        RoleRepository $roleRepository,
        PostRepository $postRepository,
        CountryRepository $countryRepository,
        PersonCompanyRepository $personCompanyRepository,
        PersonAcademicRepository $personAcademicRepository,
        Faker $faker,
    ) {
        $this->middleware('auth:admin');

        $this->personRepository = $personRepository;
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
        $this->postRepository = $postRepository;
        $this->countryRepository = $countryRepository;
        $this->personCompanyRepository = $personCompanyRepository;
        $this->personAcademicRepository = $personAcademicRepository;
        $this->faker = $faker;
    }

    public function home()
    {
        try {
            /* Número total de pots */
            $posts = $this->postRepository->getTotalPosts()->count();
           // dd($pots);

           /* Numero de noticias */
           $postNotices = $this->postRepository->getPotsNoticias()->count();

            /** Graduados en el Extranjero */
            $extraGraduates = $this->personCompanyRepository->searchExtranjeroGraduates()->count();

            /* Graduados en Colombia */
            $graduadosColombia = $this->personCompanyRepository->getGraduadosTrabajanEnColombia()->count();

            /** Cantidad de Graduados */
            $graduates = $this->userRepository->getByRole('graduado')->count();

             /** With Job */
             $graduadosConTrabajo = $this->personCompanyRepository->graduadosConTrabajo();

             /* Graduados sin Trabajo */
             $graduadoSinTrabajo = $graduates  -$graduadosConTrabajo;

             /* Graduados con Posts */
             $graduadosConPosts = $this->personAcademicRepository->getGraduatesPost()->count();

            /* Graduados verificados */
            $graduadosVerificados = $this->personRepository->getCantidadVerificados()->count();




            return view('admin.pages.home', compact(
                'extraGraduates',
                'graduates',
                'graduadosConTrabajo',  
                'posts',
                'graduadosColombia',
                'postNotices',
                'graduadosConPosts',
                'graduadosVerificados'
            ));
        } catch (\Exception $th) {
            throw $th;
        }
    }
}
