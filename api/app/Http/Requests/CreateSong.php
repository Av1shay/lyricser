<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSong extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|unique:songs|max:255',
            'writer' => 'required|max:50',
            'composers' => 'required|string',
            'performers' => 'required|string',
            'published_at' => 'required|date',
            'stanzas_delimiter' => 'required|string|size:1',
            'file' => 'file|max:10|mimetypes:text/plain' // max size in KB
        ];
    }
}
