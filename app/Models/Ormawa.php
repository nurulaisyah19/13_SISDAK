<?php

namespace App\Models;

use App\Helpers\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ormawa extends Model
{
    use HasFactory, UsesUuid;

    protected $table = 'ormawas';
    protected $primaryKey = 'id_ormawa';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nama_ormawa',
        'jurusan',
        'deskripsi',
        'kontak',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'id_ormawa', 'id_ormawa');
    }

    public function kegiatans()
    {
        return $this->hasMany(Kegiatan::class, 'id_ormawa', 'id_ormawa');
    }
}
