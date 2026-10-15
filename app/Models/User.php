<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'phone', 'address', 'password'];
    protected $hidden = ['password', 'remember_token'];
    protected function casts(): array { return ['email_verified_at' => 'datetime', 'password' => 'hashed']; }

    public function roles(): BelongsToMany { return $this->belongsToMany(Role::class); }
    public function hasRole(string $role): bool { return $this->roles()->where('name', $role)->exists(); }
    public function hasPermission(string $permission): bool {
        return $this->roles()->whereHas('permissions', fn($q) => $q->where('name', $permission))->exists();
    }
    public function orders(): HasMany { return $this->hasMany(Order::class); }
}
