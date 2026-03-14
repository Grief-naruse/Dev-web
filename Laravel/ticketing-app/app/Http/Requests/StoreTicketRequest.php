<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Spatie gèrera les droits plus tard
    }

    public function rules(): array
    {
        return [
            // 1. Informations de base
            'title'       => 'required|string|min:3|max:255',
            'description' => 'nullable|string|max:2000',
            
            // 2. Rattachement OBLIGATOIRE à un projet existant
            'project_id'  => 'required|exists:projects,id',
            
            // 3. Niveau d'urgence strict
            'priority'    => 'required|in:low,medium,high,urgent',
            
            // 4. Statut d'avancement strict
            'status'      => 'required|in:todo,in_progress,in_review,completed',
            
            // (L'assignation à l'utilisateur et les heures viendront dans une future mise à jour)
            'estimated_hours' => 'nullable|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'      => 'Le titre du ticket est obligatoire.',
            'project_id.required' => 'Ce ticket doit impérativement être rattaché à un projet.',
            'project_id.exists'   => 'Le projet sélectionné est introuvable.',
            'priority.in'         => 'Le niveau d\'urgence est invalide.',
            'status.in'           => 'Le statut sélectionné n\'est pas reconnu.',
        ];
    }
}