<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Une entreprise cliente peut avoir plusieurs projets (Contrats).
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }
    /**
     * Les comptes utilisateurs rattachés à cette entreprise.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}