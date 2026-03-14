<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTimeEntryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            // Le ticket_id viendra directement de l'URL ou d'un champ caché
            'ticket_id'   => 'required|exists:tickets,id',
            
            // La date ne peut pas être dans le futur (on ne prévoit pas le temps de travail à l'avance)
            'date'        => 'required|date|before_or_equal:today',
            
            // La durée doit être un nombre positif (ex: 0.5 pour 30min), maximum 24h d'un coup
            'duration'    => 'required|numeric|min:0.1|max:24',
            
            // La justification est vitale pour la facturation client
            'description' => 'required|string|min:5|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'date.before_or_equal' => 'Vous ne pouvez pas saisir des heures dans le futur.',
            'duration.min'         => 'La durée minimum est de 0.1 heure (6 minutes).',
            'duration.max'         => 'Vous ne pouvez pas saisir plus de 24h en une seule fois.',
            'description.required' => 'Vous devez justifier le travail effectué pour la facturation.',
        ];
    }
}