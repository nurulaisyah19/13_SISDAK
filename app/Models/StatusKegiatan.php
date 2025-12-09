<?php

namespace App\Models;

use App\Helpers\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StatusKegiatan extends Model
{
    use HasFactory, UsesUuid;

    protected $table = 'status_kegiatans';
    protected $primaryKey = 'id_status';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'status',
        'catatan',
        'id_kegiatan',
        'id_user',
    ];

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'id_kegiatan', 'id_kegiatan');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
