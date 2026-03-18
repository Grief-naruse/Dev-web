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

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name', 'email', 'password', 'role','client_id', 'avatar' // <-- AJOUT d'avatar ICI
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
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

    public function assignedTickets()
    {
        return $this->belongsToMany(Ticket::class, 'ticket_user');
    }
    public function timeEntries(): HasMany
    {
        return $this->hasMany(TimeEntry::class);
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

    /**
     * ✨ NOUVEAU HELPER ENTERPRISE : Génère l'URL de l'avatar ou renvoie null
     */
    public function avatarUrl()
    {
        if ($this->avatar) {
            // Laravel sert les fichiers publics via le disk 'public' qu'on verra après
            return asset('storage/avatars/' . $this->avatar);
        }
        return null; // Pas d'avatar, on gérera le placeholder dans la vue
    }
    /**
     * L'entreprise (Client) à laquelle cet utilisateur appartient.
     */
    public function clientEnterprise()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
    
}