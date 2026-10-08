<?php

namespace App\Http\Requests;

use App\Models\Etablissement;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEtablissementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        return [
            'nom_efp' => ['required', 'string', 'max:255'],
            'adresse' => ['required', 'string'],
            'ville' => ['required', 'string', 'max:255'],
            'tel' => ['required', 'string', 'max:20'],
            'status' => ['required', 'integer', Rule::in([Etablissement::STATUS_ACTIF, Etablissement::STATUS_INACTIF])],
            'regions_id' => ['required', 'exists:regions,id'],
            'users_id' => ['nullable', 'exists:users,id'],
        ];
    }
}
