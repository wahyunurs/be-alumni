<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Academic extends Model
{
    use HasFactory;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'jenjang_pendidikan',
        'nama_studi',
        'prodi',
        'ipk',
        'tahun_masuk',
        'tahun_lulus',
        'kota',
        'negara',
        'catatan'
    ];

    // Relasi ke model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
