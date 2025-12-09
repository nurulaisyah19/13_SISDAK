<?php

namespace App\Models;

use App\Helpers\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kegiatan extends Model
{
    use HasFactory, UsesUuid;

    protected $table = 'kegiatans';
    protected $primaryKey = 'id_kegiatan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nama_kegiatan',
        'tanggal_mulai',
        'tanggal_selesai',
        'id_ormawa',
        'id_jenis',
    ];

    public function ormawa()
    {
        return $this->belongsTo(Ormawa::class, 'id_ormawa', 'id_ormawa');
    }

    public function jenisKegiatan()
    {
        return $this->belongsTo(JenisKegiatan::class, 'id_jenis', 'id_jenis');
    }

    public function dokumens()
    {
        return $this->hasMany(Dokumen::class, 'id_kegiatan', 'id_kegiatan');
    }

    public function statusKegiatans()
    {
        return $this->hasMany(StatusKegiatan::class, 'id_kegiatan', 'id_kegiatan');
    }

    // 🔥 versi aman untuk PostgreSQL + UUID
    public function statusKegiatanLatest()
    {
        return $this->hasOne(StatusKegiatan::class, 'id_kegiatan', 'id_kegiatan')
            ->latest('created_at');   // cuma ORDER BY created_at DESC LIMIT 1
    }
}
