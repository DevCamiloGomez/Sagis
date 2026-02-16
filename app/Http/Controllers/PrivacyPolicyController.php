<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrivacyPolicyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function show()
    {
        if (Auth::user()->accepted_privacy_policy) {
            return redirect()->route('home');
        }

        return view('pages.privacy-policy');
    }

    public function accept(Request $request)
    {
        $user = Auth::user();
        $user->accepted_privacy_policy = true;
        $user->save();

        return redirect()->route('home')->with('alert', [
            'title' => '¡Éxito!',
            'icon' => 'success',
            'message' => 'Has aceptado la política de tratamiento de datos personales.'
        ]);
    }
}
