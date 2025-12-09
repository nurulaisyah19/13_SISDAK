<?php

namespace App\Models;

use App\Helpers\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JenisKegiatan extends Model
{
    use HasFactory, UsesUuid;

    protected $table = 'jenis_kegiatans';
    protected $primaryKey = 'id_jenis';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nama_jenis',
    ];

    public function kegiatans()
    {
        return $this->hasMany(Kegiatan::class, 'id_jenis', 'id_jenis');
    }
}
