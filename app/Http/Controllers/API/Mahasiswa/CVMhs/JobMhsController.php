<?php

namespace App\Http\Controllers\API\Mahasiswa\CVMhs;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class JobMhsController extends Controller
{
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
                'negara' => 'required',
                'catatan' => 'required'
            ]);

            $job = Job::create(array_merge($validatedData, [
                'user_id' => Auth::id(),
            ]));

            return response()->json(['success' => 'Data Berhasil Disimpan!', 'data' => $job], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning("Validasi gagal saat menyimpan data pekerjaan: " . json_encode($e->errors()));
            return response()->json(['error' => 'Validasi data gagal.', 'detail' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error("Error menyimpan data pekerjaan: " . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat menyimpan data pekerjaan.'], 500);
        }
    }

    public function show(string $id)
    {
        try {
            $job = Job::findOrFail($id);

            if ($job->user_id !== Auth::id()) {
                return response()->json(['error' => 'Aksi tidak diizinkan.'], 403);
            }

            return response()->json($job);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning("Data pekerjaan dengan ID {$id} tidak ditemukan.");
            return response()->json(['error' => 'Data pekerjaan tidak ditemukan.'], 404);
        } catch (\Exception $e) {
            Log::error("Error mengambil data pekerjaan: " . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat mengambil data pekerjaan.'], 500);
        }
    }

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
                'negara' => 'required',
                'catatan' => 'required'
            ]);

            $job = Job::findOrFail($id);

            if ($job->user_id !== Auth::id()) {
                return response()->json(['error' => 'Aksi tidak diizinkan.'], 403);
            }

            $job->update($validatedData);

            return response()->json(['success' => 'Data Berhasil Diubah!', 'data' => $job]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning("Validasi gagal saat memperbarui data pekerjaan: " . json_encode($e->errors()));
            return response()->json(['error' => 'Validasi data gagal.', 'detail' => $e->errors()], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning("Data pekerjaan dengan ID {$id} tidak ditemukan.");
            return response()->json(['error' => 'Data pekerjaan tidak ditemukan.'], 404);
        } catch (\Exception $e) {
            Log::error("Error memperbarui data pekerjaan: " . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat memperbarui data pekerjaan.'], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $job = Job::findOrFail($id);

            if ($job->user_id !== Auth::id()) {
                return response()->json(['error' => 'Aksi tidak diizinkan.'], 403);
            }

            $job->delete();

            return response()->json(['success' => 'Data Berhasil Dihapus!']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning("Data pekerjaan dengan ID {$id} tidak ditemukan.");
            return response()->json(['error' => 'Data pekerjaan tidak ditemukan.'], 404);
        } catch (\Exception $e) {
            Log::error("Error menghapus data pekerjaan: " . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat menghapus data pekerjaan.'], 500);
        }
    }
}
