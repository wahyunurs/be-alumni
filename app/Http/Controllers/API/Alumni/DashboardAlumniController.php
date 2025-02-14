<?php

namespace App\Http\Controllers\API\Alumni;

use App\Http\Controllers\Controller;
use App\Models\Logang;
use App\Models\Loker;
use App\Models\Academic;
use App\Models\Job;
use App\Models\Internship;
use App\Models\Organization;
use App\Models\Award;
use App\Models\Course;
use App\Models\Interest;
use App\Models\User;
use App\Models\Skill;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class DashboardAlumniController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function dataLoker(Request $request)
    {
        try {
            // Ambil data loker yang telah diverifikasi
            $lokers = Loker::where('Verify', 'verified')
                    ->latest()
                    ->limit(3)
                    ->get();

            // Mengambil ID pengguna yang sedang login
            $user_id = Auth::id();
            Log::info('Data berhasil diambil', [
                'user_id' => $user_id,
                'lokers_count' => $lokers->count(),
            ]);

            // Menggabungkan semua data dalam satu respons JSON
            return response()->json([
                'status' => 'success',
                'data' => $lokers

            ]);
        } catch (\Exception $e) {
            Log::error('Error saat mengambil data dashboard', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Terjadi kesalahan saat mengambil data dashboard'], 500);
        }
    }
    
    public function dataLogang(Request $request)
    {
        try {
            // Ambil data logang dan logang yang telah diverifikasi
            $logangs = Logang::where('Verify', 'verified')
                    ->latest()
                    ->limit(3)
                    ->get();

            // Mengambil ID pengguna yang sedang login
            $user_id = Auth::id();
            Log::info('Data berhasil diambil', [
                'user_id' => $user_id,
                'logangs_count' => $logangs->count(),
            ]);

            // Menggabungkan semua data dalam satu respons JSON
            return response()->json([
                'status' => 'success',
                'data' => $logangs,
                
            ]);
        } catch (\Exception $e) {
            Log::error('Error saat mengambil data dashboard', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Terjadi kesalahan saat mengambil data dashboard'], 500);
        }
    }

    public function dataAlumni()
    {
        try {
            // Mengambil ID pengguna yang sedang login
            $user_id = Auth::id();

            // Mengambil data dengan informasi lengkap untuk setiap kategori
            $dataStatus = [
                'academic' => [
                    'exists' => Academic::where('user_id', $user_id)->exists(),
                    'count' => Academic::where('user_id', $user_id)->count(),
                    'updated_at' => Academic::where('user_id', $user_id)->latest('updated_at')->value('updated_at'),
                ],
                'job' => [
                    'exists' => Job::where('user_id', $user_id)->exists(),
                    'count' => Job::where('user_id', $user_id)->count(),
                    'updated_at' => Job::where('user_id', $user_id)->latest('updated_at')->value('updated_at'),
                ],
                'internship' => [
                    'exists' => Internship::where('user_id', $user_id)->exists(),
                    'count' => Internship::where('user_id', $user_id)->count(),
                    'updated_at' => Internship::where('user_id', $user_id)->latest('updated_at')->value('updated_at'),
                ],
                'organization' => [
                    'exists' => Organization::where('user_id', $user_id)->exists(),
                    'count' => Organization::where('user_id', $user_id)->count(),
                    'updated_at' => Organization::where('user_id', $user_id)->latest('updated_at')->value('updated_at'),
                ],
                'award' => [
                    'exists' => Award::where('user_id', $user_id)->exists(),
                    'count' => Award::where('user_id', $user_id)->count(),
                    'updated_at' => Award::where('user_id', $user_id)->latest('updated_at')->value('updated_at'),
                ],
                'course' => [
                    'exists' => Course::where('user_id', $user_id)->exists(),
                    'count' => Course::where('user_id', $user_id)->count(),
                    'updated_at' => Course::where('user_id', $user_id)->latest('updated_at')->value('updated_at'),
                ],                
                'interest' => [
                    'exists' => Interest::whereHas('users', function ($query) use ($user_id) {
                        $query->where('user_id', $user_id);
                    })->exists(),
                    'count' => User::find($user_id)->interests->count(),
                    'updated_at' => Interest::whereHas('users', function ($query) use ($user_id) {
                        $query->where('user_id', $user_id);
                    })->latest('updated_at')->value('updated_at'),
                ],
                'skill' => [
                    'exists' => Skill::where('user_id', $user_id)->exists(),
                    'count' => Skill::where('user_id', $user_id)->count(),
                    'updated_at' => Skill::where('user_id', $user_id)->latest('updated_at')->value('updated_at'),
                ],
            ];

            Log::info('Data alumni berhasil diambil', ['user_id' => $user_id]);

            // Mengembalikan respons JSON dengan data lengkap
            return response()->json([
                'status' => 'success',
                'dataStatus' => $dataStatus,
            ]);
        } catch (\Exception $e) {
            Log::error('Error saat mengambil data alumni pada dashboard', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Terjadi kesalahan saat mengambil data alumni pada dashboard'], 500);
        }
    }


    /**
     * Display the specified Loker resource.
     */
    public function showLoker($id)
    {
        try {
            $loker = Loker::findOrFail($id);

            Log::info('Data Loker berhasil diambil', ['loker_id' => $id]);
            return response()->json([
                'status' => 'success',
                'data' => $loker,
            ]);
        } catch (\Exception $e) {
            Log::error('Error saat mengambil data Loker', [
                'loker_id' => $id,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['message' => 'Terjadi kesalahan saat mengambil data Loker'], 500);
        }
    }

    /**
     * Display the specified Logang resource.
     */
    public function showLogang($id)
    {
        try {
            $logang = Logang::findOrFail($id);

            Log::info('Data Logang berhasil diambil', ['logang_id' => $id]);
            return response()->json([
                'status' => 'success',
                'data' => $logang,
            ]);
        } catch (\Exception $e) {
            Log::error('Error saat mengambil data Logang', [
                'logang_id' => $id,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['message' => 'Terjadi kesalahan saat mengambil data Logang'], 500);
        }
    }
}
