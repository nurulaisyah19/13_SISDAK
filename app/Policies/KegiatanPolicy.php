<?php

namespace App\Policies;

use App\Models\Kegiatan;
use App\Models\User;

class KegiatanPolicy
{
    /**
     * Admin boleh lihat semua, Ormawa hanya kegiatan miliknya.
     */
    public function view(User $user, Kegiatan $kegiatan): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isOrmawa() && $user->id_ormawa === $kegiatan->id_ormawa;
    }

    /**
     * Create: cuma ormawa yang boleh buat kegiatan.
     */
    public function create(User $user): bool
    {
        return $user->isOrmawa();
    }

    /**
     * Update: admin bebas, ormawa hanya miliknya sendiri.
     */
    public function update(User $user, Kegiatan $kegiatan): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        // Ormawa hanya boleh mengubah kegiatan miliknya sendiri
        if (! ($user->isOrmawa() && $user->id_ormawa === $kegiatan->id_ormawa)) {
            return false;
        }

        // Jika status terakhir adalah 'disetujui' maka Ormawa tidak boleh meng-edit lagi.
        // Admin dibiarkan tetap bisa (di atas sudah true).
        $latest = $kegiatan->statusKegiatanLatest; // memanggil relasi one-to-one latest
        if ($latest && $latest->status === 'disetujui') {
            return false;
        }

        return true;
    }

    /**
     * Delete: sama kayak update.
     */
    public function delete(User $user, Kegiatan $kegiatan): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        // Ormawa hanya boleh menghapus kegiatan miliknya sendiri
        if (! ($user->isOrmawa() && $user->id_ormawa === $kegiatan->id_ormawa)) {
            return false;
        }

        // Jika status terakhir adalah 'disetujui' maka Ormawa tidak boleh menghapus.
        $latest = $kegiatan->statusKegiatanLatest;
        if ($latest && $latest->status === 'disetujui') {
            return false;
        }

        return true;
    }
}
