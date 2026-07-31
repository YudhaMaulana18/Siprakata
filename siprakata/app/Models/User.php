<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable {
    use Notifiable;

    protected $fillable = ['name','email','password','role_id'];
    protected $hidden   = ['password','remember_token'];
    protected $casts    = ['email_verified_at' => 'datetime', 'password' => 'hashed'];

    public function role()  { return $this->belongsTo(Role::class); }
    public function roles() { return $this->belongsToMany(Role::class, 'role_user'); }
    public function apiTokens() { return $this->hasMany(ApiToken::class); }

    public function hasRole(string $role): bool {
        return $this->role?->name === $role
            || $this->roles()->where('name', $role)->exists();
    }

    public function hasPermission(string $permission): bool {
        if ($this->role?->hasPermission($permission)) return true;
        return $this->roles()->whereHas('permissions', fn($q) => $q->where('name', $permission))->exists();
    }

    public function isAdmin()     { return $this->hasRole('admin'); }
    public function isDosen()     { return $this->hasRole('dosen'); }
    public function isMahasiswa() { return $this->hasRole('mahasiswa'); }

    public function createApiToken(string $name = 'mobile'): ApiToken {
        return $this->apiTokens()->create([
            'token' => Str::random(64),
            'name'  => $name,
        ]);
    }
}
