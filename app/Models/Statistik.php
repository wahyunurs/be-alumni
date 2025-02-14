<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statistik extends Model
{
    use HasFactory;

    protected $fillable = [
        'tahun_lulus',
        'alumni_total',
        'alumni_terlacak',
    ];
}
