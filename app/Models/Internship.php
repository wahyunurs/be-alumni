<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Internship extends Model
{
    use HasFactory;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'nama_intern',
        'bulan_masuk_intern',
        'periode_masuk_intern',
        'bulan_keluar_intern',
        'periode_keluar_intern',
        'jabatan_intern',
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
