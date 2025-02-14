<?php

namespace App\Http\Controllers\API\Admin;

use App\Models\Job;
use App\Models\Award;
use App\Models\Skill;
use App\Models\Alumni;
use App\Models\Interest;
use App\Models\Course;
use App\Models\Academic;
use App\Models\Internship;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AlumniAdminController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $ipk_min = $request->input('ipk_min');
        $ipk_max = $request->input('ipk_max');
      
        // Cek role pengguna
        $role = auth()->user()->getRoleNames()->first();
    
        // Sesuaikan query berdasarkan role
        $query = Alumni::select(
                'alumni.id', 
                'alumni.name', 
                'alumni.email', 
                'alumni.user_id', 
                'academics.ipk',
                'alumni.tahun_lulus', 
                'alumni.status', 
                'alumni.no_hp',
                'alumni.foto_profil',
                'awards.nama_award',
                'skills.kerjasama_skill',
                'skills.ahli_skill',
                'skills.inggris_skill',
                'skills.komunikasi_skill',
                'skills.pengembangan_skill',
                'skills.kepemimpinan_skill',
                'skills.etoskerja_skill',
                \DB::raw('GROUP_CONCAT(interests.name) AS interests_name')
            )
            ->leftJoin('academics', 'alumni.user_id', '=', 'academics.user_id')
            ->leftJoin('interest_user', 'alumni.user_id', '=', 'interest_user.user_id')
            ->leftJoin('skills', 'alumni.user_id', '=', 'skills.user_id')
            ->leftJoin('awards', 'alumni.user_id', '=', 'awards.user_id')
            ->leftJoin('interests', 'interest_user.interest_id', '=', 'interests.id')
            ->groupBy(
                'alumni.id',
                'alumni.name',
                'alumni.email',
                'alumni.user_id',
                'academics.ipk',
                'alumni.tahun_lulus',
                'alumni.status',
                'alumni.no_hp',
                'alumni.foto_profil',
                'awards.nama_award',
                'skills.kerjasama_skill',
                'skills.ahli_skill',
                'skills.inggris_skill',
                'skills.komunikasi_skill',
                'skills.pengembangan_skill',
                'skills.kepemimpinan_skill',
                'skills.etoskerja_skill'
            );
           
            
        
        // Menentukan query berdasarkan role
        if ($role == 'admin') {
            // Admin melihat semua alumni
            $alumnis = $query;
        } elseif ($role == 'mitra') {
            // Mitra hanya melihat alumni dengan status "Tidak Bekerja Tetapi Sedang Mencari Pekerjaan"
            $alumnis = $query->where('alumni.status', 'Tidak Bekerja Tetapi Sedang Mencari Pekerjaan');
        } else {
            return response()->json([
                'message' => 'Unauthorized access'
            ], 403);
        }
    
        // Filter tambahan berdasarkan parameter pencarian
        $alumnis = $alumnis
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where('alumni.name', 'like', "%{$search}%")
                        ->orWhere('alumni.email', 'like', "%{$search}%")
                        ->orWhere('alumni.status', 'like', "%{$search}%")
                        ->orWhere('alumni.no_hp', 'like', "%{$search}%")
                        ->orWhere('alumni.tahun_lulus', 'like', "%{$search}%");
                });
            })
            ->when($ipk_min && $ipk_max, function ($query) use ($ipk_min, $ipk_max) {
                $query->whereBetween('academics.ipk', [$ipk_min, $ipk_max]);
            })
            ->when($ipk_min && !$ipk_max, function ($query) use ($ipk_min) {
                $query->where('academics.ipk', '>=', $ipk_min);
            })
            ->when($ipk_max && !$ipk_min, function ($query) use ($ipk_max) {
                $query->where('academics.ipk', '<=', $ipk_max);
            })
            ->orderBy('alumni.name', 'asc')
            ->get();
    
        // Split interests_name into an array for each alumni
        $alumnis->transform(function ($alumni) {
            $alumni->interests_name = $alumni->interests_name ? explode(',', $alumni->interests_name) : [];
            return $alumni;
        });
    
        return response()->json([
            'success' => true,
            'message' => 'Data alumni berhasil diambil',
            'data' => $alumnis,
        ]);
    }
    
    

    public function showCV($id_alumni)
    {
        // Mengambil data yang sesuai dengan id_alumni yang dipilih
        $academics = Academic::where('user_id', $id_alumni)->orderBy('created_at', 'desc')->take(5)->get();
        $jobs = Job::where('user_id', $id_alumni)->orderBy('created_at', 'desc')->take(5)->get();
        $internships = Internship::where('user_id', $id_alumni)->orderBy('created_at', 'desc')->take(5)->get();
        $organizations = Organization::where('user_id', $id_alumni)->orderBy('created_at', 'desc')->take(5)->get();
        $awards = Award::where('user_id', $id_alumni)->orderBy('created_at', 'desc')->take(5)->get();
        $courses = Course::where('user_id', $id_alumni)->orderBy('created_at', 'desc')->take(5)->get();
        $skills = Skill::where('user_id', $id_alumni)->orderBy('created_at', 'desc')->take(5)->get();

        return response()->json([
            'academics' => $academics,
            'jobs' => $jobs,
            'internships' => $internships,
            'organizations' => $organizations,
            'awards' => $awards,
            'courses' => $courses,
            'skills' => $skills,
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('name');

        if (!$query) {
            return response()->json([
                'message' => 'name parameter is required.'
            ], 400);
        }

        // Melakukan pencarian berdasarkan nama
        $results = Alumni::where('name', 'LIKE', '%' . $query . '%')
            ->get();

        return response()->json($results);
    }
}
