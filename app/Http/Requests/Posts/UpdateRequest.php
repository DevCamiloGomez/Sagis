<?php

namespace App\Http\Requests\Posts;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
        $id = $this->post;

        return [
            'post_category_id' => ['required', 'exists:post_categories,id'],
            'title' => ['required', Rule::unique('posts', 'title')->where('post_category_id', $this->get('post_category_id'))->ignore($id)],
            'description' => ['required', 'string'],
            'date' => ['required', 'date']
        ];
    }
}
