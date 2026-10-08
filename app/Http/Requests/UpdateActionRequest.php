<?php

namespace App\Http\Requests;

use App\Models\Action;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateActionRequest extends FormRequest
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
            'themes_id' => ['sometimes', 'required', 'exists:themes,id'],
            'entreprises_id' => ['sometimes', 'required', 'exists:entreprises,id'],
            'etablissements_id' => ['sometimes', 'required', 'exists:etablissements,id'],
            'date_debut' => ['sometimes', 'required', 'date'],
            'date_fin' => ['sometimes', 'required', 'date', 'after:date_debut'],
            'prix_reel' => ['sometimes', 'required', 'numeric', 'min:0'],
            'status' => ['sometimes', 'required', 'integer', Rule::in(array_keys(Action::statusLabels()))],
        ];
    }
}
