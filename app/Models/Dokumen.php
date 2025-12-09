<?php

namespace App\Models;

use App\Helpers\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dokumen extends Model
{
    use HasFactory, UsesUuid;

    protected $table = 'dokumens';
    protected $primaryKey = 'id_dokumen';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nama_dokumen',
        'file_path',
        'id_kegiatan',
    ];

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'id_kegiatan', 'id_kegiatan');
    }
}
