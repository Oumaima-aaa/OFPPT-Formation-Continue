<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDiplomeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'intervenant_id' => ['sometimes', 'required', 'exists:intervenants,id'],
            'intitule' => ['sometimes', 'required', 'string', 'max:255'],
            'universite' => ['sometimes', 'required', 'string', 'max:255'],
            'annee_obtention' => ['sometimes', 'required', 'integer', 'digits:4', 'min:1900', 'max:'.date('Y')],
            'niveau' => ['sometimes', 'required', 'string', 'max:255'],
            'specialite' => ['nullable', 'string', 'max:255'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->user()?->isTrainer() && $this->user()->intervenant) {
            $this->merge(['intervenant_id' => $this->user()->intervenant->id]);
        }
    }
}
