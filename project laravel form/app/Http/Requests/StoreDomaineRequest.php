<?php

namespace App\Http\Requests;

use App\Models\Domaine;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDomaineRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        return [
            'nom_domaine' => ['required', 'string', 'max:255', 'unique:domaines'],
            'status' => ['required', 'integer', Rule::in([Domaine::STATUS_ACTIF, Domaine::STATUS_INACTIF])],
        ];
    }
}
