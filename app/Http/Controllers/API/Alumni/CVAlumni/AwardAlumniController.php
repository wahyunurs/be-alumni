<?php

namespace App\Http\Controllers\API\Alumni\CVAlumni;

use App\Http\Controllers\Controller;
use App\Models\Award;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class AwardAlumniController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $userId = Auth::id();
            $awards = Award::where('user_id', $userId)->latest()->paginate(10);

            Log::info('Data penghargaan berhasil diambil', ['user_id' => $userId]);
            return response()->json($awards);
        } catch (\Exception $e) {
            Log::error('Terjadi kesalahan saat mengambil data penghargaan', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Terjadi kesalahan saat mengambil data penghargaan.'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nama_award' => 'required',
                'institusi_award' => 'required',
                'tingkat_award' => 'required',
                'tahun_award' => 'required',
                'deskripsi_award' => 'required'
            ]);

            $award = Award::create(array_merge($validatedData, ['user_id' => Auth::id()]));

            Log::info('Data penghargaan berhasil disimpan', ['award_id' => $award->id]);
            return response()->json(['success' => 'Data Berhasil Disimpan!', 'award' => $award], 201);
        } catch (\Exception $e) {
            Log::error('Terjadi kesalahan saat menyimpan data penghargaan', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Terjadi kesalahan saat menyimpan data penghargaan.'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $award = Award::findOrFail($id);

            if ($award->user_id !== Auth::id()) {
                Log::warning('Akses tidak sah untuk melihat data penghargaan', ['award_id' => $id]);
                return response()->json(['error' => 'Akses tidak sah.'], 403);
            }

            Log::info('Data penghargaan berhasil diambil', ['award_id' => $id]);
            return response()->json($award);
        } catch (\Exception $e) {
            Log::error('Terjadi kesalahan saat mengambil data penghargaan', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Terjadi kesalahan saat mengambil data penghargaan.'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validatedData = $request->validate([
                'nama_award' => 'required',
                'institusi_award' => 'required',
                'tingkat_award' => 'required',
                'tahun_award' => 'required',
                'deskripsi_award' => 'required'
            ]);

            $award = Award::findOrFail($id);

            if ($award->user_id !== Auth::id()) {
                Log::warning('Akses tidak sah untuk mengubah data penghargaan', ['award_id' => $id]);
                return response()->json(['error' => 'Akses tidak sah.'], 403);
            }

            $award->update($validatedData);

            Log::info('Data penghargaan berhasil diperbarui', ['award_id' => $id]);
            return response()->json(['success' => 'Data Berhasil Diubah!', 'award' => $award]);
        } catch (\Exception $e) {
            Log::error('Terjadi kesalahan saat memperbarui data penghargaan', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Terjadi kesalahan saat memperbarui data penghargaan.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $award = Award::findOrFail($id);

            if ($award->user_id !== Auth::id()) {
                Log::warning('Akses tidak sah untuk menghapus data penghargaan', ['award_id' => $id]);
                return response()->json(['error' => 'Akses tidak sah.'], 403);
            }

            $award->delete();

            Log::info('Data penghargaan berhasil dihapus', ['award_id' => $id]);
            return response()->json(['success' => 'Data Berhasil Dihapus!']);
        } catch (\Exception $e) {
            Log::error('Terjadi kesalahan saat menghapus data penghargaan', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Terjadi kesalahan saat menghapus data penghargaan.'], 500);
        }
    }
}
