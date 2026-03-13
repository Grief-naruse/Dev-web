<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    use HasFactory;

    // Autorise l'insertion en masse pour ces colonnes
    protected $fillable = [
        'project_id', 'title', 'description', 
        'status', 'priority', 'estimated_hours', 'spent_hours'
    ];

    /**
     * Relation : Un ticket appartient à un projet.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Calcul automatique : Heures restantes
     * Accessible dans Blade via : $ticket->remaining_hours
     */
    public function getRemainingHoursAttribute(): float
    {
        $remaining = $this->estimated_hours - $this->spent_hours;
        // Si on a passé plus de temps que prévu, il reste 0 heure (pas de négatif)
        return $remaining > 0 ? (float) $remaining : 0.0;
    }

    /**
     * Calcul automatique : Heures à facturer
     * Accessible dans Blade via : $ticket->billable_hours
     */
    public function getBillableHoursAttribute(): float
    {
        return (float) $this->spent_hours;
    }
}