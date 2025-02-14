<?php

namespace App\Http\Controllers\API\Mahasiswa\CVMhs;

use App\Http\Controllers\Controller;
use App\Models\Internship;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class InternshipMhsController extends Controller
{
    public function index()
    {
        try {
            $userId = Auth::id();
            $internships = Internship::where('user_id', $userId)->latest()->paginate(10);

            return response()->json($internships);
        } catch (\Exception $e) {
            Log::error("Error mengambil data internship: " . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat mengambil data internship.'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nama_intern' => 'required',
                'bulan_masuk_intern' => 'required',
                'periode_masuk_intern' => 'required',
                'bulan_keluar_intern' => 'required',
                'periode_keluar_intern' => 'required',
                'jabatan_intern' => 'required',
                'kota' => 'required',
                'negara' => 'required',
                'catatan' => 'required'
            ]);

            $internship = Internship::create(array_merge($validatedData, [
                'user_id' => Auth::id(),
            ]));

            return response()->json(['success' => 'Data Berhasil Disimpan!', 'data' => $internship], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning("Validasi gagal saat menyimpan data internship: " . json_encode($e->errors()));
            return response()->json(['error' => 'Validasi data gagal.', 'detail' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error("Error menyimpan data internship: " . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat menyimpan data internship.'], 500);
        }
    }

    public function show(string $id)
    {
        try {
            $internship = Internship::findOrFail($id);

            if ($internship->user_id !== Auth::id()) {
                return response()->json(['error' => 'Aksi tidak diizinkan.'], 403);
            }

            return response()->json($internship);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning("Data internship dengan ID {$id} tidak ditemukan.");
            return response()->json(['error' => 'Data internship tidak ditemukan.'], 404);
        } catch (\Exception $e) {
            Log::error("Error mengambil data internship: " . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat mengambil data internship.'], 500);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $validatedData = $request->validate([
                'nama_intern' => 'required',
                'bulan_masuk_intern' => 'required',
                'periode_masuk_intern' => 'required',
                'bulan_keluar_intern' => 'required',
                'periode_keluar_intern' => 'required',
                'jabatan_intern' => 'required',
                'kota' => 'required',
                'negara' => 'required',
                'catatan' => 'required'
            ]);

            $internship = Internship::findOrFail($id);

            if ($internship->user_id !== Auth::id()) {
                return response()->json(['error' => 'Aksi tidak diizinkan.'], 403);
            }

            $internship->update($validatedData);

            return response()->json(['success' => 'Data Berhasil Diubah!', 'data' => $internship]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning("Validasi gagal saat memperbarui data internship: " . json_encode($e->errors()));
            return response()->json(['error' => 'Validasi data gagal.', 'detail' => $e->errors()], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning("Data internship dengan ID {$id} tidak ditemukan.");
            return response()->json(['error' => 'Data internship tidak ditemukan.'], 404);
        } catch (\Exception $e) {
            Log::error("Error memperbarui data internship: " . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat memperbarui data internship.'], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $internship = Internship::findOrFail($id);

            if ($internship->user_id !== Auth::id()) {
                return response()->json(['error' => 'Aksi tidak diizinkan.'], 403);
            }

            $internship->delete();

            return response()->json(['success' => 'Data Berhasil Dihapus!']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning("Data internship dengan ID {$id} tidak ditemukan.");
            return response()->json(['error' => 'Data internship tidak ditemukan.'], 404);
        } catch (\Exception $e) {
            Log::error("Error menghapus data internship: " . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat menghapus data internship.'], 500);
        }
    }
}
