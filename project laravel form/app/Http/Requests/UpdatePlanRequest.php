<?php

namespace App\Http\Requests;

use App\Models\Plan;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        return [
            'exercice' => ['sometimes', 'required', 'integer', 'min:2000', 'max:2100'],
            'etablissements_id' => ['sometimes', 'required', 'exists:etablissements,id'],
            'themes_id' => ['sometimes', 'required', 'exists:themes,id'],
            'nbjours' => ['sometimes', 'required', 'integer', 'min:1'],
            'nbparticipantmaxi' => ['sometimes', 'required', 'integer', 'min:1'],
            'nb_groupes' => ['sometimes', 'required', 'integer', 'min:1'],
            'date_debut_previsionnelle' => ['nullable', 'date'],
            'cout_previsionnel' => ['sometimes', 'required', 'numeric', 'min:0'],
            'status' => ['sometimes', 'required', 'integer', Rule::in(array_keys(Plan::statusLabels()))],
        ];
    }
}
