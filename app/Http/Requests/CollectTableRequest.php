<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CollectTableRequest extends FormRequest
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
                'name' => 'required|string|max:255|min:3',
                'email' => 'required|email|max:255|min:3',
                'item_name' => 'required|string|max:255|min:3',
                'description' => 'required|string|max:65535|min:5',
                'images' => 'required|array|max:3',
                'images.*' => 'mimes:jpeg,jpg,png,pdf|max:10240',
        ];
    }
}
