<?php

namespace App\Http\Controllers\API\Mahasiswa\CVMhs;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Alumni;
use App\Models\Academic;
use App\Models\Job;
use App\Models\Internship;
use App\Models\Organization;
use App\Models\Award;
use App\Models\Course;
use App\Models\Skill;

class CVMhsController extends Controller
{
    public function cetakCv()
    {
        // Mengambil ID pengguna yang sedang login
        $user_id = Auth::id();
        $data = [];

        try {
            // Data alumni dengan kolom tertentu
            $data['alumni'] = Alumni::where('user_id', $user_id)
                ->select('name', 'email', 'no_hp')
                ->first();

            // Jika data alumni tidak ditemukan
            if (!$data['alumni']) {
                return response()->json(['error' => 'Data alumni tidak ditemukan.'], 404);
            }

            // Mendefinisikan model dan kolom yang diambil
            $models = [
                'academics' => [Academic::class, ['nama_studi', 'prodi', 'ipk', 'tahun_masuk', 'tahun_lulus', 'kota', 'negara', 'catatan']],
                'jobs' => [Job::class, ['nama_job', 'bulan_masuk_job', 'periode_masuk_job', 'bulan_keluar_job', 'periode_keluar_job', 'jabatan_job', 'kota', 'negara', 'catatan']],
                'internships' => [Internship::class, ['nama_intern', 'bulan_masuk_intern', 'periode_masuk_intern', 'bulan_keluar_intern', 'periode_keluar_intern', 'jabatan_intern', 'kota', 'negara', 'catatan']],
                'organizations' => [Organization::class, ['nama_org', 'bulan_masuk_org', 'periode_masuk_org', 'bulan_keluar_org', 'periode_keluar_org', 'jabatan_org', 'kota', 'negara', 'catatan']],
                'awards' => [Award::class, ['nama_award', 'institusi_award', 'tingkat_award', 'tahun_award', 'deskripsi_award']],
                'courses' => [Course::class, ['nama_course', 'institusi_course', 'tingkat_course', 'tahun_course']],
                'skills' => [Skill::class, ['kerjasama_skill', 'ahli_skill', 'inggris_skill', 'komunikasi_skill', 'pengembangan_skill', 'kepemimpinan_skill', 'etoskerja_skill']],
            ];

            // Mengambil data dari setiap model
            foreach ($models as $key => [$model, $columns]) {
                $entries = $model::where('user_id', $user_id)
                    ->select($columns)
                    ->get();

                if ($entries->isNotEmpty()) {
                    $data[$key] = $entries;
                }
            }

            // Log keberhasilan
            Log::info('Data CV berhasil diambil', ['user_id' => $user_id]);
            return response()->json($data);

        } catch (\Exception $e) {
            // Log kesalahan
            Log::error('Terjadi kesalahan saat mengambil data CV', [
                'user_id' => $user_id,
                'error' => $e->getMessage()
            ]);

            // Mengembalikan respons kesalahan
            return response()->json(['error' => 'Terjadi kesalahan saat mengambil data CV.'], 500);
        }
    }
}
