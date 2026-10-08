<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRegionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        return [
            'nom_region' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('regions', 'nom_region')->ignore($this->route('region')),
            ],
            'users_id' => ['nullable', 'exists:users,id'],
        ];
    }
}
