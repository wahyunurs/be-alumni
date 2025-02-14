<?php

namespace App\Http\Controllers\API\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Logang;
use App\Models\Academic;
use App\Models\Job;
use App\Models\Internship;
use App\Models\Organization;
use App\Models\Award;
use App\Models\Course;
use App\Models\Skill;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class DashboardMhsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function dataLogang(Request $request)
    {
        try {
            // Fetch logang data based on selected filters
            $logangs = Logang::where('Verify', 'verified')
                    ->latest()
                    ->limit(3)
                    ->get();


            // Mengambil ID pengguna yang sedang login
            $user_id = Auth::id();

            // Menggabungkan semua data dalam satu respons JSON
            return response()->json([
                'status' => 'success',
                'data' => $logangs
            ]);
        } catch (\Exception $e) {
            Log::error("Error mengambil data dashboard mahasiswa: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengambil data dashboard mahasiswa.',
            ], 500);
        }
    }
    
    public function dataMhs()
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
                'work' => [
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
                'skill' => [
                    'exists' => Skill::where('user_id', $user_id)->exists(),
                    'count' => Skill::where('user_id', $user_id)->count(),
                    'updated_at' => Skill::where('user_id', $user_id)->latest('updated_at')->value('updated_at'),
                ],
            ];

            Log::info('Data mahasiswa berhasil diambil', ['user_id' => $user_id]);

            // Mengembalikan respons JSON dengan data lengkap
            return response()->json([
                'status' => 'success',
                'dataStatus' => $dataStatus,
            ]);
        } catch (\Exception $e) {
            Log::error('Error saat mengambil data mahasiswa pada dashboard', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Terjadi kesalahan saat mengambil data mahasiswa pada dashboard'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function showLogang($id)
    {
        try {
            $logang = Logang::findOrFail($id);

            return response()->json([
                'status' => 'success',
                'data' => $logang
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning("Data logang dengan ID {$id} tidak ditemukan.");
            return response()->json([
                'status' => 'error',
                'message' => 'Data logang tidak ditemukan.',
            ], 404);
        } catch (\Exception $e) {
            Log::error("Error mengambil data logang: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengambil data logang.',
            ], 500);
        }
    }
}
