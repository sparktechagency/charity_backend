<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'full_name' =>'required|string|max:255',
            'email' =>'required|email|unique:users,email,except,id',
            'password' => [
            'required',
            'string',
            'min:6',
            'max:32',
            'confirmed',
            function ($attribute, $value, $fail) {
                if (!preg_match('/[A-Z]/', $value)) {
                    $fail('Password must include an uppercase letter.');
                }
                if (!preg_match('/[a-z]/', $value)) {
                    $fail('Password must include a lowercase letter.');
                }
                if (!preg_match('/[0-9]/', $value)) {
                    $fail('Password must include a number.');
                }
            },
        ],
        'image'=>'nullable|image|mimes:png,jpg,jpeg,svg|max:10240',
       
        ];
    }
}
