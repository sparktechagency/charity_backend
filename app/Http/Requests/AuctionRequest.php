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


    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'donate_share' => 'required|numeric',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'contact_number' => 'required|string|max:100',
            'city' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'profile' => 'nullable|image|mimes:jpeg,png,jpg,pdf',
            'payment_type'=>'nullable|string',
            'card_number'=>'nullable'
        ];
    }
}
