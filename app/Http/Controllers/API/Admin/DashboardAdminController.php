<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Job;
use App\Models\User;
use App\Models\Alumni;
use App\Models\Statistik;
use App\Models\SurveiMitra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardAdminController extends Controller
{
    public function index()
    {
        try {
            // menghitung total alumni secara keseluruhan
            $totalAlumniTerlacak = Alumni::count('user_id');
            Log::info('Total alumni semua tahun yang terlacak berhasil dihitung.', ['totalAlumniTerlacak' => $totalAlumniTerlacak]);

            // Menghitung total keseluruhan alumni dari data asli (dari tabel statistiks)
            $totalAlumniAsli = Statistik::sum('alumni_total');
            Log::info('Total alumni semua tahun berhasil dihitung.', ['totalAlumniAsli' => $totalAlumniAsli]);

            // Menghitung persentase alumni yang sudah terlacak
            $persentaseTerlacak = ($totalAlumniAsli > 0) ? ($totalAlumniTerlacak / $totalAlumniAsli) * 100 : 0;
            Log::info('Presentase ALumni Terlacak berhasil dihitung.');

            // menghitung total alumni tahun saat ini
            $currentYear = Carbon::now()->year; // Mendapatkan tahun saat ini
            $totalAlumniTahunLulusSaatIni = Alumni::where('tahun_lulus', $currentYear)->count('user_id');
            Log::info('Total alumni tahun lulus ' . $currentYear . ' berhasil dihitung.', ['totalAlumniTahunLulusSaatIni' => $totalAlumniTahunLulusSaatIni]);
           
            // Menghitung jumlah alumni berdasarkan status
            $jumlahStatus = [
                'Bekerja Full Time' => Alumni::where('status', 'Bekerja Full Time')->count(),
                'Bekerja Part Time' => Alumni::where('status', 'Bekerja Part Time')->count(),
                'Wirausaha' => Alumni::where('status', 'Wirausaha')->count(),
                'Melanjutkan Pendidikan' => Alumni::where('status', 'Melanjutkan Pendidikan')->count(),
                'Tidak Bekerja Tetapi Sedang Mencari Pekerjaan' => Alumni::where('status', 'Tidak Bekerja Tetapi Sedang Mencari Pekerjaan')->count(),
                'Belum Memungkinkan Bekerja' => Alumni::where('status', 'Belum Memungkinkan Bekerja')->count(),
                'Menikah/Mengurus Keluarga' => Alumni::where('status', 'Menikah/Mengurus Keluarga')->count(),
            ];
            Log::info('Jumlah alumni berdasarkan status berhasil dihitung.', ['jumlahStatus' => $jumlahStatus]);

            // menghitung jumlah alumni bekerja dan tidak bekerja
            $bekerja = $jumlahStatus['Bekerja Full Time'] + $jumlahStatus['Bekerja Part Time'] + $jumlahStatus['Wirausaha'];
            $tidakBekerja = $jumlahStatus['Tidak Bekerja Tetapi Sedang Mencari Pekerjaan'] + $jumlahStatus['Belum Memungkinkan Bekerja'] + $jumlahStatus['Menikah/Mengurus Keluarga'];
            Log::info('Data pekerjaan alumni berhasil dihitung.', ['bekerja' => $bekerja, 'tidakBekerja' => $tidakBekerja]);

            $totalMitra = User::where('role', 'mitra')->count();
            Log::info('Jumlah pengguna dengan role mitra berhasil dihitung.', ['totalMitra' => $totalMitra]);

            // menghitung instansi mitra alumni bekerja
            $totalInstansiBekerja = Job::distinct('nama_job')->count('nama_job');
            Log::info('Total instansi tempat alumni bekerja berhasil dihitung.', ['totalInstansiBekerja' => $totalInstansiBekerja]);

            //menghitung intansi mitra yang udah mengisi survei alumni
            $totalInstansiMitra = SurveiMitra::distinct('name')->count('name');
            Log::info('Total instansi mitra yang mengisi survei berhasil dihitung.', ['totalInstansiMitra' => $totalInstansiMitra]);

            //menghitung berapa banya survei alumni yang sudah diisi oleh mitra
            $totalSurveiAlumni = SurveiMitra::count();
            Log::info('Total survei alumni yang sudah diisi oleh mitra berhasil dihitung.', ['totalSurveiAlumni' => $totalSurveiAlumni]);

            return response()->json([
                'totalAlumniTerlacak' => $totalAlumniTerlacak,
                'totalAlumniAsli' => $totalAlumniAsli,
                'persentaseTerlacak' => round($persentaseTerlacak, 2) . '%',
                'totalAlumniTahunLulusSaatIni' => $totalAlumniTahunLulusSaatIni,
                'jumlahStatus' => $jumlahStatus,
                'bekerja' => $bekerja,
                'tidakBekerja' => $tidakBekerja,
                'totalMitra' => $totalMitra,
                'totalInstansiBekerja' => $totalInstansiBekerja,
                'totalInstansiMitra' => $totalInstansiMitra,
                'totalSurveiAlumni' => $totalSurveiAlumni,
            ]);
        } catch (\Exception $e) {
            Log::error('Terjadi kesalahan pada DashboardAdminController@index', ['pesan' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Terjadi kesalahan saat memproses permintaan Anda.'], 500);
        }
    }
}
