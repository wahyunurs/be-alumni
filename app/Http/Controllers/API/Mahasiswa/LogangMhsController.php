<?php

namespace App\Http\Controllers\API\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Logang;
use Illuminate\Http\Request;

class LogangMhsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil parameter filter dari request
        $selectedTipeMagang = $request->input('TipeMagang');
        $selectedPengalaman = $request->input('Pengalaman');

        // Query dasar dengan filter Verify
        $query = Logang::where('Verify', 'verified');

        // Tambahkan filter berdasarkan parameter yang dipilih
        if ($selectedTipeMagang) {
            $query->where('TipeMagang', 'LIKE', '%' . $selectedTipeMagang . '%');
        }
        if ($selectedPengalaman) {
            $query->where('Pengalaman', 'LIKE', '%' . $selectedPengalaman . '%');
        }

        // Ambil data berdasarkan filter dan sorting terbaru
        $logangs = $query->orderBy('updated_at', 'desc')->get();

        // Cek apakah data tersedia
        if ($logangs->isEmpty()) {
            return response()->json(['message' => 'Logang not found'], 404);
        }

        // Return data dalam format JSON
        return response()->json([
            'success' => true,
            'data' => [
                'logangs' => $logangs,
                'filters' => [
                    'selectedTipeMagang' => $selectedTipeMagang ?: null,
                    'selectedPengalaman' => $selectedPengalaman ?: null,
                ]
            ]
        ]);
    }
    
    /**
     * Display the specified resource.
     */    
    public function showLogang($id){
        $logang = Logang::findOrFail($id); 
    
        return response()->json(['status' => 'success','data' => $logang]);
    }
}
