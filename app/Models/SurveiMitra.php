<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveiMitra extends Model
{
    use HasFactory;
    
    protected $table = 'survei'; 

    protected $fillable = [
        'user_id',
        'name',
        'name_alumni',
        'kedisiplinan', 'kejujuran', 'motivasi', 'etos', 'moral', 'etika',
        'bidang_ilmu', 'produktif', 'masalah', 'inisiatif', 
        'menulis_asing','komunikasi_asing', 'memahami_asing', 
        'alat_teknologi', 'adaptasi_teknologi', 'penggunaan_teknologi', 
        'emosi', 'percaya_diri', 'keterbukaan','kom_lisan', 'kom_tulisan', 
        'kepemimpinan', 'manajerial','masalah_kerja', 
        'motivasi_tempat_kerja', 'motivasi_diri',
    ];

    // Relasi ke model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function alumni()
    {
        return $this->belongsTo(Alumni::class);
    }
}
