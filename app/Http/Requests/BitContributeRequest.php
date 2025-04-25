<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BitContributeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'auction_id' => 'required|exists:auctions,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'contact_number' => 'required|string|max:100',
            'bit_online' => 'required|numeric',
            'payment_type' => 'required',
            'card_number' => 'required|string|max:255',
        ];
    }
}
