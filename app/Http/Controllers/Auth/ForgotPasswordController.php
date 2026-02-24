<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    public function showLinkRequestForm()
    {
        return view('dev.auth.passwords.email');
    }

    public function broker()
    {
        return \Illuminate\Support\Facades\Password::broker('users');
    }
}
