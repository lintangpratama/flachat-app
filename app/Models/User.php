<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Room;
use App\traits\GenUid;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use League\CommonMark\Delimiter\Delimiter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Staudenmeir\LaravelMergedRelations\Eloquent\HasMergedRelationships;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, GenUid, HasMergedRelationships;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function canJoinRoom($roomId)
    {
        $granted = false;
        # code...
        $room = Room::findOrFail($roomId);
        $user = explode( ":", $room->users);
        foreach ($user as $id) {
            # code...\if
            if ($this->id == $id) {
                # code...
                $granted = true;
            }
        }
        return $granted;
    }

    public function friendsTo()
    {
        return $this->belongsToMany(User::class, 'friends', 'user_id', 'friend_id')
            ->withPivot('accepted')
            ->withTimestamps();
    }

    public function friendsFrom()
    {
        return $this->belongsToMany(User::class, 'friends', 'friend_id', 'user_id')
            ->withPivot('accepted')
            ->withTimestamps();
    }

    public function pendingFriendsTo()
{
    return $this->friendsTo()->wherePivot('accepted', false);
}

public function pendingFriendsFrom()
{
    return $this->friendsFrom()->wherePivot('accepted', false);
}

public function acceptedFriendsTo()
{
    return $this->friendsTo()->wherePivot('accepted', true);
}

public function acceptedFriendsFrom()
{
    return $this->friendsFrom()->wherePivot('accepted', true);
}

public function friends()
{
    return $this->mergedRelationWithModel(User::class, 'friends_view');
}
}
