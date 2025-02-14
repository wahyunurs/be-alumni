<?php

namespace App\Http\Controllers\API\Alumni\CVAlumni;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class SkillAlumniController extends Controller
{
    public function index()
    {
        try {
            $userId = Auth::id();
            $skills = Skill::where('user_id', $userId)->latest()->paginate(10);

            return response()->json($skills);
        } catch (\Exception $e) {
            Log::error("Error mengambil daftar skill: " . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat mengambil data skill.'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'kerjasama_skill' => 'required',
                'ahli_skill' => 'required',
                'inggris_skill' => 'required',
                'komunikasi_skill' => 'required',
                'pengembangan_skill' => 'required',
                'kepemimpinan_skill' => 'required',
                'etoskerja_skill' => 'required'
            ]);

            $skill = Skill::create(array_merge($validatedData, [
                'user_id' => Auth::id(),
            ]));

            return response()->json(['success' => 'Data Berhasil Disimpan!', 'data' => $skill], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning("Validasi gagal saat menyimpan data skill: " . json_encode($e->errors()));
            return response()->json(['error' => 'Validasi data gagal.', 'detail' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error("Error menyimpan data skill: " . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat menyimpan data.'], 500);
        }
    }

    public function show(string $id)
    {
        try {
            $skill = Skill::findOrFail($id);

            if ($skill->user_id !== Auth::id()) {
                return response()->json(['error' => 'Aksi tidak diizinkan.'], 403);
            }

            return response()->json($skill);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning("Skill dengan ID {$id} tidak ditemukan.");
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        } catch (\Exception $e) {
            Log::error("Error mengambil data skill: " . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat mengambil data.'], 500);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $validatedData = $request->validate([
                'kerjasama_skill' => 'required',
                'ahli_skill' => 'required',
                'inggris_skill' => 'required',
                'komunikasi_skill' => 'required',
                'pengembangan_skill' => 'required',
                'kepemimpinan_skill' => 'required',
                'etoskerja_skill' => 'required'
            ]);

            $skill = Skill::findOrFail($id);

            if ($skill->user_id !== Auth::id()) {
                return response()->json(['error' => 'Aksi tidak diizinkan.'], 403);
            }

            $skill->update($validatedData);

            return response()->json(['success' => 'Data Berhasil Diubah!', 'data' => $skill]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning("Validasi gagal saat memperbarui data skill: " . json_encode($e->errors()));
            return response()->json(['error' => 'Validasi data gagal.', 'detail' => $e->errors()], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning("Skill dengan ID {$id} tidak ditemukan.");
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        } catch (\Exception $e) {
            Log::error("Error memperbarui data skill: " . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat memperbarui data.'], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $skill = Skill::findOrFail($id);

            if ($skill->user_id !== Auth::id()) {
                return response()->json(['error' => 'Aksi tidak diizinkan.'], 403);
            }

            $skill->delete();

            return response()->json(['success' => 'Data Berhasil Dihapus!']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning("Skill dengan ID {$id} tidak ditemukan.");
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        } catch (\Exception $e) {
            Log::error("Error menghapus data skill: " . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat menghapus data.'], 500);
        }
    }
}
