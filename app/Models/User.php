<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\UserStatus;
use App\UserType;
use App\Models\UserSocialLink;
use App\Models\Role;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'picture',
        'bio',
        'userType',
        'status',


    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
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
            'type' => UserType::class,
            'status' => UserStatus::class

        ];
    }
    public function isAdmin()
    {
        return $this->userType === UserType::Admin;
    }

    public function isActive()
    {
        return $this->status === UserStatus::ACTIVE;
    }
    public function getPictureAttribute($value)
    {
        return $value ? asset('/images/users/' . $value) : asset('images/users/default.jpg');
    }
    public function socialLinks()
    {
        return $this->belongsTo(UserSocialLink::class, 'id', 'user_id');
    }

    // RBAC Relationships & Methods
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    /**
     * Get all posts by this user
     */
    public function posts()
    {
        return $this->hasMany(\App\Models\Post::class);
    }

    /**
     * Get all comments by this user
     */
    public function comments()
    {
        return $this->hasMany(\App\Models\Comment::class);
    }

    public function hasRole($role)
    {
        return $this->roles()->where('slug', $role)->exists();
    }

    public function hasPermission($permission)
    {
        return $this->roles()
            ->whereHas('permissions', function ($q) use ($permission) {
                $q->where('slug', $permission);
            })->exists();
    }

    /**
     * Check permission using cached roles (more efficient)
     */
    public function hasPermissionCached($permission)
    {
        if (!$this->relationLoaded('roles')) {
            $this->load('roles.permissions');
        }

        return $this->roles
            ->pluck('permissions')
            ->flatten()
            ->pluck('slug')
            ->contains($permission);
    }
}
