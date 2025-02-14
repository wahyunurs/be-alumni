<?php

namespace App\Models;

use App\Http\Controllers\API\Admin\StatistikAdminController;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Api\StatistikController;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Alumni extends Model
{
    use HasFactory;

    protected $table = 'alumni';

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'foto_profil',
        'jns_kelamin',
        'nim',
        'tahun_masuk',
        'tahun_lulus',
        'bulan_lulus',
        'wisuda',
        'no_hp',
        'status',
        'masa_tunggu',
        'bidang_job',
        'jns_job',
        'nama_job',
        'jabatan_job',
        'lingkup_job',
        'bulan_masuk_job',
        'biaya_studi',
        'jenjang_pendidikan',
        'universitas',
        'program_studi',
        'mulai_studi',

    ];

    // Relasi ke model User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function SurveiMitra()
    {
        return $this->hasMany(SurveiMitra::class);
    }

    protected static function booted()
    {
        static::saved(function ($alumni) {
            // Panggil fungsi updateAlumniCount setiap kali data alumni diubah
            app(StatistikAdminController::class)->updateAlumniCount($alumni->tahun_lulus);
        });

        static::deleted(function ($alumni) {
            // Panggil fungsi updateAlumniCount untuk menghapus alumni dari statistik
            app(StatistikAdminController::class)->updateAlumniCount($alumni->tahun_lulus);
        });
    }
}
