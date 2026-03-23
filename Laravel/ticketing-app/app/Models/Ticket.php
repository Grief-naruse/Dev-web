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

    public function comments()
    {
        // Relation vers les messages du chat
        return $this->hasMany(TicketComment::class)->orderBy('created_at', 'asc');
    }

    public function assignees()
    {
        return $this->belongsToMany(User::class, 'ticket_user');
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

    // --- DESIGN SYSTEM (Accessors pour l'UI) ---

    /**
     * Génère le badge HTML du Statut avec la bonne couleur
     */
    public function getStatusBadgeAttribute()
    {
        $config = [
            'todo'        => ['label' => 'À faire',   'bg' => '#ebf5fb', 'text' => '#3498db'],
            'in_progress' => ['label' => 'En cours',  'bg' => '#e9f7ef', 'text' => '#27ae60'],
            'in_review'   => ['label' => 'En revue',  'bg' => '#f5eef8', 'text' => '#9b59b6'],
            'completed'   => ['label' => 'Terminé',   'bg' => '#f2f4f4', 'text' => '#7f8c8d']
        ];

        $current = $config[$this->status] ?? ['label' => ucfirst($this->status), 'bg' => '#f2f4f4', 'text' => '#7f8c8d'];

        // ✨ Ajout de display: inline-block; et white-space: nowrap; pour empêcher la coupure
        return "<span style='display: inline-block; white-space: nowrap; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: bold; text-transform: uppercase; border: 1px solid currentColor; color: {$current['text']}; background-color: {$current['bg']};'>{$current['label']}</span>";
    }

    /**
     * Génère le label HTML de la Priorité avec l'icône
     */
    public function getPriorityLabelAttribute()
    {
        if ($this->isUrgent()) {
            return "<span style='color: #e74c3c; font-weight: bold;'>🔴 " . ucfirst($this->priority) . "</span>";
        }
        
        return "<span style='color: #2c3e50; font-weight: bold;'>" . ucfirst($this->priority) . "</span>";
    }
}