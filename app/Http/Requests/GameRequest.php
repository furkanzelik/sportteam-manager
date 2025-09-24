<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GameRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'home_team_id' => ['required','exists:teams,id','different:away_team_id'],
            'away_team_id' => ['required','exists:teams,id'],
            'starts_at'    => ['required','date','after:now'],
            'location'     => ['nullable','string','max:120'],
        ];
    }
}
