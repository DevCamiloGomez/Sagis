<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use App\Providers\RouteServiceProvider;

use App\Repositories\RoleRepository;
use App\Repositories\AdminRepository;

use App\Traits\Auth\Admin\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME_ADMIN;

    /** @var RoleRepository */
    protected $roleRepository;

    /** @var AdminRepository */
    protected $adminRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        RoleRepository $roleRepository,
        AdminRepository $adminRepository
    ) {
        $this->middleware('guest:admin')->only('showLoginForm');
        $this->roleRepository = $roleRepository;
        $this->adminRepository = $adminRepository;
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        // Obtener el rol de administrador
        $role = $this->roleRepository->getByAttribute('name', 'admin');
        return view('admin.auth.login', compact('role'));
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendLoginResponse(Request $request)
    {
        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }

        // Asignar automáticamente el rol de administrador
        $role = $this->roleRepository->getByAttribute('name', 'admin');
        $this->saveRoleSession($role->id);

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect()->route('admin.home');
    }


    /**
     * @param Request $request
     * @param int $role_id
     */
    protected function saveRoleSession($role_id)
    {
        $role = $this->roleRepository->getById($role_id);
        session()->put('role_admin', $role);
    }


    protected function deleteRoleSession()
    {
        session()->forget('role_admin');
    }
}
