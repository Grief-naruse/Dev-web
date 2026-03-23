<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketComment extends Model
{
    // On autorise ces colonnes à être remplies
    protected $fillable = ['ticket_id', 'user_id', 'content'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function ticket() {
        return $this->belongsTo(Ticket::class);
    }
}