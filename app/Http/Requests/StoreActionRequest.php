<?php

namespace App\Http\Requests;

use App\Models\Action;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreActionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        $canSetStatus = $this->user()?->isRegionalManager() || $this->user()?->isLocalManager();

        return [
            'exercice' => ['required', 'integer', 'min:2000', 'max:2100'],
            'themes_id' => ['required', 'exists:themes,id'],
            'entreprises_id' => ['required', 'exists:entreprises,id'],
            'etablissements_id' => ['required', 'exists:etablissements,id'],
            'date_debut' => ['required', 'date'],
            'date_fin' => ['required', 'date', 'after:date_debut'],
            'prix_reel' => ['required', 'numeric', 'min:0'],
            'status' => [$canSetStatus ? 'required' : 'nullable', 'integer', Rule::in(array_keys(Action::statusLabels()))],
        ];
    }
}
