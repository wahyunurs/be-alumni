<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject; // Import JWTSubject

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relasi ke model Alumni
    public function alumni()
    {
        return $this->hasOne(Alumni::class, 'user_id', 'id');
    }

    // Relasi ke model academic (dapat memiliki banyak catatan academic >1)
    public function academics()
    {
        return $this->hasMany(Academic::class);
    }

    // Relasi ke model job (dapat memiliki banyak catatan job >1)
    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    // Relasi ke model internship (dapat memiliki banyak catatan internship >1)
    public function internships()
    {
        return $this->hasMany(Internship::class);
    }

    // Relasi ke model organization (dapat memiliki banyak catatan organization >1)
    public function organizations()
    {
        return $this->hasMany(Organization::class);
    }

    // Relasi ke model award (dapat memiliki banyak catatan award >1)
    public function awards()
    {
        return $this->hasMany(Award::class);
    }

    // Relasi ke model course (dapat memiliki banyak catatan course >1)
    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    // Relasi ke model skill (hanya dapat memiliki satu catatan skill)
    public function skills()
    {
        return $this->hasOne(Skill::class);
    }

    // Implementasi metode dari JWTSubject
    public function getJWTIdentifier()
    {
        return $this->getKey(); // Mengembalikan ID pengguna
    }

    public function getJWTCustomClaims()
    {
        return []; // Mengembalikan klaim khusus jika ada
    }
    // Relasi one-to-many ke Loker
    public function lokers()
    {
        return $this->hasMany(Loker::class);
    }

    // Relasi one-to-many ke Logang
    public function logangs()
    {
        return $this->hasMany(Logang::class);
    }

    // Definisi relasi dengan tabel 'interests'
    public function interests()
    {
        return $this->belongsToMany(Interest::class, 'interest_user', 'user_id', 'interest_id')
                    ->withTimestamps(); // Sama seperti di model Interest
    }
    public function SurveiMitra()
    {
        return $this->hasMany(SurveiMitra::class);
    }
}
