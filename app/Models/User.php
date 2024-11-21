<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel; // Use the correct Panel class
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Update method to match the FilamentUser contract
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->email == 'admin@gmail.com';
    }
}
