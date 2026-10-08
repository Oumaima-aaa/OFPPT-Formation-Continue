<?php

namespace App\Http\Requests;

use App\Models\Entreprise;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEntrepriseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        return [
            'raison' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'site' => ['nullable', 'url', 'max:255'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'telephone1' => ['required', 'string', 'max:20'],
            'telephone2' => ['nullable', 'string', 'max:20'],
            'telephone3' => ['nullable', 'string', 'max:20'],
            'representant' => ['required', 'string', 'max:255'],
            'status' => ['required', 'integer', Rule::in([Entreprise::STATUS_ACTIF, Entreprise::STATUS_INACTIF])],
            'users_id' => ['nullable', 'exists:users,id'],
        ];
    }
}
