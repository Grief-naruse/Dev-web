<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    // --- RELATIONS ---

    /**
     * Projets sur lesquels ce collaborateur travaille.
     */
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class);
    }

    /**
     * Tickets assignés à cet utilisateur (en tant que responsable).
     */
    public function assignedTickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'assigned_to');
    }

    /**
     * Temps total saisi par cet utilisateur sur l'ensemble du système.
     */
    public function timeEntries(): HasMany
    {
        return $this->hasMany(TimeEntry::class);
    }

    // --- LOGIQUE MÉTIER (Helpers) ---

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isCollaborator(): bool
    {
        return $this->role === 'collaborator';
    }

    public function isClient(): bool
    {
        return $this->role === 'client';
    }
}