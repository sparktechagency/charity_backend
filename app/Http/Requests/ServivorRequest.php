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
                'transaction_id'=>'required',
                'payment_type'=>'required',
                'donation_type'=>'required',
                'frequency'=>'nullable',
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'remark' => 'nullable|string|max:65,535',
                'amount' => 'numeric',
                'phone_number' => 'nullable|max:100',
        ];
    }
}
