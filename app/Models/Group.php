<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Group model.
 *
 * Good eloquent blog about recursive model relationships we're
 * using here for groups with nested sub-groups:
 * https://blog.ghanshyamdigital.com/building-a-self-referencing-model-in-laravel-a-step-by-step-guide
 */
class Group extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id', 'name', 'name_full', 'slug',
        'description', 'logo_url', 'is_active'
    ];

    /**
     * Groups can have many users.
     *
     * All group users are members of a parent group first.
     *
     * The relationship table (group_users, aka, pivot table) fields
     * beyond the foreign keys must be specified using withPivot to be
     * available in queries. Adding/updating timestamps on the pivot
     * table requires withTimestamps, telling you when the
     * relationships were created_at/updated_at.
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
     * This method gets one level of children. See
     * childrenRecursive for getting all levels.
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
