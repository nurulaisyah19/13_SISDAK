<?php

namespace App\Models;

use App\Helpers\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, UsesUuid;

    protected $table = 'users';
    protected $primaryKey = 'id_user';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'username',
        'password',
        'role',
        'id_ormawa',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // helper role
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isOrmawa(): bool
    {
        return $this->role === 'ormawa';
    }

    public function ormawa()
    {
        return $this->belongsTo(Ormawa::class, 'id_ormawa', 'id_ormawa');
    }
}
