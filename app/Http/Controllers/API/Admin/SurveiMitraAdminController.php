<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\SurveiMitra;
use Illuminate\Support\Facades\Log;

class SurveiMitraAdminController extends Controller
{
    public function index()
    {
        try {
            // Ambil seluruh data dari tabel survei_mitra tanpa batasan user_id
            $survei = SurveiMitra::orderBy('name') // Urutkan berdasarkan 'name'
                ->get();
    
            // Kelompokkan berdasarkan 'name' yang sama
            $groupedSurvei = $survei->groupBy('name');
    
            // Menambahkan nomor urut di dalam masing-masing grup 'name'
            $groupedSurvei = $groupedSurvei->map(function ($items, $name) {
                return $items->map(function ($item, $index) {
                    $item->nomor_urut = $index + 1;  // Nomor urut mulai dari 1
                    return $item;
                });
            });
    
            // Menggabungkan semua data yang sudah dikelompokkan dan diberi nomor urut
            $allSurvei = $groupedSurvei->flatten();
    
            // Paginasi hasil
            $perPage = 10;
            $currentPage = request()->get('page', 1);
            $currentItems = $allSurvei->slice(($currentPage - 1) * $perPage, $perPage)->values();
    
            Log::info("Berhasil mengambil data survei mitra.", ['total_data' => $allSurvei->count()]);
    
            return response()->json([
                'data' => $currentItems,
                'current_page' => $currentPage,
                'total_pages' => ceil($allSurvei->count() / $perPage),
            ]);
        } catch (\Exception $e) {
            Log::error("Error mengambil daftar data survei mitra: " . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat mengambil data survei mitra.'], 500);
        }
    }
    
}
