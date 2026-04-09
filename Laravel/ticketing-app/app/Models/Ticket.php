<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'author_id',
        'title',
        'description',
        'status',
        'priority',
        'type', 
        'estimated_hours'
    ];

    // --- RELATIONS ---

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function assignees(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'ticket_user');
    }

    public function timeEntries(): HasMany
    {
        return $this->hasMany(TimeEntry::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(TicketComment::class)->orderBy('created_at', 'asc');
    }

    // --- LOGIQUE MÉTIER ---

    public function getTotalSpentHoursAttribute(): float
    {
        return (float) $this->timeEntries()->sum('duration');
    }

    public function isUrgent(): bool
    {
        return in_array($this->priority, ['high', 'urgent']);
    }

    // --- DESIGN SYSTEM ---

    public function getStatusBadgeAttribute(): string
    {
        $config = [
            'todo'        => ['label' => 'À faire',   'bg' => '#ebf5fb', 'text' => '#3498db'],
            'in_progress' => ['label' => 'En cours',  'bg' => '#e9f7ef', 'text' => '#27ae60'],
            'in_review'   => ['label' => 'En revue',  'bg' => '#f5eef8', 'text' => '#9b59b6'],
            'completed'   => ['label' => 'Terminé',   'bg' => '#f2f4f4', 'text' => '#7f8c8d']
        ];

        $current = $config[$this->status] ?? ['label' => ucfirst($this->status), 'bg' => '#f2f4f4', 'text' => '#7f8c8d'];

        return "<span style='display: inline-block; white-space: nowrap; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: bold; text-transform: uppercase; border: 1px solid currentColor; color: {$current['text']}; background-color: {$current['bg']};'>{$current['label']}</span>";
    }

    public function getPriorityLabelAttribute(): string
    {
        if ($this->isUrgent()) {
            return "<span style='color: #e74c3c; font-weight: bold;'>🔴 " . ucfirst($this->priority) . "</span>";
        }
        
        return "<span style='color: #2c3e50; font-weight: bold;'>" . ucfirst($this->priority) . "</span>";
    }
}