<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DataMitraPenggunaAlumniController extends Controller
{
    public function getAllMitra()
    {
        $data = Alumni::select(
            'alumni.id', 
            'alumni.nama_job',
            'alumni.jns_job',
            'alumni.lingkup_job',
            'jobs.kota'
        )
        ->leftJoin('jobs', 'alumni.user_id', '=', 'jobs.user_id')
        ->whereColumn('alumni.nama_job', '=', 'jobs.nama_job') // Filter berdasarkan nama_job yang sesuai
        ->get();


        return response()->json($data); // Mengembalikan data dalam format JSON
    }
    


    public function searchMitra(Request $request)
    {
        $keyword = $request->input('keyword');

        $query = Alumni::join('jobs', 'alumni.id', '=', 'jobs.id')
            ->where(function ($query) use ($keyword) {
                $query->where('alumni.nama_job', 'LIKE', "%$keyword%")
                    ->orWhere('alumni.jns_job', 'LIKE', "%$keyword%")
                    ->orWhere('alumni.lingkup_job', 'LIKE', "%$keyword%")
                    ->orWhere('jobs.kota', 'LIKE', "%$keyword%");
            })
            ->select(
                'alumni.id',
                'alumni.nama_job',
                'alumni.jns_job',
                'alumni.lingkup_job',
                'jobs.kota',
            );

        $data = $query->get();
        return response()->json($data);
    }

    public function getDetailMitra($id)
    {
        $data = Alumni::join('jobs', 'alumni.user_id', '=', 'jobs.user_id')
            ->where('alumni.id', $id)
            ->select(
                'alumni.id',
                'alumni.nama_job',
                'alumni.jns_job',
                'alumni.lingkup_job',
                'jobs.kota',
                'jobs.alamat'
            )
            ->first(); // Hanya mengambil satu hasil

        if ($data) {
            return response()->json($data); // Mengembalikan data detail
        } else {
            return response()->json(['message' => 'Data not found'], 404); // Jika data tidak ditemukan
        }
    }

}
