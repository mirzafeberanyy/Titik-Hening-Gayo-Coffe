<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone', 'province_id', 'city_id', 'district_id', 'address'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // LOGIC KEAMANAN ADMIN PANEL
    public function canAccessPanel(Panel $panel): bool
    {
        // Izinkan Admin DAN Staff masuk dashboard
        return in_array($this->role, ['admin', 'staff_gudang', 'staff_konten']);
    }

    // Helper function untuk cek di kodingan lain
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // Helper untuk Policy
    public function isGudang(): bool {
        return $this->role === 'staff_gudang';
    }
    
    public function isKonten(): bool {
        return $this->role === 'staff_konten';
    }

    public function isCustomer(): bool 
    {
        return $this->role === 'customer';
    }

    public function chats(): HasMany
    {
        return $this->hasMany(Chat::class);
    }
}