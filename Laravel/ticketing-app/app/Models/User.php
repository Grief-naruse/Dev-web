<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Ajout pour la relation clientEnterprise

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'client_id',
        'avatar'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // --- RELATIONS ---

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class);
    }

    public function assignedTickets(): BelongsToMany
    {
        return $this->belongsToMany(Ticket::class, 'ticket_user');
    }

    public function timeEntries(): HasMany
    {
        return $this->hasMany(TimeEntry::class);
    }

    public function clientEnterprise(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    // --- LOGIQUE MÉTIER & HELPERS ---

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

    public function avatarUrl(): ?string
    {
        return $this->avatar ? asset('storage/avatars/' . $this->avatar) : null;
    }
}