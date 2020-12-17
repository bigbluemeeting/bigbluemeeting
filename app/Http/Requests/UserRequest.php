<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:50',
            'username' => 'required|max:20|unique:users,username',
            'email' => 'required|email|max:50|unique:users,email',
            'password' => 'required|min:6',
            'roles' => 'required|exists:roles,name',
        ];
    }
}
