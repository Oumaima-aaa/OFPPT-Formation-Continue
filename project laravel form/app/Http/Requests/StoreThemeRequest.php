<?php

namespace App\Http\Requests;

use App\Models\Theme;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreThemeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        return [
            'intitule_theme' => ['required', 'string', 'max:255'],
            'domaines_id' => ['required', 'exists:domaines,id'],
            'duree_formation' => ['required', 'integer', 'min:1'],
            'nbparticipantmaxi' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'integer', Rule::in([Theme::STATUS_ACTIF, Theme::STATUS_INACTIF])],
        ];
    }
}
