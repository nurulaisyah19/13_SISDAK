<?php

namespace App\Http\Middleware;

use App\Models\Kegiatan;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOrmawaOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): \Symfony\Component\HttpFoundation\Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // kalau belum login, lempar ke login
        if (! $user) {
            return redirect()->route('login');
        }

        // pastikan dia ORMAWA
        if ($user->role !== 'ormawa') {
            abort(403, 'Hanya ORMAWA yang boleh mengakses halaman ini.');
        }

        // ambil id_kegiatan dari route parameter
        // misal route: /ormawa/kegiatan/{id_kegiatan}/edit
        $idKegiatan = $request->route('id_kegiatan') 
            ?? $request->route('kegiatan'); // tergantung nama param di route

        if (! $idKegiatan) {
            abort(400, 'ID kegiatan tidak ditemukan di route.');
        }

        // cari kegiatan
        $kegiatan = Kegiatan::where('id_kegiatan', $idKegiatan)->first();

        if (! $kegiatan) {
            abort(404, 'Kegiatan tidak ditemukan.');
        }

        // cek kepemilikan: id_ormawa di user harus sama dengan id_ormawa di kegiatan
        if ($user->id_ormawa !== $kegiatan->id_ormawa) {
            abort(403, 'Anda tidak berhak mengakses kegiatan ini.');
        }

        // kalau lolos semua, lanjut
        return $next($request);
    }
}
