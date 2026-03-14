<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // On autorise tout le monde pour l'instant (on verra les rôles plus tard)
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:255|unique:clients,name',
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