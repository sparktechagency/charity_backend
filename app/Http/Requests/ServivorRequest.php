<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServivorRequest extends FormRequest
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
                'donation_type'=>'required|in:luxurious,online_pay',
                'name' => 'required|string',
                'email' => 'required|email',
                'address' => 'nullable|string',
                'amount' => 'numeric',
                'status' => 'string|in:Paid,Pending,Failed',
        ];
    }
}
