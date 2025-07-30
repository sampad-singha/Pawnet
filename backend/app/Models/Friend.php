<?php

namespace App\Models;

use Database\Factories\FriendFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/** @mixin Builder */
class Friend extends Model
{
    /** @use HasFactory<FriendFactory> */

    use HasFactory;

    protected $fillable = [
        'user_id', 'friend_id', 'status', 'accepted_at', 'blocked_at', 'rejected_at', 'requested_at'
    ];

    /**
     * Get the user associated with the friend record.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the friend associated with the friend record.
     */
    public function friend(): BelongsTo
    {
        return $this->belongsTo(User::class, 'friend_id');
    }
}
