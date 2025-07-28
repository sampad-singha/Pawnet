<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    /** @use HasFactory<\Database\Factories\FriendFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id', 'friend_id', 'status', 'accepted_at', 'blocked_at', 'rejected_at', 'requested_at'
    ];

    /**
     * Get the user associated with the friend record.
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the friend associated with the friend record.
     */
    public function friend(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'friend_id');
    }
}
