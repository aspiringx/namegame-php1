<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'first_name', 'last_name',
        'mobile_phone', 'mobile_phone_carrier',
        'email', 'password'
    ];

    /**
     * Get groups this user belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class)
        ->withPivot(
            'role', 'title', 'member_since', 'is_leader', 'is_active'
        )
        ->withTimestamps();
    }

    /**
     * Get connections with users this user invited.
     *
     * This user's id will match 'user_id' from the relationship table
     * (user_user) below, getting all connected users where they were
     * the inviter.
     *
     * @return BelongsToMany
     */
    public function invitees(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class, 'user_user', 'user_id', 'user_id_invitee'
        )
        ->withPivot('group_id', 'relationship')
        ->withTimestamps();
    }

    /**
     * Get connections with users where others invited them to connect.
     *
     * This user's id will match 'user_id_invitee' from the relationship
     * table (user_user) below, getting all users where they were the
     * invitee.
     *
     * @return BelongsToMany
     */
    public function inviters(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class, 'user_user', 'user_id_invitee', 'user_id'
        )->withPivot('group_id', 'relationship')
        ->withTimestamps();
    }

    /**
     * TODOs:
     * 1. Ensure people provide a mobile number and have it verified before they can
     *    join a sub-group. Allows sub-group leader to include them on group
     *    text messages.
     * 2. Require a photo to join a group (upload existing or snap with camera).
     * 3. Ensure email is verified before user is visible.
     */
}
