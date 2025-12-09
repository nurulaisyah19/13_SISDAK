<?php

namespace App\Http\Controllers\Ormawa;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ormawa\StoreKegiatanRequest;
use App\Http\Requests\Ormawa\UpdateKegiatanRequest;
use App\Models\Kegiatan;
use App\Models\JenisKegiatan;
use Illuminate\Http\Request;
use App\Models\Dokumen;       
use App\Models\StatusKegiatan;
use Illuminate\Support\Facades\Storage; 

class KegiatanController extends Controller
{
    /**
     * Tampilkan daftar kegiatan milik ORMAWA yang login
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $kegiatans = Kegiatan::with('jenisKegiatan')
            ->where('id_ormawa', $user->id_ormawa)
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('ormawa.kegiatan.index', compact('kegiatans'));
    }

    /**
     * Form pengajuan kegiatan baru
     */
    public function create(Request $request)
    {
        // Policy: hanya ormawa yang boleh create
        $this->authorize('create', Kegiatan::class);

        // Jenis kegiatan untuk dropdown
        $jenisKegiatans = JenisKegiatan::orderBy('nama_jenis')->get();

        return view('ormawa.kegiatan.create', compact('jenisKegiatans'));
    }

    /**
     * Simpan pengajuan kegiatan baru
     */
    public function store(StoreKegiatanRequest $request)
{
    $user = $request->user();

    // data yang sudah divalidasi dari Form Request
    $data = $request->validated();

    // 1. Buat kegiatan baru
    $kegiatan = Kegiatan::create([
        'nama_kegiatan'   => $data['nama_kegiatan'],
        'tanggal_mulai'   => $data['tanggal_mulai'],
        'tanggal_selesai' => $data['tanggal_selesai'],
        'id_jenis'        => $data['id_jenis'],
        'id_ormawa'       => $user->id_ormawa,
    ]);

    // 2. Buat status awal "menunggu"
    StatusKegiatan::create([
        'status'      => 'menunggu',
        'catatan'     => null,
        'id_kegiatan' => $kegiatan->id_kegiatan,
        'id_user'     => null, // belum diverifikasi admin
    ]);

    // 3. Kalau ada proposal yang diupload → simpan ke storage & tabel dokumens
    if ($request->hasFile('proposal')) {
        $file = $request->file('proposal');

        // simpan ke storage/app/public/dokumens/{id_kegiatan}
        $path = $file->store('dokumens/'.$kegiatan->id_kegiatan, 'public');

        Dokumen::create([
            'nama_dokumen' => 'Proposal Kegiatan',
            'file_path'    => $path,
            'id_kegiatan'  => $kegiatan->id_kegiatan,
        ]);
    }

    return redirect()
        ->route('ormawa.kegiatan.index')
        ->with('success', 'Kegiatan berhasil diajukan dan menunggu verifikasi.');
}

    /**
     * Form edit kegiatan
     * (route ini sudah dilindungi middleware owner.ormawa)
     */
    public function edit(Request $request, $id_kegiatan)
    {
        $user = $request->user();

        $kegiatan = Kegiatan::where('id_kegiatan', $id_kegiatan)
            ->where('id_ormawa', $user->id_ormawa)
            ->firstOrFail();

        // Policy: update
        $this->authorize('update', $kegiatan);

        $jenisKegiatans = JenisKegiatan::orderBy('nama_jenis')->get();

        return view('ormawa.kegiatan.edit', compact('kegiatan', 'jenisKegiatans'));
    }

    /**
     * Update kegiatan
     */
    public function update(UpdateKegiatanRequest $request, $id_kegiatan)
    {
        $user = $request->user();

        $kegiatan = Kegiatan::where('id_kegiatan', $id_kegiatan)
            ->where('id_ormawa', $user->id_ormawa)
            ->firstOrFail();

        // Policy: update
        $this->authorize('update', $kegiatan);

        $data = $request->validated();

        $kegiatan->update([
            'nama_kegiatan'   => $data['nama_kegiatan'],
            'tanggal_mulai'   => $data['tanggal_mulai'],
            'tanggal_selesai' => $data['tanggal_selesai'],
            'id_jenis'        => $data['id_jenis'],
        ]);

        return redirect()
            ->route('ormawa.kegiatan.show', $kegiatan->id_kegiatan)
            ->with('success', 'Kegiatan berhasil diperbarui.');
    }

    /**
     * Hapus kegiatan
     */
    public function destroy(Request $request, $id_kegiatan)
    {
        $user = $request->user();

        $kegiatan = Kegiatan::where('id_kegiatan', $id_kegiatan)
            ->where('id_ormawa', $user->id_ormawa)
            ->firstOrFail();

        // Policy: delete
        $this->authorize('delete', $kegiatan);

        $kegiatan->delete(); // akan otomatis menghapus dokumen & status kalau FK cascade

        return redirect()
            ->route('ormawa.kegiatan.index')
            ->with('success', 'Kegiatan berhasil dihapus.');
    }
    public function show(Request $request, $id_kegiatan)
    {
    $user = $request->user();

    $kegiatan = Kegiatan::with(['jenisKegiatan', 'ormawa', 'dokumens', 'statusKegiatans'])
        ->where('id_kegiatan', $id_kegiatan)
        ->where('id_ormawa', $user->id_ormawa) // keamanan: hanya kegiatan milik ORMAWA ini
        ->firstOrFail();

    return view('kegiatan.show', [
        'kegiatan' => $kegiatan,
        'user'     => $user,
    ]);
}
}
