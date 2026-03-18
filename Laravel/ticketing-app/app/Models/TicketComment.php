<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketComment extends Model
{
    protected $fillable = [
        'ticket_id',
        'user_id',
        'content',
    ];

    /**
     * Le ticket sur lequel ce commentaire a été posté.
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * L'utilisateur qui a écrit ce commentaire.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}