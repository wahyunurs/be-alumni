<?php

namespace App\Http\Controllers\API\Alumni;

use App\Models\Alumni;
use App\Models\Job;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileAlumniController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Memeriksa apakah pengguna sudah terautentikasi
        if (!Auth::check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthenticated user',
            ], 401);
        }

        $user = Auth::user();
        // Mengambil data alumni berdasarkan email pengguna
        $alumni = Alumni::where('email', $user->email)->first();

        if ($alumni) {
            return response()->json([
                'status' => 'success',
                'data' => [
                    'name' => $alumni->name,
                    'email' => $alumni->email,
                    'foto_profil' => $alumni->foto_profil,
                    'jns_kelamin' => $alumni->jns_kelamin,
                    'nim' => $alumni->nim,
                    'tahun_masuk' => $alumni->tahun_masuk,
                    'tahun_lulus' => $alumni->tahun_lulus,
                    'no_hp' => $alumni->no_hp,
                    'status' => $alumni->status,
                    'masa_tunggu' => $alumni->masa_tunggu,
                    'bidang_job' => $alumni->bidang_job,
                    'jns_job' => $alumni->jns_job,
                    'nama_job' => $alumni->nama_job,
                    'jabatan_job' => $alumni->jabatan_job,
                    'lingkup_job' => $alumni->lingkup_job,
                    'bulan_masuk_job' => $alumni->bulan_masuk_job,
                    'biaya_studi' => $alumni->biaya_studi,
                    'jenjang_pendidikan' => $alumni->jenjang_pendidikan,
                    'universitas' => $alumni->universitas,
                    'program_studi' => $alumni->program_studi,
                    'mulai_studi' => $alumni->mulai_studi,
                ],
            ], 200);
        }

        // Jika tidak ditemukan data alumni, berikan pesan yang lebih informatif
        return response()->json([
            'status' => 'error',
            'message' => 'Alumni data not found. Please ensure that you are registered as an alumni.',
        ], 404);
    }

    /**
     * 
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'foto_profil' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'foto_profil.required' => 'Please upload a profile picture.',
            'foto_profil.image' => 'The file must be an image.',
            'foto_profil.mimes' => 'The profile picture must be a file of type: jpeg, png, jpg.',
            'foto_profil.max' => 'The profile picture size must not exceed 2MB.',
        ]);
        
        $alumni = Auth::user()->alumni;

        if ($request->hasFile('foto_profil')) {
            $file = $request->file('foto_profil');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/foto_profils', $filename);

            // Hapus file lama jika ada
            if ($alumni->foto_profil) {
                $oldFilePath = storage_path('app/public/foto_profils/' . $alumni->foto_profil);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }
            // Simpan nama file ke database di tabel users
            $alumni->foto_profil = $filename; // Pastikan kolom ini ada di tabel users
            $alumni->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Profile picture uploaded successfully!',
                'data' => [
                    'foto_profil' => $filename,
                ]
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'No profile picture found!',
        ], 400);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // Validate input
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'The current password is required.',
            'new_password.required' => 'The new password is required.',
            'new_password.min' => 'The new password must be at least 8 characters.',
            'new_password.confirmed' => 'The new password confirmation does not match.',
        ]);

        // Check if the old password matches
        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Current password does not match.',
            ], 400);
        }

        // Change password
        Auth::user()->update([
            'password' => Hash::make($request->new_password),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Password changed successfully.',
        ], 200);
    }

    /**
     * Edit profil alumni
     */
    // public function editProfil(Request $request)
    // {
    //     // Mendapatkan data pengguna yang sedang login
    //     $user = Auth::user();

    //     // Mengambil data alumni berdasarkan email pengguna
    //     $alumni = Alumni::where('email', $user->email)->first();

    //     // Jika tidak ditemukan data alumni
    //     if (!$alumni) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Alumni data not found. Please ensure that you are registered as an alumni.',
    //         ], 404);
    //     }

    //     // Validasi input
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|string|email|max:255|unique:alumni,email,' . $alumni->id,
    //         'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi untuk file foto_profil
    //         'existingFotoProfil' => 'nullable|string', // Jika foto profil lama digunakan
    //         'nim' => 'required|string|max:20|unique:alumni,nim,' . $alumni->id,
    //         'jns_kelamin' => 'required|string',
    //         'tahun_masuk' => 'required|numeric|min:1900|max:2024',
    //         'tahun_lulus' => 'required|numeric|min:1900|max:2024',
    //         'no_hp' => 'required|string|max:15',
    //         'status' => 'required|string',
    //         'masa_tunggu' => 'required|string',
    //         'bidang_job' => 'nullable|string',
    //         'jns_job' => 'nullable|string',
    //         'nama_job' => 'nullable|string',
    //         'jabatan_job' => 'nullable|string',
    //         'lingkup_job' => 'nullable|string',
    //         'biaya_studi' => 'nullable|string',
    //         'jenjang_pendidikan' => 'nullable|string',
    //         'universitas' => 'nullable|string',
    //         'program_studi' => 'nullable|string'
    //     ]);

    //     // Proses upload foto_profil
    //     $filename = null;

    //     if ($request->hasFile('foto_profil')) {
    //         // Ambil file dan simpan
    //         $image = $request->file('foto_profil');
    //         $filename = date('Y-m-d') . '_' . $image->getClientOriginalName();
    //         $path = '/foto_profil/' . $filename;

    //         // Simpan file baru
    //         Storage::disk('public')->put($path, file_get_contents($image));

    //         // Hapus file lama jika ada
    //         if ($alumni->foto_profil) {
    //             Storage::disk('public')->delete('/foto_profil/' . $alumni->foto_profil);
    //         }
    //     } else {
    //         // Gunakan foto profil lama jika tidak ada file baru
    //         $filename = $request->input('existingFotoProfil') ?? $alumni->foto_profil;
    //     }

    //     // Update data alumni
    //     $alumni->update([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'foto_profil' => $filename,
    //         'nim' => $request->nim,
    //         'jns_kelamin' => $request->jns_kelamin,
    //         'tahun_masuk' => $request->tahun_masuk,
    //         'tahun_lulus' => $request->tahun_lulus,
    //         'no_hp' => $request->no_hp,
    //         'status' => $request->status,
    //         'masa_tunggu' => $request->masa_tunggu,
    //         'bidang_job' => $request->bidang_job,
    //         'jns_job' => $request->jns_job,
    //         'nama_job' => $request->nama_job,
    //         'jabatan_job' => $request->jabatan_job,
    //         'lingkup_job' => $request->lingkup_job,
    //         'biaya_studi' => $request->biaya_studi,
    //         'jenjang_pendidikan' => $request->jenjang_pendidikan,
    //         'universitas' => $request->universitas,
    //         'program_studi' => $request->program_studi
    //     ]);

    //     // Kembalikan respons sukses
    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Profile updated successfully.',
    //         'data' => $alumni
    //     ], 200);
    // }
    public function editProfil(Request $request)
    {
            // Mendapatkan data pengguna yang sedang login
            $user = Auth::user();

            // Mengambil data alumni berdasarkan email pengguna
            $alumni = Alumni::where('email', $user->email)->first();

            // Jika tidak ditemukan data alumni
            if (!$alumni) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Alumni data not found. Please ensure that you are registered as an alumni.',
                ], 404);
            }

            // Validasi input
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:alumni,email,' . $alumni->id,
                'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi untuk file foto_profil
                'existingFotoProfil' => 'nullable|string', // Jika foto profil lama digunakan
                'nim' => 'required|string|max:20|unique:alumni,nim,' . $alumni->id,
                'jns_kelamin' => 'required|string',
                'tahun_masuk' => 'required|numeric|min:1900|max:2024',
                'tahun_lulus' => 'required|numeric|min:1900|max:2024',
                'no_hp' => 'required|string|max:15',
                'status' => 'required|string',
                'masa_tunggu' => 'required|string',
                'bidang_job' => 'nullable|string',
                'jns_job' => 'nullable|string',
                'nama_job' => 'nullable|string',
                'jabatan_job' => 'nullable|string',
                'lingkup_job' => 'nullable|string',
                'biaya_studi' => 'nullable|string',
                'jenjang_pendidikan' => 'nullable|string',
                'universitas' => 'nullable|string',
                'program_studi' => 'nullable|string'
            ]);

            // Proses upload foto_profil
            $filename = null;

            if ($request->hasFile('foto_profil')) {
                // Ambil file dan simpan
                $image = $request->file('foto_profil');
                $filename = date('Y-m-d') . '_' . $image->getClientOriginalName();
                $path = '/foto_profil/' . $filename;

                // Simpan file baru
                Storage::disk('public')->put($path, file_get_contents($image));

                // Hapus file lama jika ada
                if ($alumni->foto_profil) {
                    Storage::disk('public')->delete('/foto_profil/' . $alumni->foto_profil);
                }
            } else {
                // Gunakan foto profil lama jika tidak ada file baru
                $filename = $request->input('existingFotoProfil') ?? $alumni->foto_profil;
            }

            // Update data alumni
            $alumni->update([
                'name' => $request->name,
                'email' => $request->email,
                'foto_profil' => $filename,
                'nim' => $request->nim,
                'jns_kelamin' => $request->jns_kelamin,
                'tahun_masuk' => $request->tahun_masuk,
                'tahun_lulus' => $request->tahun_lulus,
                'no_hp' => $request->no_hp,
                'status' => $request->status,
                'masa_tunggu' => $request->masa_tunggu,
                'bidang_job' => $request->bidang_job,
                'jns_job' => $request->jns_job,
                'nama_job' => $request->nama_job,
                'jabatan_job' => $request->jabatan_job,
                'lingkup_job' => $request->lingkup_job,
                'biaya_studi' => $request->biaya_studi,
                'jenjang_pendidikan' => $request->jenjang_pendidikan,
                'universitas' => $request->universitas,
                'program_studi' => $request->program_studi
            ]);

            if ($request->filled(['nama_job', 'jabatan_job'])) {
                // Cari job berdasarkan nama_job dan user_id
                $existingJob = Job::where('nama_job', $request->nama_job)
                                  ->where('user_id', Auth::id()) // Gunakan Auth::id() untuk ID user
                                  ->first();
            
                if ($existingJob) {
                    // Jika nama_job sama tetapi jabatan berbeda, update jabatan
                    if ($existingJob->jabatan_job !== $request->jabatan_job) {
                        $existingJob->jabatan_job = $request->jabatan_job;
                        $existingJob->save();
            
                        return response()->json([
                            'message' => 'Job updated with new position.',
                            'data' => $existingJob
                        ], 200);
                    }
            
                    // Jika nama_job dan jabatan_job sama, tidak perlu perubahan
                    return response()->json([
                        'message' => 'Job with the same name and position already exists.',
                        'data' => $existingJob
                    ], 400);
                }
            
                // Jika nama_job belum ada, buat job baru
                $job = new Job();
                $job->user_id = Auth::id();
                $job->nama_job = $request->nama_job;
                $job->jabatan_job = $request->jabatan_job;
                $job->bulan_masuk_job = $request->bulan_masuk_job ?? 0;
                $job->periode_masuk_job = $request->periode_masuk_job ?? 0;
                $job->bulan_keluar_job = $request->bulan_keluar_job ?? 0;
                $job->periode_keluar_job = $request->periode_keluar_job ?? 0;
                $job->kota = $request->kota ?? null;
                $job->negara = $request->negara ?? null;
                $job->catatan = $request->catatan ?? null;
            
                $job->save();
            
                return response()->json([
                    'message' => 'New job created successfully.',
                    'data' => $job
                ], 201);
            }
            
        // Kembalikan respons sukses
        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated successfully.',
            'data' => $alumni
        ], 200);
    }

}
