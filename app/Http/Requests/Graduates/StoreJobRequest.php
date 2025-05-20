<?php

namespace App\Http\Requests\Graduates;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class StoreJobRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard('admin')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'salary' => ['required', 'string'],
            'in_working' => ['required', 'boolean'],
            'company_id' => ['required'],
            'location_type' => ['required', 'in:api,manual'],
        ];

        // Si se usa la API, validar company_place_id
        if ($this->input('location_type') === 'api') {
            $rules['company_place_id'] = ['required'];
        } else {
            // Si es manual, validar los campos de ubicación manual
            $rules['country_name'] = ['required', 'string'];
            $rules['state_name'] = ['required', 'string'];
            $rules['city_name_manual'] = ['required', 'string'];
        }

        // Si se crea una nueva empresa, validar sus campos
        if ($this->input('company_id') === '-2') {
            $rules['name'] = ['required', 'string'];
            $rules['email'] = ['required', 'email'];
            $rules['address'] = ['required', 'string'];
            $rules['phone'] = ['required', 'string'];
        }

        return $rules;
    }
}
