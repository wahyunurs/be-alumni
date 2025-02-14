<?php

namespace App\Http\Controllers\API\Alumni\CVAlumni;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Alumni;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class JobAlumniController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $userId = Auth::id();
            $jobs = Job::where('user_id', $userId)->latest()->paginate(10);

            return response()->json($jobs);
        } catch (\Exception $e) {
            Log::error("Error mengambil data pekerjaan: " . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat mengambil data pekerjaan.'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nama_job' => 'required',
                'bulan_masuk_job' => 'required',
                'periode_masuk_job' => 'required',
                'bulan_keluar_job' => 'required',
                'periode_keluar_job' => 'required',
                'jabatan_job' => 'required',
                'kota' => 'required',
                'alamat' => 'required',
                'negara' => 'required',
                'catatan' => 'required'
            ]);

            $job = Job::create(array_merge($validatedData, ['user_id' => Auth::id()]));
            Log::info("Berhasil menyimpan data pekerjaan untuk user_id: " . Auth::id(), ['data' => $job]);

            return response()->json(['success' => 'Data Berhasil Disimpan!', 'data' => $job], 201);
        } catch (\Exception $e) {
            Log::error("Gagal menyimpan data pekerjaan untuk user_id: " . Auth::id() . ". Kesalahan: " . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat menyimpan data pekerjaan.'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $job = Job::findOrFail($id);

            if ($job->user_id !== Auth::id()) {
                Log::warning("Akses tidak sah ke data pekerjaan ID: {$id} oleh user_id: " . Auth::id());
                return response()->json(['error' => 'Tindakan tidak sah.'], 403);
            }

            Log::info("Berhasil mengambil data pekerjaan dengan ID: {$id} untuk user_id: " . Auth::id());
            return response()->json($job);
        } catch (\Exception $e) {
            Log::error("Gagal mengambil data pekerjaan dengan ID: {$id}. Kesalahan: " . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat mengambil data pekerjaan.'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validatedData = $request->validate([
                'nama_job' => 'required',
                'bulan_masuk_job' => 'required',
                'periode_masuk_job' => 'required',
                'bulan_keluar_job' => 'required',
                'periode_keluar_job' => 'required',
                'jabatan_job' => 'required',
                'kota' => 'required',
                'alamat' => 'required',
                'negara' => 'required',
                'catatan' => 'required'
            ]);

            $job = Job::findOrFail($id);

            if ($job->user_id !== Auth::id()) {
                Log::warning("Akses tidak sah untuk mengupdate data pekerjaan ID: {$id} oleh user_id: " . Auth::id());
                return response()->json(['error' => 'Tindakan tidak sah.'], 403);
            }

            $job->update($validatedData);
            Log::info("Berhasil mengupdate data pekerjaan ID: {$id} untuk user_id: " . Auth::id());

            return response()->json(['success' => 'Data Berhasil Diubah!', 'data' => $job]);
        } catch (\Exception $e) {
            Log::error("Gagal mengupdate data pekerjaan ID: {$id}. Kesalahan: " . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat mengupdate data pekerjaan.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $job = Job::findOrFail($id);

            if ($job->user_id !== Auth::id()) {
                Log::warning("Akses tidak sah untuk menghapus data pekerjaan ID: {$id} oleh user_id: " . Auth::id());
                return response()->json(['error' => 'Tindakan tidak sah.'], 403);
            }

            $job->delete();
            Log::info("Berhasil menghapus data pekerjaan ID: {$id} untuk user_id: " . Auth::id());

            return response()->json(['success' => 'Data Berhasil Dihapus!']);
        } catch (\Exception $e) {
            Log::error("Gagal menghapus data pekerjaan ID: {$id}. Kesalahan: " . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat menghapus data pekerjaan.'], 500);
        }
    }
}
