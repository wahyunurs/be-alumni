<?php

namespace App\Http\Controllers\API\Mitra;

use App\Http\Controllers\Controller;
use App\Models\SurveiMitra;
use Auth;
use Illuminate\Http\Request;
use Log;

class DashboardMitraController extends Controller
{
    public function getStatistics()
    {
        try {
            $userId = Auth::id();

            // Hitung total survei yang ada
            $totalSurveys = SurveiMitra::count();

            // Hitung jumlah alumni yang unik terkait dengan survei
            $totalAlumniInSurveys = SurveiMitra::distinct('name_alumni')->count();

            // Hitung distribusi nilai kepuasan (1-5)
            $kepuasanDistribution = SurveiMitra::select('kepuasan', \DB::raw('count(*) as count'))
                ->groupBy('kepuasan')
                ->orderBy('kepuasan')
                ->get()
                ->map(function($item) {
                    return [
                        'rating' => $item->kepuasan,
                        'count' => $item->count
                    ];
                });

            // Hitung distribusi nilai kesesuaian (1-5)
            $kesesuaianDistribution = SurveiMitra::select('kesesuaian', \DB::raw('count(*) as count'))
                ->groupBy('kesesuaian')
                ->orderBy('kesesuaian')
                ->get()
                ->map(function($item) {
                    return [
                        'rating' => $item->kesesuaian,
                        'count' => $item->count
                    ];
                });

            // Gabungkan semua data dalam format yang diminta
            return response()->json([
                'total_surveys' => $totalSurveys,
                'total_alumni_in_surveys' => $totalAlumniInSurveys,
                'kepuasan_distribution' => $kepuasanDistribution,
                'kesesuaian_distribution' => $kesesuaianDistribution,
            ]);
        } catch (\Exception $e) {
            Log::error('Terjadi kesalahan saat mengambil statistik survei', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Terjadi kesalahan saat mengambil statistik survei'], 500);
        }
    }
}
