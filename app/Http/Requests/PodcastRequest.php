<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PodcastRequest extends FormRequest
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
            'podcast_id'     => 'sometimes|exists:podcast_stores,id', // only for update
            'podcast_title'  => 'required|string|max:255',
            'host_title'     => 'required|string|max:255',
            'guest_title'    => 'required|string|max:255',
            'host_profile'   => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',  // 10MB
            'guest_profile'  => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',  // 10MB
            'description'    => 'nullable|string|max:65535',
            'mp3'            => $this->isMethod('post') ?
                                'required|mimetypes:audio/mpeg,audio/mp3|max:1024000' :
                                'nullable|mimetypes:audio/mpeg,audio/mp3|max:1024000',
            'thumbnail'      => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // 2MB
        ];
    }
}
