<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
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
//    public function friends()
//    {
//        return $this->belongsToMany(User::class, 'friends', 'user_id', 'friend_id')
//            ->wherePivot('status', 'accepted')
//            ->orWherePivot('status', 'accepted')
//            ->where(function($query) {
//                $query->where('user_id', $this->id)
//                    ->orWhere('friend_id', $this->id);
//            })
//            ->withTimestamps()
//            ->get();
//    }
    public function pendingSentRequests()
    {
        return $this->hasMany(Friend::class, 'user_id')
            ->where('status', 'pending');
    }
    public function pendingReceivedRequests()
    {
        return $this->hasMany(Friend::class, 'friend_id')
            ->where('status', 'pending');
    }
    public function blockedUsers()
    {
        return $this->belongsToMany(User::class, 'friends', 'user_id', 'friend_id')
            ->wherePivot('status', 'blocked')
            ->withTimestamps();
    }

}
