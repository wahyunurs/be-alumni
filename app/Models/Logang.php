<?php

namespace App\Models;


use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logang extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'NamaPerusahaan',
        'Posisi',
        'Alamat',
        'Pengalaman',
        'Gaji',
        'TipeMagang',
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
        return $this->belongsTo(User::class);
    }
    protected static function booted()
    {
        static::creating(function ($logang) {
            // Jika role pengguna adalah alumni dan admin, otomatis gunakan Auth::id() untuk user_id
            if (Auth::check() && Auth::user()->role === 'alumni' && 'admin') {
                $logang->user_id = Auth::id();
            }
        });
    }
}
