<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimeEntry extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'ticket_id',
        'user_id',
        'duration',
        'work_date',
        'comment'
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'work_date' => 'date',
        'duration'  => 'float',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Le collaborateur qui a déclaré ses heures.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}