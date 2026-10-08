<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateIntervenantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        return [
            'matricule' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('intervenants', 'matricule')->ignore($this->route('intervenant')),
            ],
            'nom' => ['sometimes', 'required', 'string', 'max:255'],
            'prenom' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => [
                'sometimes',
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('intervenants', 'email')->ignore($this->route('intervenant')),
            ],
            'telephone' => ['nullable', 'string', 'max:50'],
            'adresse' => ['nullable', 'string'],
            'date_naissance' => ['nullable', 'date'],
            'genre' => ['sometimes', 'required', 'in:M,F'],
            'type_intervenant' => ['sometimes', 'required', 'in:interne,externe'],
            'etablissements_id' => ['sometimes', 'required', 'integer', 'exists:etablissements,id'],
        ];
    }
}
