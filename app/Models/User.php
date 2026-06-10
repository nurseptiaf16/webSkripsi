<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
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

    public static function normalizeRoleValue(?string $role): string
    {
        $normalized = strtolower(trim((string) $role));

        return match ($normalized) {
            'manager', 'manajer' => 'manajer',
            'administrator' => 'admin',
            default => $normalized,
        };
    }

    public function hasAnyRole(array $roles): bool
    {
        $userRole = self::normalizeRoleValue($this->role);
        $allowedRoles = array_map([self::class, 'normalizeRoleValue'], $roles);

        return in_array($userRole, $allowedRoles, true);
    }

    protected function role(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => self::normalizeRoleValue($value),
            set: fn ($value) => self::normalizeRoleValue($value),
        );
    }
}
