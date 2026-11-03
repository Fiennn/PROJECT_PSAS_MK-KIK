<?php

namespace App\Models;

use App\Enums\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $primaryKey = 'id_user';

    public $timestamps = false;

    protected $fillable = [
        'username',
        'password',
        'role',
        'email',
        'status_aktif',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'role' => Role::class,
            'status_aktif' => 'boolean',
        ];
    }

    public function wisatawan()
    {
        return $this->hasOne(Wisatawan::class, 'id_user', 'id_user');
    }

    public function pemilikHomestay()
    {
        return $this->hasOne(PemilikHomestay::class, 'id_user', 'id_user');
    }

    public function displayName(): string
    {
        return $this->wisatawan?->nama_wisatawan
            ?? $this->pemilikHomestay?->nama_pemilik
            ?? $this->username;
    }

    public function dashboardUrl(): string
    {
        return route($this->role->dashboardRoute());
    }
}
