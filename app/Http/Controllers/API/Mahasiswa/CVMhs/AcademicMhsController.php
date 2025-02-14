<?php

namespace App\Http\Controllers\API\Mahasiswa\CVMhs;

use App\Http\Controllers\Controller;
use App\Models\Academic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class AcademicMhsController extends Controller
{
    public function index()
    {
        try {
            $userId = Auth::id();
            $academics = Academic::where('user_id', $userId)->latest()->paginate(10);

            return response()->json($academics);
        } catch (\Exception $e) {
            Log::error("Error mengambil daftar data akademik: " . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat mengambil data akademik.'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nama_studi' => 'required',
                'prodi' => 'required',
                'ipk' => 'required',
                'tahun_masuk' => 'required',
                'tahun_lulus' => 'required',
                'kota' => 'required',
                'negara' => 'required',
                'catatan' => 'required'
            ]);

            $academic = Academic::create(array_merge($validatedData, [
                'user_id' => Auth::id(),
            ]));

            return response()->json(['success' => 'Data Berhasil Disimpan!', 'data' => $academic], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning("Validasi gagal saat menyimpan data akademik: " . json_encode($e->errors()));
            return response()->json(['error' => 'Validasi data gagal.', 'detail' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error("Error menyimpan data akademik: " . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat menyimpan data akademik.'], 500);
        }
    }

    public function show(string $id)
    {
        try {
            $academic = Academic::findOrFail($id);

            if ($academic->user_id !== Auth::id()) {
                return response()->json(['error' => 'Aksi tidak diizinkan.'], 403);
            }

            return response()->json($academic);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning("Data akademik dengan ID {$id} tidak ditemukan.");
            return response()->json(['error' => 'Data akademik tidak ditemukan.'], 404);
        } catch (\Exception $e) {
            Log::error("Error mengambil data akademik: " . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat mengambil data akademik.'], 500);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $validatedData = $request->validate([
                'nama_studi' => 'required',
                'prodi' => 'required',
                'ipk' => 'required',
                'tahun_masuk' => 'required',
                'tahun_lulus' => 'required',
                'kota' => 'required',
                'negara' => 'required',
                'catatan' => 'required'
            ]);

            $academic = Academic::findOrFail($id);

            if ($academic->user_id !== Auth::id()) {
                return response()->json(['error' => 'Aksi tidak diizinkan.'], 403);
            }

            $academic->update($validatedData);

            return response()->json(['success' => 'Data Berhasil Diubah!', 'data' => $academic]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning("Validasi gagal saat memperbarui data akademik: " . json_encode($e->errors()));
            return response()->json(['error' => 'Validasi data gagal.', 'detail' => $e->errors()], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning("Data akademik dengan ID {$id} tidak ditemukan.");
            return response()->json(['error' => 'Data akademik tidak ditemukan.'], 404);
        } catch (\Exception $e) {
            Log::error("Error memperbarui data akademik: " . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat memperbarui data akademik.'], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $academic = Academic::findOrFail($id);

            if ($academic->user_id !== Auth::id()) {
                return response()->json(['error' => 'Aksi tidak diizinkan.'], 403);
            }

            $academic->delete();

            return response()->json(['success' => 'Data Berhasil Dihapus!']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning("Data akademik dengan ID {$id} tidak ditemukan.");
            return response()->json(['error' => 'Data akademik tidak ditemukan.'], 404);
        } catch (\Exception $e) {
            Log::error("Error menghapus data akademik: " . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat menghapus data akademik.'], 500);
        }
    }
}
