<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
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
            'is_active' => 'boolean',
        ];
    }

    /**
     * The role assigned to this user.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * News articles authored by this user.
     */
    public function news(): HasMany
    {
        return $this->hasMany(News::class, 'author_id');
    }

    /**
     * Pages authored by this user.
     */
    public function pages(): HasMany
    {
        return $this->hasMany(Page::class, 'author_id');
    }

    /**
     * Programs authored by this user.
     */
    public function programs(): HasMany
    {
        return $this->hasMany(Program::class, 'author_id');
    }

    /**
     * Forum topics created by this user.
     */
    public function forumTopics(): HasMany
    {
        return $this->hasMany(ForumTopic::class);
    }

    /**
     * Forum replies by this user.
     */
    public function forumReplies(): HasMany
    {
        return $this->hasMany(ForumReply::class);
    }

    /**
     * Check if user has a specific permission via their role.
     */
    public function hasPermission(string $permission): bool
    {
        return $this->role?->hasPermission($permission) ?? false;
    }

    /**
     * Check if user has any of the given permissions.
     */
    public function hasAnyPermission(array $permissions): bool
    {
        return $this->role?->hasAnyPermission($permissions) ?? false;
    }

    /**
     * Check if user is a Super Admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role?->slug === 'super_admin';
    }

    /**
     * Scope: only active users.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
