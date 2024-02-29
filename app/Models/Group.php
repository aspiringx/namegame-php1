<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_group_id', 'name', 'slug',
        'description', 'logo_url', 'is_active'
    ];

    /**
     * Groups can have many users.
     *
     * All users are first members of the parent group.
     * Get users of sub-groups directly from that group.
     *
     * Pivot table (group_users) columns beyond foreign keys must
     * be specified (withPivot) to be available.
     *
     * Updating timestamps on the pivot table requires withTimestamps.
     *
     * TODO: Watch for performance in large groups. May need to
     * paginate users for groups with 100s or 1,000s.
     *
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
        ->withPivot(
            'role', 'title', 'member_since', 'is_leader', 'is_active'
        )
        ->withTimestamps();
    }

    /**
     * Parent group of this group (if applicable).
     *
     * All sub-groups have a single direct parent. Their top
     * ancestor is the parent group where parent_id is null.
     * See parentRecursive().
     *
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Get all ancestor parents (parent, grandparent, etc.)
     * up to the top group (parent_id is null).
     *
     * @return BelongsTo
     */
    public function parentRecursive(): BelongsTo
    {
        return $this->parent()->with('parentRecursive');
    }

    /**
     * Child sub-groups within this group.
     *
     * A group can have multiple levels of sub-groups to
     * accommodate more personal groups within larger groups.
     * This gets one level of children. See childrenRecursive.
     *
     * @return HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * All levels of child sub-groups.
     *
     * Monitor performance. We may need to limit layers of children.
     *
     * @return HasMany
     */
    public function childrenRecursive(): HasMany
    {
        return $this->children()->with('childrenRecursive');
    }


}
