<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'string|min:1',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:1'
        ];
    }
    // public function messages()
    // {
    //     return [
    //         'name.required' => 'User name required.',
    //         'email.required' => 'Email required.',
    //         'password.required' => 'Password required.'
    //     ];
    // }
}
