<?php

namespace App\Http\Requests\Graduates;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAcademicRequestFirst extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Permitir acceso si el usuario es administrador o si es el propio graduado
        if (Auth::guard('admin')->check()) {
            return true;
        }
        if (Auth::guard('web')->check()) {
            $academicId = $this->route('academic');
            $academic = \App\Models\PersonAcademic::find($academicId);
            return $academic && Auth::guard('web')->user()->person_id == $academic->person_id;
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'year' => ['required', 'string', 'min:4', 'max:4'],
        ];
    }
}
