<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard('web')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(){
        return [
            'name' => ['required', 'string'],
            'lastname' => ['required', 'string'],
            'document_type_id' => ['required', 'exists:document_types,id'],
            'document' => ['required', Rule::unique('people', 'document')->ignore((int) graduate_user()->person_id), 'between:6,12'],
            'birthdate' => ['required', 'date'],
            'birthdate_place_id' => ['required', 'exists:cities,id'],
            'phone' => ['required', 'string', 'min:10'],
            'telephone' => ['required', 'string', 'min:6'],
            'address' => ['required', 'string'],
            'code' => ['required', 'numeric', Rule::unique('users', 'code')->ignore((int) graduate_user()->id), 'between:100000,9999999'],
            'email' => ['required', 'email', Rule::unique('people', 'email')->ignore((int) graduate_user()->person_id)],
             'company_email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore((int) graduate_user()->id)
            ],
            'image' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:5120']
        ];
    }
}
