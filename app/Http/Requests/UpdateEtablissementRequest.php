<?php

namespace App\Http\Requests;

use App\Models\Etablissement;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEtablissementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        return [
            'nom_efp' => ['sometimes', 'required', 'string', 'max:255'],
            'adresse' => ['sometimes', 'required', 'string'],
            'ville' => ['sometimes', 'required', 'string', 'max:255'],
            'tel' => ['sometimes', 'required', 'string', 'max:20'],
            'status' => ['sometimes', 'required', 'integer', Rule::in([Etablissement::STATUS_ACTIF, Etablissement::STATUS_INACTIF])],
            'regions_id' => ['sometimes', 'required', 'exists:regions,id'],
            'users_id' => ['nullable', 'exists:users,id'],
        ];
    }
}
