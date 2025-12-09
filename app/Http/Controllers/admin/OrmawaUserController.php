<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ormawa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OrmawaUserController extends Controller
{
    /**
     * Tampilkan daftar akun ORMAWA
     */
    public function index()
    {
        $ormawaUsers = User::with('ormawa')
            ->where('role', 'ormawa')
            ->orderBy('username')
            ->get();

        return view('admin.ormawa-akun.index', compact('ormawaUsers'));
    }

    /**
     * Form tambah akun ORMAWA
     */
    public function create()
    {
        // list tetap, nanti dipakai di dropdown jurusan
        $jurusans = [
            'Kimia',
            'Fisika',
            'Matematika',
            'Biologi',
            'Ilmu Komputer',
            'Umum',
        ];

        return view('admin.ormawa-akun.create', compact('jurusans'));
    }

    /**
     * Simpan akun ORMAWA baru
     *
     * - nama_ormawa dipakai sebagai:
     *   - ormawas.nama_ormawa
     *   - users.username
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_ormawa' => ['required', 'string', 'max:100'],
            'jurusan'     => ['required', 'in:Kimia,Fisika,Matematika,Biologi,Ilmu Komputer,Umum'],
            'password'    => ['required', 'string', 'min:8'],
        ]);

        $nama = trim($data['nama_ormawa']);

        // Pastikan belum ada user dengan username ini (biar ga dobel)
        if (User::where('username', $nama)->exists()) {
            return back()
                ->withErrors(['nama_ormawa' => 'Username / Nama ORMAWA ini sudah dipakai.'])
                ->withInput();
        }

        // 1. Buat ORMAWA
        $ormawa = Ormawa::create([
            'nama_ormawa' => $nama,
            'jurusan'     => $data['jurusan'],
            'deskripsi'   => null,
            'kontak'      => null,
        ]);

        // 2. Buat user ORMAWA
        User::create([
            'username'  => $nama,
            'password'  => Hash::make($data['password']),
            'role'      => 'ormawa',
            'id_ormawa' => $ormawa->id_ormawa,
        ]);

        return redirect()
            ->route('admin.ormawa-akun.index')
            ->with('success', 'Akun ORMAWA berhasil dibuat.');
    }

    /**
     * Hapus akun ORMAWA
     */
    public function destroy(User $user)
    {
        if ($user->role !== 'ormawa') {
            abort(403, 'Hanya akun ORMAWA yang bisa dihapus di sini.');
        }

        $user->delete();

        return back()->with('success', 'Akun ORMAWA berhasil dihapus.');
    }
}
