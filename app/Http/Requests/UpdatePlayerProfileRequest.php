<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePlayerProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check(); // speler mag z'n eigen profiel aanpassen
    }

    public function rules(): array
    {
        return [
            'number' => ['nullable','integer','min:0','max:999'],
            'position' => ['nullable','string','max:50'],
            'bio' => ['nullable','string','max:1000'],
            'avatar' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
        ];
    }
}
