<?php

namespace App\Http\Requests;

use App\Models\Domaine;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDomaineRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        return [
            'nom_domaine' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('domaines', 'nom_domaine')->ignore($this->route('domaine')),
            ],
            'status' => ['sometimes', 'required', 'integer', Rule::in([Domaine::STATUS_ACTIF, Domaine::STATUS_INACTIF])],
        ];
    }
}
