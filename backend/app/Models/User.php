<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
/** @mixin Builder */
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'facebook_id',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'set_password',
        'google_id',
        'facebook_id',
        'email_verified_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function friends()
    {
        // Get accepted friends where the user is the initiator
        $friendsInitiated = $this->belongsToMany(User::class, 'friends', 'user_id', 'friend_id')
            ->wherePivot('status', 'accepted')
            ->withTimestamps();

        // Get accepted friends where the user is the receiver
        $friendsReceived = $this->belongsToMany(User::class, 'friends', 'friend_id', 'user_id')
            ->wherePivot('status', 'accepted')
            ->withTimestamps();

        // Merge the two relationships and remove duplicates (based on user id)
        return $friendsInitiated->union($friendsReceived)->distinct()->get();
    }
    public function isFriendsWith(User $otherUser): bool
    {
        // Check the 'friends' table directly for an accepted relationship in either direction
        return \Illuminate\Support\Facades\DB::table('friends')
            ->where('status', 'accepted')
            ->where(function ($query) use ($otherUser) {
                $query->where('user_id', $this->id)
                    ->where('friend_id', $otherUser->id);
            })
            ->orWhere(function ($query) use ($otherUser) {
                $query->where('user_id', $otherUser->id)
                    ->where('friend_id', $this->id);
            })
            ->exists();
    }

    public function userProfile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    public function avatar(): MorphOne
    {
        return $this->morphOne(File::class, 'attachable')->where('type', 'avatar');
    }

    /**
     * // Access the user's avatar file
     * $user = User::find(1);
     * $avatarFile = $user->avatar;
     *
     * if ($avatarFile) {
     * // Get the path and display the avatar
     * $avatarPath = $avatarFile->path;
     * }
     */


}
