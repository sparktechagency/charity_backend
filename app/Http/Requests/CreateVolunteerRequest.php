<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateVolunteerRequest extends FormRequest
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
            'email' => 'required|email|unique:volunteers,email',
            'contact_number' => 'required|string|max:255',
            'location' => 'required|string',
            'reason' => 'required|string',
            'donated' => 'nullable|numeric',
            'status' => 'nullable|in:Pending,Approved,Suspended',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ];
    }
}
