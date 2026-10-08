<?php

namespace App\Http\Requests;

use App\Models\Theme;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateThemeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        return [
            'intitule_theme' => ['sometimes', 'required', 'string', 'max:255'],
            'domaines_id' => ['sometimes', 'required', 'exists:domaines,id'],
            'duree_formation' => ['sometimes', 'required', 'integer', 'min:1'],
            'nbparticipantmaxi' => ['sometimes', 'required', 'integer', 'min:1'],
            'status' => ['sometimes', 'required', 'integer', Rule::in([Theme::STATUS_ACTIF, Theme::STATUS_INACTIF])],
        ];
    }
}
