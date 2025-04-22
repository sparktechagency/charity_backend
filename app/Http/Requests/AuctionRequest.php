<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuctionRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,pdf',
            'donate_share' => 'required|numeric',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'contact_number' => 'required|string|max:100',
            'city' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'profile' => 'nullable|image|mimes:jpeg,png,jpg,pdf',
            'payment_type'=>'required|in:card','apple_pay','google_pay','paypal_pay',
            'card_number'=>'required'
        ];
    }
}
