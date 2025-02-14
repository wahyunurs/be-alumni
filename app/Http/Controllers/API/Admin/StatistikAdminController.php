<?php

namespace App\Http\Controllers\API\Admin;

use App\Models\Alumni;
use App\Models\Statistik;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class StatistikAdminController extends Controller
{
    // Menampilkan semua data statistik
    public function index()
    {
        try {
            $statistiks = Statistik::orderBy('tahun_lulus', 'desc')->paginate(10); // Urutkan berdasarkan tahun_lulus terbaru
            Log::info('Data statistik berhasil diambil', ['total' => $statistiks->total()]);
            return response()->json($statistiks);
        } catch (\Exception $e) {
            Log::error('Error mengambil data statistik', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Terjadi kesalahan saat mengambil data statistik'], 500);
        }
    }

    // Menyimpan data statistik baru
    public function store(Request $request)
    {
        try {
            $request->validate([
                'tahun_lulus' => 'required|integer|min:0|unique:statistiks,tahun_lulus',
                'alumni_total' => 'required|integer|min:0',
            ]);

            $alumni_terlacak = Alumni::where('tahun_lulus', $request->tahun_lulus)->count();
            $statistik = Statistik::create([
                'tahun_lulus' => $request->tahun_lulus,
                'alumni_total' => $request->alumni_total,
                'alumni_terlacak' => $alumni_terlacak,
            ]);

            Log::info('Data statistik berhasil dibuat', ['data' => $statistik]);
            return response()->json([
                'message' => 'Data statistik berhasil dibuat',
                'data' => $statistik,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error menyimpan data statistik', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Tahun statistik yang anda inputkan sudah tersedia!'], 500);
        }
    }

    // Mengupdate data statistik
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'tahun_lulus' => "required|integer|min:0|unique:statistiks,tahun_lulus,$id",
                'alumni_total' => 'required|integer|min:0',
            ]);

            $alumni_terlacak = Alumni::where('tahun_lulus', $request->tahun_lulus)->count();
            $statistik = Statistik::findOrFail($id);
            $statistik->update([
                'tahun_lulus' => $request->tahun_lulus,
                'alumni_total' => $request->alumni_total,
                'alumni_terlacak' => $alumni_terlacak,
            ]);

            Log::info('Data statistik berhasil diperbarui', ['data' => $statistik]);
            return response()->json([
                'message' => 'Data statistik berhasil diperbarui',
                'data' => $statistik,
            ]);
        } catch (\Exception $e) {
            Log::error('Error memperbarui data statistik', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Terjadi kesalahan saat memperbarui data statistik'], 500);
        }
    }

    // Menghapus data statistik
    public function destroy($id)
    {
        try {
            $statistik = Statistik::findOrFail($id);
            $statistik->delete();

            Log::info('Data statistik berhasil dihapus', ['id' => $id]);
            return response()->json(['message' => 'Data statistik berhasil dihapus']);
        } catch (\Exception $e) {
            Log::error('Error menghapus data statistik', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Terjadi kesalahan saat menghapus data statistik'], 500);
        }
    }

    // Menghitung alumni terdeteksi ketika data alumni berubah
    public function updateAlumniCount($tahun_lulus)
    {
        try {
            $alumni_terlacak = Alumni::where('tahun_lulus', $tahun_lulus)->count();
            $statistik = Statistik::updateOrCreate(
                ['tahun_lulus' => $tahun_lulus],
                ['alumni_terlacak' => $alumni_terlacak]
            );

            Log::info('Statistik alumni berhasil diperbarui', ['tahun_lulus' => $tahun_lulus, 'alumni_terlacak' => $alumni_terlacak]);
            return response()->json([
                'message' => 'Statistik alumni berhasil diperbarui',
                'data' => $statistik,
            ]);
        } catch (\Exception $e) {
            Log::error('Error memperbarui statistik alumni', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Terjadi kesalahan saat memperbarui statistik alumni'], 500);
        }
    }

    // Menampilkan data statistik berdasarkan ID
    public function show($id)
    {
        try {
            $statistik = Statistik::findOrFail($id);

            Log::info('Data statistik berhasil diambil', ['id' => $id]);
            return response()->json([
                'message' => 'Data statistik berhasil diambil',
                'data' => $statistik,
            ]);
        } catch (\Exception $e) {
            Log::error('Error mengambil data statistik', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Terjadi kesalahan saat mengambil data statistik'], 500);
        }
    }
}
