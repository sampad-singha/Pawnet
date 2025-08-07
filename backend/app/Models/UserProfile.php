<?php

namespace App\Models;

use Database\Factories\UserProfileFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/* @mixin Builder */
class UserProfile extends Model
{
    /** @use HasFactory<UserProfileFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bio',
        'date_of_birth',
        'gender',
        'phone_number',
        'phone_verified',
        'address',
        'city',
        'state',
        'country',
        'visibility',
    ];
    protected $casts = [
        'date_of_birth' => 'date',
        'visibility' => 'string',
    ];

    /**
     * Get the user that owns the profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
