<?php

namespace App\Models;

use App\Permissions\HasPermissions;
use App\Permissions\HasPermissionsTrait;
use App\Permissions\HasRoles;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasPermissions;
    use HasRoles;

    public const ADMIN = 'administrator';
    public const MANAGER = 'manager';
    public const USER = 'user';
    public const DEVELOPER = 'developer';
    public const SUPERVISOR = 'supervisor';


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


    public function listings(): HasMany
    {
        return $this->hasMany(Listing::class, 'user_id');
    }


    public function isAdministrator(): bool
    {
        $adminRole = Role::all()->where('slug','like', 'admin')->first();
        return $this->hasRole($adminRole);
    }
}
