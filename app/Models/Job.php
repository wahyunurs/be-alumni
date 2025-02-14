<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'nama_job',
        'bulan_masuk_job',
        'periode_masuk_job',
        'bulan_keluar_job',
        'periode_keluar_job',
        'jabatan_job',   
        'kota',
        'alamat',
        'negara',
        'catatan'
    ];

    // Relasi ke model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
