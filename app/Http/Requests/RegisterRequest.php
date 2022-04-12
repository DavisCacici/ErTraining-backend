<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'user_name' => 'required',
            'email'    => 'required|email|unique:App\Models\User,email',
            'password' => 'required',
            'role_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'email.unique' => "l'email è stata già usata",
        ];
    }
}
