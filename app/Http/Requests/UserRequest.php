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
        $user = \Request::segments();


//        dd($this->getMethod());

//        dd($user);

        if (isset($user[2]))
        {
            $id =','.$user[2];
//            .isset($user[2])?$user[2]:[]
        }else{
            $id='';
        }
        $rules=  [
            'name' => 'required|max:50',
            'username' => 'required|max:20|unique:users,username'.$id,
            'email' => 'required|email|max:50|unique:users,email'.$id,
            'password' => 'required|min:6',
            'roles' => 'required|exists:roles,name',
        ];


        return $rules;
    }
}
