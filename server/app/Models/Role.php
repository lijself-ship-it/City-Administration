<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    protected $fillable = ['name', 'slug'];

    /**
     * The permissions that belong to this role.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }

    /**
     * The users that belong to this role.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Check if the role has a specific permission.
     */
    public function hasPermission(string $permissionName): bool
    {
        return $this->permissions->contains('name', $permissionName);
    }

    /**
     * Check if the role has any of the given permissions.
     */
    public function hasAnyPermission(array $permissionNames): bool
    {
        return $this->permissions->whereIn('name', $permissionNames)->isNotEmpty();
    }
}
