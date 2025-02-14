<?php

namespace App\Http\Controllers\API\Alumni;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use Illuminate\Http\Request;

class AlumniController extends Controller
{
    // Mendapatkan semua alumni
    public function index()
    {
        $alumni = Alumni::all();
        return response()->json($alumni);
    }

    // Mendapatkan detail alumni berdasarkan ID
    public function show($id)
    {
        $alumni = Alumni::find($id);

        if (!$alumni) {
            return response()->json(['message' => 'Alumni not found'], 404);
        }

        return response()->json($alumni);
    }
    public function destroy($id)
    {
        $alumni = Alumni::find($id);

        if (!$alumni) {
            return response()->json(['message' => 'Alumni not found'], 404);
        }

        // Hapus alumni
        $alumni->delete();

        return response()->json(['message' => 'Alumni deleted successfully'], 204);
    }
}
