<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimeEntry extends Model
{
    use HasFactory;

    // Les champs qu'on autorise à remplir via un formulaire
    protected $fillable = [
        'ticket_id',
        'user_id',
        'date',
        'duration',
        'description',
    ];

    // On force 'date' à être un objet Carbon (pour pouvoir faire format('d/m/Y') dans les vues)
    protected $casts = [
        'date' => 'date',
        'duration' => 'decimal:2',
    ];

    /**
     * Une saisie de temps appartient à un Ticket.
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Une saisie de temps est effectuée par un Utilisateur.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function timeEntries()
    {
        return $this->hasMany(TimeEntry::class);
    }
}