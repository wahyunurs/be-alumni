<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MasaTungguController extends Controller
{
    
    /**
     * Mendapatkan daftar tahun lulus unik dari data alumni
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTahunLulus()
    {
        try {
            // Log info: memulai proses pengambilan data tahun lulus
            Log::info('Memulai proses pengambilan data tahun lulus.');

            // Ambil tahun_lulus unik dari tabel Alumni dengan masa_tunggu dan tahun_lulus tidak null
            $tahunLulus = Alumni::whereNotNull('masa_tunggu') // Hanya data dengan masa_tunggu yang tidak null
                                ->whereNotNull('tahun_lulus') // Hanya data dengan tahun_lulus yang tidak null
                                ->distinct()                 // Pastikan hanya nilai unik
                                ->orderBy('tahun_lulus', 'desc') // Urutkan dari tahun terbaru ke terlama
                                ->pluck('tahun_lulus');      // Ambil hanya kolom tahun_lulus

            // Log info: data berhasil diambil
            Log::info('Data tahun lulus berhasil diambil.', ['data' => $tahunLulus]);

            // Jika data kosong, kembalikan pesan bahwa tidak ada data masa tunggu
            if ($tahunLulus->isEmpty()) {
                Log::info('Tidak ada data masa tunggu yang tersedia.');
                return response()->json([
                    'message' => 'Tidak ada data masa tunggu.',
                ], 404);
            }

            // Jika data ada, log hasilnya
            Log::info('Data tahun lulus ditemukan.', ['tahunLulus' => $tahunLulus]);

            // Kembalikan daftar tahun lulus
            return response()->json([
                'tahunLulus' => $tahunLulus,
            ]);

        } catch (\Exception $e) {
            // Log error jika terjadi kesalahan
            Log::error('Terjadi kesalahan saat mengambil data tahun lulus.', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            // Kembalikan respons error
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengambil data tahun lulus. Silakan coba lagi nanti.',
            ], 500);
        }
    }

    /**
     * Mendapatkan statistik jumlah alumni berdasarkan masa_tunggu
     * dengan filter berdasarkan tahun lulus
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cekMasaTunggu(Request $request)
    {
        try {
            Log::info('Memulai proses cekMasaTunggu');

            // Filter tahun lulus jika ada di request
            $tahunLulus = $request->input('tahun_lulus');
            Log::info('Filter tahun lulus: ' . ($tahunLulus ?? 'tidak ada filter'));

            // Query dasar
            $query = Alumni::query();

            // Tambahkan filter tahun_lulus jika tersedia
            if ($tahunLulus) {
                $query->where('tahun_lulus', $tahunLulus);
            }

            // Filter alumni yang hanya mengisi masa_tunggu
            $filteredQuery = (clone $query)->whereNotNull('masa_tunggu');

            // Total jumlah alumni yang mengisi masa_tunggu
            $totalAlumni = $filteredQuery->count();
            Log::info('Total alumni yang mengisi masa_tunggu: ' . $totalAlumni);

            // Hitung jumlah alumni berdasarkan masa_tunggu
            $masaTungguCounts = [
                'Kurang dari 6 bulan' => (clone $filteredQuery)->where('masa_tunggu', 'Kurang dari 6 bulan')->count(),
                '6 bulan - 12 bulan' => (clone $filteredQuery)->where('masa_tunggu', '6 bulan - 12 bulan')->count(),
                'Lebih dari 12 bulan' => (clone $filteredQuery)->where('masa_tunggu', 'Lebih dari 12 bulan')->count(),
            ];

            Log::info('Data statistik masa_tunggu berhasil dihitung', $masaTungguCounts);

            return response()->json([
                'tahunLulus' => $tahunLulus,
                'totalAlumni' => $totalAlumni,
                'masaTungguCounts' => $masaTungguCounts,
            ]);
        } catch (\Exception $e) {
            Log::error('Terjadi kesalahan pada cekMasaTunggu: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat memproses data'], 500);
        }
    }

    /**
     * Mendapatkan data alumni berdasarkan masa_tunggu dan tahun lulus
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            Log::info('Memulai proses index berdasarkan masa tunggu');

            // Ambil parameter masa_tunggu dan tahun_lulus dari request
            $masaTunggu = $request->input('masa_tunggu', 'Kurang dari 6 bulan');
            $tahunLulus = $request->input('tahun_lulus');
            Log::info("Filter masa_tunggu: $masaTunggu, tahun lulus: " . ($tahunLulus ?? 'tidak ada filter'));

            // Query data alumni
            $query = Alumni::query();

            // Filter berdasarkan tahun lulus jika tersedia
            if ($tahunLulus) {
                $query->where('tahun_lulus', $tahunLulus);
            }

            // Filter berdasarkan masa tunggu hanya
            if ($masaTunggu === 'Kurang dari 6 bulan') {
                $query->where('masa_tunggu', 'Kurang dari 6 bulan');
            } elseif ($masaTunggu === '6 sampai 12 bulan') {
                $query->where('masa_tunggu', '6 sampai 12 bulan');
            } elseif ($masaTunggu === 'Lebih dari 12 bulan') {
                $query->where('masa_tunggu', 'Lebih dari 12 bulan');
            }

            // Eksekusi query hanya dengan kolom yang diperlukan
            $data = $query->get([
                'name',
                'nim',
                'email',
                'no_hp',
                'tahun_masuk',
                'tahun_lulus',
                'status',
                'masa_tunggu',
            ]);

            Log::info('Data alumni berhasil diambil berdasarkan masa tunggu', ['total' => $data->count()]);

            return response()->json([
                'masaTunggu' => $masaTunggu,
                'tahunLulus' => $tahunLulus,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            Log::error('Terjadi kesalahan pada index: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat memproses data'], 500);
        }
    }
}
