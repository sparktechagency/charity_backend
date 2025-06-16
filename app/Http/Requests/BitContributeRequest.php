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
            'bit_online' => 'required|numeric',
        ];
    }
}
