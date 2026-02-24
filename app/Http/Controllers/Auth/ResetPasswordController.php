<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    protected $redirectTo = '/';

    public function showResetForm(Request $request, $token = null)
    {
        return view('dev.auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    public function broker()
    {
        return \Illuminate\Support\Facades\Password::broker('users');
    }

    protected function setUserPassword($user, $password)
    {
        $user->password = \Illuminate\Support\Facades\Hash::make($password);
        $user->save();
    }
}
