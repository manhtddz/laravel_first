<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'max:225', 'email'],
            'password' => ['required']
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email can not be blank',
            'email.email' => 'Wrong email format',

            'password.required' => 'Password can not be blank'
        ];
    }
}
