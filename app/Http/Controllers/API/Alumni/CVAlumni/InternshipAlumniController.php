<?php

namespace App\Http\Controllers\API\Alumni\CVAlumni;

use App\Http\Controllers\Controller;
use App\Models\Internship;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class InternshipAlumniController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $userId = Auth::id();
            $internships = Internship::where('user_id', $userId)->latest()->paginate(10);

            Log::info("Berhasil mengambil data magang untuk user_id: {$userId}");

            return response()->json($internships);
        } catch (\Exception $e) {
            Log::error("Gagal mengambil data magang untuk user_id: " . Auth::id() . ". Kesalahan: " . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat mengambil data magang.'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
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

            Log::info("Berhasil menyimpan data magang untuk user_id: " . Auth::id(), ['data' => $internship]);

            return response()->json(['success' => 'Data Berhasil Disimpan!', 'data' => $internship], 201);
        } catch (\Exception $e) {
            Log::error("Gagal menyimpan data magang untuk user_id: " . Auth::id() . ". Kesalahan: " . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat menyimpan data magang.'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $internship = Internship::findOrFail($id);

            if ($internship->user_id !== Auth::id()) {
                Log::warning("Akses tidak sah ke data magang dengan ID: {$id} oleh user_id: " . Auth::id());
                return response()->json(['error' => 'Tindakan tidak sah.'], 403);
            }

            Log::info("Berhasil mengambil data magang dengan ID: {$id} untuk user_id: " . Auth::id());
            return response()->json($internship);
        } catch (\Exception $e) {
            Log::error("Gagal mengambil data magang dengan ID: {$id}. Kesalahan: " . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat mengambil data magang.'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
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
                Log::warning("Akses tidak sah untuk mengupdate data magang dengan ID: {$id} oleh user_id: " . Auth::id());
                return response()->json(['error' => 'Tindakan tidak sah.'], 403);
            }

            $internship->update($validatedData);

            Log::info("Berhasil mengupdate data magang dengan ID: {$id} untuk user_id: " . Auth::id(), ['data' => $internship]);

            return response()->json(['success' => 'Data Berhasil Diubah!', 'data' => $internship]);
        } catch (\Exception $e) {
            Log::error("Gagal mengupdate data magang dengan ID: {$id}. Kesalahan: " . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat mengupdate data magang.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $internship = Internship::findOrFail($id);

            if ($internship->user_id !== Auth::id()) {
                Log::warning("Akses tidak sah untuk menghapus data magang dengan ID: {$id} oleh user_id: " . Auth::id());
                return response()->json(['error' => 'Tindakan tidak sah.'], 403);
            }

            $internship->delete();

            Log::info("Berhasil menghapus data magang dengan ID: {$id} untuk user_id: " . Auth::id());

            return response()->json(['success' => 'Data Berhasil Dihapus!']);
        } catch (\Exception $e) {
            Log::error("Gagal menghapus data magang dengan ID: {$id}. Kesalahan: " . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat menghapus data magang.'], 500);
        }
    }
}
