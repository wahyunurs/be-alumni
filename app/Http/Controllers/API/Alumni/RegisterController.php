<?php

namespace App\Http\Controllers\API\Alumni;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Alumni;
use App\Models\Job;
use App\Models\Academic;
use App\Models\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOtpMail;
use Tymon\JWTAuth\Facades\JWTAuth;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'jns_kelamin' => 'required|string',
            'nim' => 'required|string|max:20|unique:alumni',
            'tahun_lulus' => 'required|numeric',
            'bulan_lulus' => 'required|string|max:20',
            'wisuda' => 'required|numeric',
            'no_hp' => 'required|string|max:15',
            'status' => 'required|string',
            'bidang_job' => 'nullable|string',
            'jns_job' => 'nullable|string',
            'nama_job' => 'nullable|string',
            'jabatan_job' => 'nullable|string',
            'lingkup_job' => 'nullable|string',
            'bulan_masuk_job' => 'nullable|string',
            'biaya_studi' => 'nullable|string',
            'jenjang_pendidikan' => 'nullable|string',
            'universitas' => 'nullable|string',
            'program_studi' => 'nullable|string',
            'mulai_studi' => 'nullable|date',
        ], [
            'password.min' => 'Password must be at least 8 characters.',
        ]);

        if ($validator->fails()) {
            Log::error('Validation errors: ', $validator->errors()->toArray());
            return response()->json($validator->errors(), 400);
        }

        Log::info('Request Data: ', $request->all());

        try {
            // Simpan ke tabel users
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'alumni',
            ]);
            Log::info('User created: ', ['user_id' => $user->id]);

            // Assign role alumni secara otomatis
            $user->assignRole('alumni');
            Log::info('Assigned role: ', [
                'user_id' => $user->id,
                'roles' => $user->getRoleNames(),
            ]);

            // Siapkan data untuk tabel alumni
            $nimParts = explode('.', $request->nim); 
            $tahunMasuk = isset($nimParts[1]) ? $nimParts[1] : 0; // Ambil tahun_masuk dari nim 

            // data required untuk data pada tabel alumni
            $alumniData = [
                'user_id' => $user->id,
                'email' => $request->email,
                'name' => $request->name,
                'jns_kelamin' => $request->jns_kelamin,
                'nim' => $request->nim,
                'tahun_masuk' => $tahunMasuk,
                'tahun_lulus' => $request->tahun_lulus,
                'bulan_lulus' => $request->bulan_lulus,
                'wisuda' => $request->wisuda,
                'no_hp' => $request->no_hp,
                'status' => $request->status,
            ];

            // simpan data alumni sesuai status bekerja dan wirausaha
            if (in_array($request->status, ['Bekerja Full Time', 'Bekerja Part Time', 'Wiraswasta'])) {
                $alumniData['bidang_job'] = $request->bidang_job;
                $alumniData['jns_job'] = $request->jns_job;
                $alumniData['nama_job'] = $request->nama_job;
                $alumniData['jabatan_job'] = $request->jabatan_job;
                $alumniData['lingkup_job'] = $request->lingkup_job;
                $alumniData['bulan_masuk_job'] = $request->bulan_masuk_job;
                $alumniData['periode_masuk_job'] = $request->periode_masuk_job;

                // Konversi bulan ke angka
                $bulanKeAngka = [
                    'Januari' => 1, 'Februari' => 2, 'Maret' => 3, 'April' => 4, 'Mei' => 5, 'Juni' => 6,
                    'Juli' => 7, 'Agustus' => 8, 'September' => 9, 'Oktober' => 10, 'November' => 11, 'Desember' => 12,
                ];
                $bulanLulus = $bulanKeAngka[$request->bulan_lulus] ?? null;
                $bulanMasukJob = $bulanKeAngka[$request->bulan_masuk_job] ?? null;                  

                if ($bulanLulus !== null && $bulanMasukJob !== null) {
                    $selisihTahun = $request->periode_masuk_job - $request->tahun_lulus;
                    $selisihBulan = $bulanMasukJob - $bulanLulus;

                // Hitung total bulan masa tunggu
                $totalBulan = ($selisihTahun * 12) + $selisihBulan;

                // Validasi total bulan untuk menghindari nilai negatif
                if ($totalBulan < 0) {
                    $totalBulan = 0; // Atur default ke 0 jika perhitungan menghasilkan nilai negatif
                }

                // Tentukan kategori masa tunggu
                if ($totalBulan < 6) {
                    $masaTunggu = 'kurang dari 6 bulan';
                } elseif ($totalBulan <= 12) {
                    $masaTunggu = '6 bulan - 12 bulan';
                } else {
                    $masaTunggu = 'Lebih dari 12 bulan';
                }
                } else {
                // Atur default jika data tidak valid
                $masaTunggu = 'kurang dari 6 bulan'; // Atur ke opsi yang paling relevan
                }

                // Simpan nilai masa tunggu
                $alumniData['masa_tunggu'] = $masaTunggu;            
            }

            // simpan data alumni sesuai status melanjutkan pendidikan
            if (in_array($request->status, ['Melanjutkan Pendidikan'])) {
                $alumniData['biaya_studi'] = $request->biaya_studi;
                $alumniData['jenjang_pendidikan'] = $request->jenjang_pendidikan;
                $alumniData['universitas'] = $request->universitas;
                $alumniData['program_studi'] = $request->program_studi;
                $alumniData['mulai_studi'] = $request->mulai_studi;      
            }

            // Log sebelum menyimpan alumni
            Log::info('Alumni Data: ', $alumniData);
            // Simpan ke tabel alumni
            $alumni = Alumni::create($alumniData);
            Log::info('Alumni data saved: ', ['alumni_id' => $alumni->id]);

            //ini menyimpan data job secara otomatis jika mengisikan status bekerja dan wirausaha
            if (in_array($request->status, ['Bekerja Full Time', 'Bekerja Part Time', 'Wiraswasta'])) {
                $job = Job::create([
                    'user_id' => $user->id,
                    'nama_job' => $request->nama_job,
                    'bulan_masuk_job' => $request->bulan_masuk_job,
                    'periode_masuk_job' => $request->periode_masuk_job,
                    'bulan_keluar_job' => $request->bulan_keluar_job ?? 'Tidak Diketahui',
                    'periode_keluar_job' => $request->periode_keluar_job ?? 0,
                    'jabatan_job' => $request->jabatan_job,
                    'kota' => $request->kota ?? 'Tidak Diketahui',
                    'negara' => $request->negara ?? 'Tidak Diketahui',
                    'catatan' => $request->bulan_keluar_job ?? 'Tidak Diketahui',
                ]);
                Log::info('Data job awal berhasil dibuat ', ['job_id' => $job->id]);
            } else {
                Log::info('Status tidak sesuai untuk penyimpanan data ke tabel jobs', ['status' => $request->status]);
            }
            
            //ini menyimpan data academic pertama secara otomatis karena telah  mengisikan data academic saat kuliah sti
            $academic = Academic::create([
                'user_id' => $user->id,
                'jenjang_pendidikan' => 'Sarjana',
                'nama_studi' => 'Universitas Dian Nuswantoro',
                'prodi' => 'Teknik Informatika',
                'ipk' => 0.00,
                'tahun_masuk' => $alumni->tahun_masuk,
                'tahun_lulus' => $alumni->tahun_lulus,
                'kota' => 'Semarang',
                'negara' => 'Indonesia',
                'catatan' => 'catatan',
            ]);
            Log::info('Data academic awal berhasil dibuat', ['academic_id' => $academic->id]);

            //ini menyimpan data academic secara otomatis jika mengisikan status melanjutkan pendidikan
            if (in_array($request->status, ['Melanjutkan Pendidikan'])) {
                $tahunMasuk = $alumni->mulai_studi ? \Carbon\Carbon::parse($alumni->mulai_studi)->year : 0;

                // Buat data pertama secara otomatis
                $academic = Academic::create([
                    'user_id' => $user->id,
                    'jenjang_pendidikan' => 'Magister',
                    'nama_studi' => $alumni->universitas,
                    'prodi' => $alumni->program_studi,
                    'ipk' => 0.00,
                    'tahun_masuk' => $tahunMasuk,
                    'tahun_lulus' => 0,
                    'kota' => 'kota',
                    'negara' => 'negara',
                    'catatan' => 'catatan',
                ]);
                Log::info('Data academic dengan status melanjutkan studi berhasil dibuat', ['academic_id' => $academic->id]);
            } else {
                Log::info('Status tidak sesuai untuk penyimpanan data ke tabel academics', ['status' => $request->status]);
            }

            // Generate OTP (6 digit)
            $otpCode = rand(100000, 999999);

            // Simpan OTP ke database
            Otp::create([
                'email' => $user->email,
                'otp' => $otpCode,
                'expires_at' => now()->addMinutes(15),
            ]);

            // Kirim OTP ke email pengguna
            Mail::to($user->email)->send(new SendOtpMail($otpCode));

            Log::info('OTP sent to user email: ', ['user_id' => $user->id, 'otp' => $otpCode]);

            // Kembalikan respons bahwa registrasi berhasil
            return response()->json([
                'message' => 'Registrasi berhasil. Silakan verifikasi OTP yang telah dikirim ke email.',
                'user' => [
                    'id' => $user->id,
                    'email' => $user->email,
                ]
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error during registration: ' . $e->getMessage(), [
                'request' => $request->all(),
                'user_id' => isset($user) ? $user->id : null
            ]);
            return response()->json(['error' => 'Terjadi kesalahan saat registrasi: ' . $e->getMessage()], 500);
        }
    }
}
