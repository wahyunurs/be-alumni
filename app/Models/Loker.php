<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loker extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'NamaPerusahaan',
        'Posisi',
        'Alamat',
        'Pengalaman',
        'Gaji',
        'TipeKerja',
        'Deskripsi',
        'Website',
        'Email',
        'no_hp',
        'Tags',
        'Logo',
        'Verify',
        'MasaBerlaku'
    ];

    // Relasi ke model User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected static function booted()
    {
        static::creating(function ($loker) {
            // Jika role pengguna adalah alumni dan admin, otomatis gunakan Auth::id() untuk user_id
            if (Auth::check() && Auth::user()->role === 'alumni' && 'admin') {
                $loker->user_id = Auth::id();
            }
        });
    }
}
