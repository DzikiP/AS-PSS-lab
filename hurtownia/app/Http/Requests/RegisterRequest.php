<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'username' => 'required|string|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ];
    }
}
