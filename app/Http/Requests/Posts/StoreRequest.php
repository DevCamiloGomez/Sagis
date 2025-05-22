<?php

namespace App\Http\Requests\Posts;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
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
        return [
            'post_category_id' => ['required', 'exists:post_categories,id'],
            'title' => ['required', 'string'],
            'description' => ['required', 'string'],
            'date' => ['required', 'date'],
            'image' => ["array","max:5"], 
            'image.*' => ['required', 'image', 'mimes:png,jpg,jpeg, array, max:5']
        ];
    }
}
