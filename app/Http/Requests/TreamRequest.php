<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TreamRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'work_experience' => 'required|string|max:255',
            'photo' => 'nullable|image|max:10240',
            'twitter_link' => 'nullable|string',
            'linkedIn_link' => 'nullable|string',
            'instagram_link' => 'nullable|string',
        ];
    }
}
