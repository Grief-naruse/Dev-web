<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à faire cette requête.
     */
    public function authorize(): bool
    {
        return true; // On autorise tout le monde pour l'instant (Spatie gèrera ça plus tard)
    }

    /**
     * Les règles de validation "Enterprise Ready".
     */
    public function rules(): array
    {
        return [
            // Le nom est obligatoire et unique, mais on pourrait le limiter par client plus tard
            'name'           => 'required|string|min:3|max:255',
            'description'    => 'nullable|string|max:1000',
            
            // La sécurité relationnelle : on vérifie que l'ID envoyé correspond à un VRAI client
            'client_id'      => 'required|exists:clients,id',
            
            // On force le statut à être l'une de ces 3 valeurs, rien d'autre
            'status'         => 'required|in:active,completed,on_hold',
            
            // Le forfait d'heures doit être un nombre positif
            'included_hours' => 'required|integer|min:0',
        ];
    }

    /**
     * Les messages d'erreur personnalisés (UX propre).
     */
    public function messages(): array
    {
        return [
            'name.required'           => 'Le nom du projet est obligatoire.',
            'client_id.required'      => 'Vous devez lier ce projet à un client.',
            'client_id.exists'        => 'Le client sélectionné est invalide ou a été supprimé.',
            'status.in'               => 'Le statut sélectionné n\'est pas valide.',
            'included_hours.required' => 'Veuillez définir un forfait d\'heures (mettez 0 si non applicable).',
        ];
    }
}