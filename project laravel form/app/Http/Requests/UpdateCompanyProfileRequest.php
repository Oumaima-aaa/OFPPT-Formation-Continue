<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompanyProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isCompany() && $this->user()->entreprise !== null;
    }

    public function rules(): array
    {
        $entrepriseId = $this->user()->entreprise->id;

        return [
            'raison' => ['required', 'string', 'max:255'],
            'ice' => ['nullable', 'string', 'max:20'],
            'adresse' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('entreprises', 'email')->ignore($entrepriseId)],
            'telephone1' => ['required', 'string', 'max:30'],
            'representant' => ['required', 'string', 'max:255'],
            'contact_email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->user()->id)],
        ];
    }

    public function validatedEntreprise(): array
    {
        return $this->safe()->only(['raison', 'ice', 'adresse', 'email', 'telephone1', 'representant']);
    }

    public function validatedUser(): array
    {
        return ['email' => $this->validated('contact_email')];
    }

    public function attributes(): array
    {
        return [
            'raison' => 'nom de l\'entreprise',
            'representant' => 'personne de contact',
            'contact_email' => 'e-mail de connexion',
            'telephone1' => 'téléphone',
        ];
    }
}
