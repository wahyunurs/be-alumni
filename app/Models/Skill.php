<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'kerjasama_skill',
        'ahli_skill',
        'inggris_skill',
        'komunikasi_skill',
        'pengembangan_skill',
        'kepemimpinan_skill',
        'etoskerja_skill'
    ];
    
    // Relasi ke model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
