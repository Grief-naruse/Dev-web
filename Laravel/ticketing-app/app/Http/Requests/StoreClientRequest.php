<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        // On récupère l'ID du client depuis l'URL si on est en mode "Update"
        $clientId = $this->route('client') ? $this->route('client')->id : null;

        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                Rule::unique('clients', 'name')->ignore($clientId),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom de l\'entreprise est obligatoire.',
            'name.unique'   => 'Ce client existe déjà dans notre base.',
            'name.min'      => 'Le nom doit faire au moins 3 caractères.',
        ];
    }
}