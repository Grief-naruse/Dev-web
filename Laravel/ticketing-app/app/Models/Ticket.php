<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'project_id',
        'author_id',
        'assigned_to',
        'title',
        'description',
        'status',
        'priority',
        'type', // 'included' ou 'billable'
        'estimated_hours'
    ];

    // --- RELATIONS ---

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * L'utilisateur qui a ouvert le ticket.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Le collaborateur chargé de résoudre le ticket.
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Historique des temps passés sur ce ticket précis.
     */
    public function timeEntries(): HasMany
    {
        return $this->hasMany(TimeEntry::class);
    }

    // --- LOGIQUE MÉTIER (Business Logic) ---

    /**
     * Somme des heures réellement travaillées sur ce ticket.
     */
    public function getTotalSpentHoursAttribute(): float
    {
        return (float) $this->timeEntries()->sum('duration');
    }

    /**
     * Détermine si le ticket est considéré comme critique.
     */
    public function isUrgent(): bool
    {
        return in_array($this->priority, ['high', 'urgent']);
    }
}