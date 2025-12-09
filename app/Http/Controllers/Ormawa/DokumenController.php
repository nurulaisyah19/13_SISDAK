<?php

namespace App\Http\Controllers\Ormawa;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ormawa\StoreDokumenRequest;
use App\Models\Dokumen;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DokumenController extends Controller
{
    /**
     * List dokumen untuk suatu kegiatan
     * route: /ormawa/kegiatan/{id_kegiatan}/dokumen
     */
    public function index(Request $request, $id_kegiatan)
    {
        $user = $request->user();

        $kegiatan = Kegiatan::where('id_kegiatan', $id_kegiatan)
            ->where('id_ormawa', $user->id_ormawa)
            ->firstOrFail();

        // Policy: boleh lihat kegiatan ini?
        $this->authorize('view', $kegiatan);

        $dokumens = $kegiatan->dokumens()
            ->orderByDesc('created_at')
            ->get();

        return view('ormawa.dokumen.index', compact('kegiatan', 'dokumens'));
    }

    /**
     * Form upload dokumen
     */
    public function create(Request $request, $id_kegiatan)
    {
        $user = $request->user();

        $kegiatan = Kegiatan::where('id_kegiatan', $id_kegiatan)
            ->where('id_ormawa', $user->id_ormawa)
            ->firstOrFail();

        // Policy: hanya pemilik (atau admin) yang boleh menambah dokumen
        $this->authorize('update', $kegiatan);

        return view('ormawa.dokumen.upload', compact('kegiatan'));
    }

    /**
     * Simpan dokumen yang diupload
     */
    public function store(StoreDokumenRequest $request, $id_kegiatan)
    {
        $user = $request->user();

        $kegiatan = Kegiatan::where('id_kegiatan', $id_kegiatan)
            ->where('id_ormawa', $user->id_ormawa)
            ->firstOrFail();

        // Policy: update kegiatan = boleh manage dokumen
        $this->authorize('update', $kegiatan);

        $data = $request->validated();

        $file = $request->file('file');

        // simpan ke storage/app/public/dokumens/{id_kegiatan}
        $path = $file->store('dokumens/'.$kegiatan->id_kegiatan, 'public');

        Dokumen::create([
            'nama_dokumen' => $data['nama_dokumen'],
            'file_path'    => $path,
            'id_kegiatan'  => $kegiatan->id_kegiatan,
        ]);

        return redirect()
            ->route('ormawa.dokumen.index', $kegiatan->id_kegiatan)
            ->with('success', 'Dokumen berhasil diupload.');
    }

    /**
     * Hapus dokumen
     * route: DELETE /ormawa/dokumen/{id_dokumen}
     */
    public function destroy(Request $request, $id_dokumen)
    {
        $dokumen = Dokumen::with('kegiatan')
            ->where('id_dokumen', $id_dokumen)
            ->firstOrFail();

        // Policy: delete dokumen → dicek oleh DokumenPolicy
        $this->authorize('delete', $dokumen);

        // hapus file fisik
        if ($dokumen->file_path && Storage::disk('public')->exists($dokumen->file_path)) {
            Storage::disk('public')->delete($dokumen->file_path);
        }

        $idKegiatan = $dokumen->id_kegiatan;

        $dokumen->delete();

        return redirect()
            ->route('ormawa.dokumen.index', $idKegiatan)
            ->with('success', 'Dokumen berhasil dihapus.');
    }
}
