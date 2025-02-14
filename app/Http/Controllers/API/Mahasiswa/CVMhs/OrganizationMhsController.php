<?php

namespace App\Http\Controllers\API\Mahasiswa\CVMhs;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class OrganizationMhsController extends Controller
{
    public function index()
    {
        try {
            $userId = Auth::id();
            $organizations = Organization::where('user_id', $userId)->latest()->paginate(10);

            return response()->json($organizations);
        } catch (\Exception $e) {
            Log::error("Error mengambil daftar organisasi: " . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat mengambil data organisasi.'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nama_org' => 'required',
                'bulan_masuk_org' => 'required',
                'periode_masuk_org' => 'required',
                'bulan_keluar_org' => 'required',
                'periode_keluar_org' => 'required',
                'jabatan_org' => 'required',
                'kota' => 'required',
                'negara' => 'required',
                'catatan' => 'required'
            ]);

            $organization = Organization::create(array_merge($validatedData, [
                'user_id' => Auth::id(),
            ]));

            return response()->json(['success' => 'Data Berhasil Disimpan!', 'data' => $organization], 201);
        } catch (\Exception $e) {
            Log::error("Error menyimpan data organisasi: " . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat menyimpan data.'], 500);
        }
    }

    public function show(string $id)
    {
        try {
            $organization = Organization::findOrFail($id);

            if ($organization->user_id !== Auth::id()) {
                return response()->json(['error' => 'Aksi tidak diizinkan.'], 403);
            }

            return response()->json($organization);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning("Organisasi dengan ID {$id} tidak ditemukan.");
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        } catch (\Exception $e) {
            Log::error("Error saat mengambil data organisasi: " . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat mengambil data.'], 500);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $validatedData = $request->validate([
                'nama_org' => 'required',
                'bulan_masuk_org' => 'required',
                'periode_masuk_org' => 'required',
                'bulan_keluar_org' => 'required',
                'periode_keluar_org' => 'required',
                'jabatan_org' => 'required',
                'kota' => 'required',
                'negara' => 'required',
                'catatan' => 'required'
            ]);

            $organization = Organization::findOrFail($id);

            if ($organization->user_id !== Auth::id()) {
                return response()->json(['error' => 'Aksi tidak diizinkan.'], 403);
            }

            $organization->update($validatedData);

            return response()->json(['success' => 'Data Berhasil Diubah!', 'data' => $organization]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning("Validasi gagal saat memperbarui data organisasi: " . json_encode($e->errors()));
            return response()->json(['error' => 'Validasi data gagal.', 'detail' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error("Error memperbarui data organisasi: " . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat memperbarui data.'], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $organization = Organization::findOrFail($id);

            if ($organization->user_id !== Auth::id()) {
                return response()->json(['error' => 'Aksi tidak diizinkan.'], 403);
            }

            $organization->delete();

            return response()->json(['success' => 'Data Berhasil Dihapus!']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning("Organisasi dengan ID {$id} tidak ditemukan.");
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        } catch (\Exception $e) {
            Log::error("Error menghapus data organisasi: " . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat menghapus data.'], 500);
        }
    }
}
