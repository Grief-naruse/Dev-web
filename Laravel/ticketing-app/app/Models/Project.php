<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id', 
        'name', 
        'description', 
        'included_hours', 
        'hourly_rate', 
        'status'
    ];

    // --- RELATIONS ---

    /**
     * Le client (entreprise) propriétaire du projet.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * L'équipe de collaborateurs assignée à ce projet.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Tous les tickets créés dans le cadre de ce projet.
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    // --- LOGIQUE ENTREPRISE (Calculs en temps réel) ---

    /**
     * Calcule le cumul des heures saisies sur tous les tickets du projet.
     */
    public function getTotalSpentHoursAttribute(): float
    {
        // On récupère toutes les timeEntries de tous les tickets et on somme la durée
        return (float) $this->tickets->flatMap->timeEntries->sum('duration');
    }

    /**
     * Détermine les heures restant sur le forfait.
     */
    public function getRemainingHoursAttribute(): float
    {
        return $this->included_hours - $this->total_spent_hours;
    }
}