<?php

namespace App\Http\Controllers\API\Mitra;

use App\Http\Controllers\Controller;
use App\Models\SurveiMitra;
use App\Models\Alumni;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SurveiMitraController extends Controller
{
    public function index()
    {
        try {
            $userId = Auth::id();
            $survei = SurveiMitra::where('user_id', $userId)->get();

            // Menambahkan nomor urut secara otomatis
            $survei->each(function ($item, $index) {
                $item->nomor_urut = $index + 1;  // Nomor urut mulai dari 1
            });

            // Paginasi hasil
            $perPage = 10;
            $currentPage = request()->get('page', 1);
            $currentItems = $survei->slice(($currentPage - 1) * $perPage, $perPage)->values();

            Log::info("Berhasil mengambil data survei mitra.", ['total_data' => $survei->count()]);

            return response()->json([
                'data' => $currentItems,
                'current_page' => $currentPage,
                'total_pages' => ceil($survei->count() / $perPage),
            ]);

        } catch (\Exception $e) {
            Log::error("Error mengambil daftar data survei mitra: " . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat mengambil data survei mitra.'], 500);
        }
    }

    public function search(Request $request)
    {
        // Mengambil nama instansi yang terkait dengan pengguna yang sedang login
        $userName = Auth::user()->name; // Nama dari pengguna yang sedang login
        
        // Cari di tabel 'jobs' untuk menemukan nama instansi yang cocok dengan nama pengguna
        $job = Job::where('nama_job', $userName)->first();
        
        // Jika instansi ditemukan, cari alumni yang bekerja di instansi tersebut
        if ($job) {
            // Ambil alumni yang bekerja di instansi tersebut berdasarkan 'user_id'
            $alumni = Alumni::whereHas('job', function($query) use ($job) {
                $query->where('user_id', $job->user_id);
            })
            ->select('alumni.id', 'alumni.name')
            ->get();
            
            // Kembalikan hasil pencarian sebagai JSON
            return response()->json($alumni);
        } else {
            // Jika tidak ada instansi yang cocok, kirimkan respons kosong atau pesan error
            return response()->json(['message' => 'Instansi tidak ditemukan.'], 200);
            Log::info("Tidak terdapat alumni yang bekerja pada instansi ini.");
        }

        // Log pencarian berhasil
        Log::info("Berhasil melakukan pencarian alumni untuk instansi: {$userName}", ['alumni' => $alumni]);
    }    

    public function store(Request $request)
    {
        try {
            // Validasi input
            $validatedData = $request->validate([
                'name_alumni' => ['required', 'exists:alumni,name'], // Validasi name_alumni harus ada di tabel alumni
                'kedisiplinan' => ['required', 'string'],
                'kejujuran' => ['required', 'string'],
                'motivasi' => ['required', 'string'],
                'etos' => ['required', 'string'],
                'moral' => ['required', 'string'],
                'etika' => ['required', 'string'],
                'bidang_ilmu' => ['required', 'string'],
                'produktif' => ['required', 'string'],
                'masalah' => ['required', 'string'],
                'inisiatif' => ['required', 'string'],
                'menulis_asing' => ['required', 'string'],
                'komunikasi_asing' => ['required', 'string'],
                'memahami_asing' => ['required', 'string'],
                'alat_teknologi' => ['required', 'string'],
                'adaptasi_teknologi' => ['required', 'string'],
                'penggunaan_teknologi' => ['required', 'string'],
                'emosi' => ['required', 'string'],
                'percaya_diri' => ['required', 'string'],
                'keterbukaan' => ['required', 'string'],
                'kom_lisan' => ['required', 'string'],
                'kom_tulisan' => ['required', 'string'],
                'kepemimpinan' => ['required', 'string'],
                'manajerial' => ['required', 'string'],
                'masalah_kerja' => ['required', 'string'],
                'motivasi_tempat_kerja' => ['required', 'string'],
                'motivasi_diri' => ['required', 'string'],
            ]);
    
            // Merge data with user_id and name
            $data = array_merge($validatedData, [
                'user_id' => Auth::id(),
                'name' => Auth::user()->name,
            ]);
    
            // Cek duplikasi berdasarkan user_id dan name_alumni
            $exists = SurveiMitra::where('user_id', $data['user_id'])
                ->where('name_alumni', $data['name_alumni'])
                ->exists();
    
            if ($exists) {
                Log::warning('Data survei duplikat ditemukan', [
                    'user_id' => $data['user_id'],
                    'name_alumni' => $data['name_alumni'],
                ]);
    
                return response()->json([
                    'error' => 'Data survei dengan name_alumni ini sudah ada untuk pengguna saat ini.',
                ], 409); // 409 Conflict
            }
    
            // Simpan data survei
            $survei = SurveiMitra::create($data);
    
            // Log keberhasilan
            Log::info('Data survei berhasil disimpan', ['survei_id' => $survei->id]);
    
            return response()->json([
                'success' => 'Data Berhasil Disimpan!',
                'data' => $survei,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Tangani validasi gagal
            Log::warning('Validasi input gagal', ['errors' => $e->errors()]);
    
            return response()->json([
                'error' => 'Validasi Gagal.',
                'details' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Tangani kesalahan lain
            Log::error('Terjadi kesalahan saat menyimpan data survei', ['error' => $e->getMessage()]);
    
            return response()->json(['error' => 'Terjadi kesalahan saat menyimpan data survei'], 500);
        }
    }
    
    public function update(Request $request, string $id)
    {
        try {
            // Validasi input
            $validatedData = $request->validate([
                'name_alumni' => ['required', 'exists:alumni,name'], // Pastikan tabel `alumnis` dan kolom `name` sesuai
                'kedisiplinan' => ['required', 'string'],
                'kejujuran' => ['required', 'string'],
                'motivasi' => ['required', 'string'],
                'etos' => ['required', 'string'],
                'moral' => ['required', 'string'],
                'etika' => ['required', 'string'],
                'bidang_ilmu' => ['required', 'string'],
                'produktif' => ['required', 'string'],
                'masalah' => ['required', 'string'],
                'inisiatif' => ['required', 'string'],
                'menulis_asing' => ['required', 'string'],
                'komunikasi_asing' => ['required', 'string'],
                'memahami_asing' => ['required', 'string'],
                'alat_teknologi' => ['required', 'string'],
                'adaptasi_teknologi' => ['required', 'string'],
                'penggunaan_teknologi' => ['required', 'string'],
                'emosi' => ['required', 'string'],
                'percaya_diri' => ['required', 'string'],
                'keterbukaan' => ['required', 'string'],
                'kom_lisan' => ['required', 'string'],
                'kom_tulisan' => ['required', 'string'],
                'kepemimpinan' => ['required', 'string'],
                'manajerial' => ['required', 'string'],
                'masalah_kerja' => ['required', 'string'],
                'motivasi_tempat_kerja' => ['required', 'string'],
                'motivasi_diri' => ['required', 'string'],
            ]);
    
            // Cari data survei berdasarkan ID
            $survei = SurveiMitra::findOrFail($id);
    
            // Cek apakah pengguna memiliki akses untuk memperbarui data ini
            if ($survei->user_id !== Auth::id()) {
                Log::warning('Akses tidak sah untuk mengubah data survei', ['survei_id' => $id]);
                return response()->json(['error' => 'Akses tidak sah.'], 403);
            }
    
            // Cek duplikasi berdasarkan user_id dan name_alumni, kecuali data saat ini
            $exists = SurveiMitra::where('user_id', Auth::id())
                ->where('name_alumni', $validatedData['name_alumni'])
                ->where('id', '!=', $id) // Kecualikan data yang sedang diperbarui
                ->exists();
    
            if ($exists) {
                Log::warning('Data survei duplikat ditemukan saat update', [
                    'user_id' => Auth::id(),
                    'name_alumni' => $validatedData['name_alumni'],
                ]);
    
                return response()->json([
                    'error' => 'Data survei dengan name_alumni ini sudah ada untuk pengguna saat ini.',
                ], 409); // 409 Conflict
            }
    
            // Perbarui data survei
            $survei->update($validatedData);
    
            // Log keberhasilan
            Log::info('Data survei berhasil diperbarui', [
                'survei_id' => $id,
                'user_id' => Auth::id(),
            ]);
    
            return response()->json([
                'success' => 'Data Berhasil Diubah!',
                'data' => $survei,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Tangani validasi gagal
            Log::warning('Validasi input gagal', ['errors' => $e->errors()]);
    
            return response()->json([
                'error' => 'Validasi Gagal.',
                'details' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Tangani kesalahan lain
            Log::error('Terjadi kesalahan saat memperbarui data survei', [
                'error' => $e->getMessage(),
            ]);
    
            return response()->json([
                'error' => 'Terjadi kesalahan saat memperbarui data survei.',
            ], 500);
        }
    }   

    public function show(string $id)
    {
        try {
            $survei = SurveiMitra::findOrFail($id);

            if ($survei->user_id !== Auth::id()) {
                Log::warning('Akses tidak sah untuk melihat data survei mitra', ['survei_id' => $id]);
                return response()->json(['error' => 'Akses tidak sah.'], 403);
            }

            Log::info('Data survei mitra berhasil diambil', ['survei_id' => $id]);
            return response()->json($survei);
        } catch (\Exception $e) {
            Log::error('Terjadi kesalahan saat mengambil data survei mitra', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Terjadi kesalahan saat mengambil data survei mitra'], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $survei = SurveiMitra::findOrFail($id);

            if ($survei->user_id !== Auth::id()) {
                Log::warning('Akses tidak sah untuk menghapus data survei mitra', ['survei_id' => $id]);
                return response()->json(['error' => 'Akses tidak sah.'], 403);
            }

            $survei->delete();

            Log::info('Data survei mitra berhasil dihapus', ['survei_id' => $id]);
            return response()->json(['success' => 'Data Berhasil Dihapus!']);
        } catch (\Exception $e) {
            Log::error('Terjadi kesalahan saat menghapus data survei mitra', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Terjadi kesalahan saat menghapus data survei mitra'], 500);
        }
    }
}
