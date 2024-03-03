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
     * User can have many groups
     *
     * Gets all groups this user belongs to. In the user's
     * profile, they'll be able to see this list as an indented
     * hierarchy of groups, sub-groups, etc. they belong to,
     * allowing them to switch between groups.
     *
     * Leaders of any group should be visible to new people who join
     * before they've directly "connected". This ensures both new
     * people and leaders can use this like a reminder to meet personally
     * and connect. Leaders can see everyone who joins their group or
     * sub-group since it's usually their job to welcome people.
     *
     * Anyone who does this (whether they have a formal title or not)
     * is a "leader" and should be given "is_leader" permissions on
     * their group_user record.
     *
     * In a large group of 50+ people, it's hard for people to quickly
     * meet. Multiple layers of nested sub-groups are where it's at. If
     * someone participates in a sub-group (aka, team, class, etc.),
     * it should be a trusted space where any group member can see
     * everyone else (names and photos) to quickly learn everyone's names
     * and communicate (chat, group text messages, emails, etc.).
     *
     * Church example: Joe Shmoe attends a church. Within the church,
     * he's in a choir (sub-group of church). Within the choir, he's a
     * tenor (sub-group of choir). There are eight tenors. He can learn
     * their names in a day or two with the sub-group photo directory,
     * contact any or all of them easily, etc.
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
     * This user's id will match 'user_id' below, getting all
     * users where they were the inviter.
     *
     * @return BelongsToMany
     */
    public function invitees(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_user', 'user_id', 'user_id_invitee')
        ->withPivot(
            'group_id', 'relationship'
        )
        ->withTimestamps();
    }

    /**
     * Get connections with users where others invited them to connect.
     *
     * This user's id will match 'user_id_invitee' below, getting all
     * users where they were the invitee.
     *
     * @return BelongsToMany
     */
    public function inviters(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_user', 'user_id_invitee', 'user_id')
        ->withPivot(
            'group_id', 'relationship'
        )
        ->withTimestamps();
    }

    /**
     * TODOs:
     * 1. Ensure people provide a mobile number and have it verified before they can
     *    join a sub-group. Allows sub-group leader to include them on group
     *    text messages.
     * 2. Require a photo (portrait) to join any parent group (which extends to all sub-groups).
     *    a. The whole point is for people to get to know each other's faces and names (initially)
     *       so we don't want a bunch of names with placeholder photos.
     *    b. They can upload existing photo or snap a new one with their phone.
     *    c. Do we need photos to be "approved" by someone as a step before they
     *       are visible to anyone else? Rogue user uploading something pornish
     *       would be bad. Or just useless for this app's purpose... memes, etc.
     *       instead of their face.
     * 3. Ensure their email is verified before anyone sees them. Encourage optional
     *    mobile (for parent group) as a better option that is better for verifying
     *    their identity. (I HATE EMAIL... nobody checks it.)
     */
}
