<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Response;

class PengumumanAlumniController extends Controller
{
    public function index()
    {
        Log::info('Memulai proses pengambilan data pengumuman');

        $pengumuman = Pengumuman::latest()->get(10);

        if ($pengumuman->isEmpty()) {
            Log::warning('Tidak ada data pengumuman ditemukan');
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        Log::info('Data pengumuman berhasil diambil, jumlah data: ' . $pengumuman->count());
        return response()->json($pengumuman);
    }

    public function store(Request $request)
    {
        Log::info('Memulai proses penyimpanan pengumuman baru');

        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
        ]);

        if ($validator->fails()) {
            Log::error('Validasi data gagal: ' . json_encode($validator->errors()));
            return response()->json(['message' => 'Validasi data gagal', 'errors' => $validator->errors()], 422);
        }

        try {
            $pengumuman = new Pengumuman();
            $pengumuman->judul = $request->judul;
            $pengumuman->user = "Koordinator Alumni";
            $pengumuman->isi = $request->isi;
            $pengumuman->published_at = now();
            $pengumuman->save();

            Log::info('Pengumuman berhasil disimpan: ' . $pengumuman->id);
            return response()->json(['message' => 'Pengumuman berhasil dibuat', 'pengumuman' => $pengumuman], 201);
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan pengumuman: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat menyimpan data', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        Log::info('Memulai proses pengambilan data pengumuman dengan ID: ' . $id);

        $pengumuman = Pengumuman::find($id);

        if (!$pengumuman) {
            Log::warning('Data pengumuman dengan ID ' . $id . ' tidak ditemukan');
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        Log::info('Data pengumuman berhasil ditemukan: ' . $pengumuman->id);
        return response()->json($pengumuman);
    }

    public function update(Request $request, $id)
    {
        Log::info('Memulai proses pembaruan data pengumuman dengan ID: ' . $id);

        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
        ]);

        if ($validator->fails()) {
            Log::error('Validasi data gagal: ' . json_encode($validator->errors()));
            return response()->json(['status' => 'error', 'message' => 'Validasi data gagal', 'errors' => $validator->errors()], 422);
        }

        $pengumuman = Pengumuman::find($id);

        if (!$pengumuman) {
            Log::warning('Data pengumuman dengan ID ' . $id . ' tidak ditemukan');
            return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan'], 404);
        }

        try {
            $pengumuman->judul = $request->judul;
            $pengumuman->isi = $request->isi;
            $pengumuman->save();

            Log::info('Data pengumuman berhasil diperbarui: ' . $pengumuman->id);
            return response()->json(['status' => 'success', 'message' => 'Data berhasil diperbarui'], 200);
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui pengumuman: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Gagal memperbarui data', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        Log::info('Memulai proses penghapusan data pengumuman dengan ID: ' . $id);

        $pengumuman = Pengumuman::find($id);

        if (!$pengumuman) {
            Log::warning('Data pengumuman dengan ID ' . $id . ' tidak ditemukan');
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        try {
            $pengumuman->delete();
            Log::info('Data pengumuman berhasil dihapus: ' . $id);
            return response()->json(['message' => 'Pengumuman berhasil dihapus']);
        } catch (\Exception $e) {
            Log::error('Gagal menghapus pengumuman: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat menghapus data', 'error' => $e->getMessage()], 500);
        }
    }
}
