<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCertificationRequest extends FormRequest
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
            'intervenant_id' => ['required', 'exists:intervenants,id'],
            'code' => ['nullable', 'string', 'max:255'],
            'intitule' => ['required', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'max:255'],
            'domaine' => ['nullable', 'string', 'max:255'],
            'organisme' => ['nullable', 'string', 'max:255'],
            'date_obtention' => ['nullable', 'date'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->user()?->isTrainer() && $this->user()->intervenant) {
            $this->merge(['intervenant_id' => $this->user()->intervenant->id]);
        }
    }
}
