<?php

namespace App\Http\Controllers\API\Alumni\CVAlumni;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class CourseAlumniController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $userId = Auth::id();
            $courses = Course::where('user_id', $userId)->latest()->paginate(10);

            Log::info('Data kursus berhasil diambil', ['user_id' => $userId]);
            return response()->json($courses);
        } catch (\Exception $e) {
            Log::error('Terjadi kesalahan saat mengambil data kursus', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Terjadi kesalahan saat mengambil data kursus.'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nama_course' => 'required',
                'institusi_course' => 'required',
                'tingkat_course' => 'required',
                'tahun_course' => 'required'
            ]);

            $course = Course::create(array_merge($validatedData, [
                'user_id' => Auth::id(),
            ]));

            Log::info('Data kursus berhasil disimpan', ['course_id' => $course->id]);
            return response()->json(['success' => 'Data Berhasil Disimpan!', 'data' => $course], 201);
        } catch (\Exception $e) {
            Log::error('Terjadi kesalahan saat menyimpan data kursus', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Terjadi kesalahan saat menyimpan data kursus.'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $course = Course::findOrFail($id);

            if ($course->user_id !== Auth::id()) {
                Log::warning('Akses tidak sah untuk melihat data kursus', ['course_id' => $id]);
                return response()->json(['error' => 'Akses tidak sah.'], 403);
            }

            Log::info('Data kursus berhasil diambil', ['course_id' => $id]);
            return response()->json($course);
        } catch (\Exception $e) {
            Log::error('Terjadi kesalahan saat mengambil data kursus', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Terjadi kesalahan saat mengambil data kursus.'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validatedData = $request->validate([
                'nama_course' => 'required',
                'institusi_course' => 'required',
                'tingkat_course' => 'required',
                'tahun_course' => 'required'
            ]);

            $course = Course::findOrFail($id);

            if ($course->user_id !== Auth::id()) {
                Log::warning('Akses tidak sah untuk mengubah data kursus', ['course_id' => $id]);
                return response()->json(['error' => 'Akses tidak sah.'], 403);
            }

            $course->update($validatedData);

            Log::info('Data kursus berhasil diperbarui', ['course_id' => $id]);
            return response()->json(['success' => 'Data Berhasil Diubah!', 'data' => $course]);
        } catch (\Exception $e) {
            Log::error('Terjadi kesalahan saat memperbarui data kursus', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Terjadi kesalahan saat memperbarui data kursus.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $course = Course::findOrFail($id);

            if ($course->user_id !== Auth::id()) {
                Log::warning('Akses tidak sah untuk menghapus data kursus', ['course_id' => $id]);
                return response()->json(['error' => 'Akses tidak sah.'], 403);
            }

            $course->delete();

            Log::info('Data kursus berhasil dihapus', ['course_id' => $id]);
            return response()->json(['success' => 'Data Berhasil Dihapus!']);
        } catch (\Exception $e) {
            Log::error('Terjadi kesalahan saat menghapus data kursus', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Terjadi kesalahan saat menghapus data kursus.'], 500);
        }
    }
}
