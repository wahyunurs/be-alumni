<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'nama_org',
        'bulan_masuk_org',
        'periode_masuk_org',
        'bulan_keluar_org',
        'periode_keluar_org',
        'jabatan_org',
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
