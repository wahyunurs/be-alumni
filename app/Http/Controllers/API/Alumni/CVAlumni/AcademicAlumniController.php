<?php

namespace App\Http\Controllers\API\Alumni\CVAlumni;

use App\Http\Controllers\Controller;
use App\Models\Academic;
use App\Models\Alumni;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class AcademicAlumniController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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
                'jenjang_pendidikan' => 'required',
                'nama_studi' => 'required',
                'prodi' => 'required',
                'ipk' => 'required',
                'tahun_masuk' => 'required',
                'tahun_lulus' => 'required',
                'kota' => 'required',
                'negara' => 'required',
                'catatan' => 'required'
            ]);

            $academic = Academic::create(array_merge($validatedData, ['user_id' => Auth::id()]));

            Log::info('Data akademik berhasil disimpan', ['academic_id' => $academic->id]);
            return response()->json(['success' => 'Data Berhasil Disimpan!', 'data' => $academic], 201);
        } catch (\Exception $e) {
            Log::error('Terjadi kesalahan saat menyimpan data akademik', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Terjadi kesalahan saat menyimpan data akademik'], 500);
        }
    }

    public function show(string $id)
    {
        try {
            $academic = Academic::findOrFail($id);

            if ($academic->user_id !== Auth::id()) {
                Log::warning('Akses tidak sah untuk melihat data akademik', ['academic_id' => $id]);
                return response()->json(['error' => 'Akses tidak sah.'], 403);
            }

            Log::info('Data akademik berhasil diambil', ['academic_id' => $id]);
            return response()->json($academic);
        } catch (\Exception $e) {
            Log::error('Terjadi kesalahan saat mengambil data akademik', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Terjadi kesalahan saat mengambil data akademik'], 500);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $validatedData = $request->validate([
                'jenjang_pendidikan' => 'required',
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
                Log::warning('Akses tidak sah untuk mengubah data akademik', ['academic_id' => $id]);
                return response()->json(['error' => 'Akses tidak sah.'], 403);
            }

            $academic->update($validatedData);

            Log::info('Data akademik berhasil diperbarui', ['academic_id' => $id]);
            return response()->json(['success' => 'Data Berhasil Diubah!', 'data' => $academic]);
        } catch (\Exception $e) {
            Log::error('Terjadi kesalahan saat memperbarui data akademik', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Terjadi kesalahan saat memperbarui data akademik'], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $academic = Academic::findOrFail($id);

            if ($academic->user_id !== Auth::id()) {
                Log::warning('Akses tidak sah untuk menghapus data akademik', ['academic_id' => $id]);
                return response()->json(['error' => 'Akses tidak sah.'], 403);
            }

            $academic->delete();

            Log::info('Data akademik berhasil dihapus', ['academic_id' => $id]);
            return response()->json(['success' => 'Data Berhasil Dihapus!']);
        } catch (\Exception $e) {
            Log::error('Terjadi kesalahan saat menghapus data akademik', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Terjadi kesalahan saat menghapus data akademik'], 500);
        }
    }
}
