<?php

namespace App\Http\Requests;

use App\Models\Plan;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        $isCompany = $this->user()?->isCompany();
        $canSetStatus = $this->user()?->isRegionalManager() || $this->user()?->isLocalManager();

        return [
            'exercice' => ['required', 'integer', 'min:2000', 'max:2100'],
            'etablissements_id' => ['required', 'exists:etablissements,id'],
            'themes_id' => ['required', 'exists:themes,id'],
            'nbjours' => ['required', 'integer', 'min:1'],
            'nbparticipantmaxi' => ['required', 'integer', 'min:1'],
            'nb_groupes' => ['required', 'integer', 'min:1'],
            'date_debut_previsionnelle' => ['nullable', 'date'],
            'cout_previsionnel' => ['required', 'numeric', 'min:0'],
            'status' => [$canSetStatus ? 'required' : 'nullable', 'integer', Rule::in(array_keys(Plan::statusLabels()))],
        ];
    }
}
