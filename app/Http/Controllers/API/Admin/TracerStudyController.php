<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Alumni;
use Illuminate\Support\Facades\Log;

class TracerStudyController extends Controller
{
    /**
     * Mendapatkan daftar tahun lulus unik dari data alumni
     */
    public function getTahunLulus()
    {
        try {
            $tahunLulus = Alumni::whereNotNull('tahun_lulus')
                ->whereNotNull('status')
                ->distinct()
                ->orderBy('tahun_lulus', 'desc')
                ->pluck('tahun_lulus');
            
            Log::info('Daftar tahun lulus berhasil diambil', ['total_tahun' => $tahunLulus->count()]);
            return response()->json(['tahunLulus' => $tahunLulus]);
        } catch (\Exception $e) {
            Log::error('Error saat mengambil tahun lulus', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Terjadi kesalahan saat mengambil data tahun lulus'], 500);
        }
    }
    
    /**
     * Mendapatkan statistik jumlah alumni berdasarkan status pekerjaan
     */
    public function cekAlumni(Request $request)
    {
        try {
            $tahunLulus = $request->input('tahun_lulus');
            $query = Alumni::query();

            if ($tahunLulus) {
                $query->where('tahun_lulus', $tahunLulus);
            }

            // Salin data alumni berdasarkan query
            $alumniData = $query->get();

            // Hitung total alumni
            $totalAlumni = $alumniData->count();

            // Hitung berdasarkan status
            $statusCounts = [
                'Bekerja Full Time' => $alumniData->where('status', 'Bekerja Full Time')->count(),
                'Bekerja Part Time' => $alumniData->where('status', 'Bekerja Part Time')->count(),
                'Wirausaha' => $alumniData->where('status', 'Wirausaha')->count(),
                'Melanjutkan Pendidikan' => $alumniData->where('status', 'Melanjutkan Pendidikan')->count(),
                'Tidak Bekerja Tetapi Sedang Mencari Pekerjaan' => $alumniData->where('status', 'Tidak Bekerja Tetapi Sedang Mencari Pekerjaan')->count(),
                'Belum Memungkinkan Bekerja' => $alumniData->where('status', 'Belum Memungkinkan Bekerja')->count(),
                'Menikah/Mengurus Keluarga' => $alumniData->where('status', 'Menikah/Mengurus Keluarga')->count(),
            ];

            Log::info('Statistik jumlah alumni berhasil dihitung', [
                'tahun_lulus' => $tahunLulus,
                'total_alumni' => $totalAlumni,
                'status_counts' => $statusCounts,
            ]);

            return response()->json([
                'tahunLulus' => $tahunLulus,
                'totalAlumni' => $totalAlumni,
                'statusCounts' => $statusCounts,
            ]);
        } catch (\Exception $e) {
            Log::error('Error saat menghitung statistik jumlah alumni', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Terjadi kesalahan saat menghitung statistik jumlah alumni'], 500);
        }
    }

    /**
     * Mendapatkan data alumni berdasarkan status pekerjaan dan tahun lulus
     */
    public function index(Request $request)
    {
        try {
            $status = $request->input('status', 'Bekerja Full Time');
            $tahunLulus = $request->input('tahun_lulus');
            $query = Alumni::query();

            $query->where('status', $status);

            if ($tahunLulus) {
                $query->where('tahun_lulus', $tahunLulus);
            }

            $data = $query->get();

            $extraColumns = [];
            if (in_array($status, ['Bekerja Full Time', 'Bekerja Part Time', 'Wirausaha'])) {
                $extraColumns = ['bidang_job', 'jns_job', 'nama_job', 'jabatan_job', 'lingkup_job'];
            } elseif ($status === 'Melanjutkan Pendidikan') {
                $extraColumns = ['biaya_studi', 'jenjang_pendidikan', 'universitas', 'program_studi'];
            }

            $formattedData = $data->map(function ($alumni) use ($extraColumns) {
                $baseData = $alumni->only([
                    'name', 'email', 'foto_profil', 'jns_kelamin', 'nim',
                    'tahun_masuk', 'tahun_lulus', 'no_hp', 'status', 'masa_tunggu',
                ]);
                foreach ($extraColumns as $column) {
                    $baseData[$column] = $alumni->{$column};
                }
                return $baseData;
            });

            Log::info('Data alumni berhasil diambil', [
                'status' => $status,
                'tahun_lulus' => $tahunLulus,
                'total_data' => $formattedData->count(),
            ]);

            return response()->json([
                'status' => $status,
                'tahunLulus' => $tahunLulus,
                'data' => $formattedData,
            ]);
        } catch (\Exception $e) {
            Log::error('Error saat mengambil data alumni', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Terjadi kesalahan saat mengambil data alumni'], 500);
        }
    }
}
