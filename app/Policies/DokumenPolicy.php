<?php

namespace App\Policies;

use App\Models\Dokumen;
use App\Models\User;

class DokumenPolicy
{
    /**
     * View: admin boleh semua, ormawa hanya dokumen kegiatannya.
     */
    public function view(User $user, Dokumen $dokumen): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isOrmawa()
            && $dokumen->kegiatan
            && $dokumen->kegiatan->id_ormawa === $user->id_ormawa;
    }

    /**
     * Upload dokumen: ormawa saja.
     */
    public function create(User $user): bool
    {
        return $user->isOrmawa();
    }

    /**
     * Hapus dokumen: admin boleh semua, ormawa hanya dokumen milik kegiatannya.
     */
    public function delete(User $user, Dokumen $dokumen): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isOrmawa()
            && $dokumen->kegiatan
            && $dokumen->kegiatan->id_ormawa === $user->id_ormawa;
    }
}
