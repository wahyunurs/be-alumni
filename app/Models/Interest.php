<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User; // Pastikan ada import model User

class Interest extends Model
{
    use HasFactory;

    protected $fillable = [
        'interest_id',
        'user_id',
        'name'
    ];

    // Relasi many-to-many dengan User
    public function users()
    {
        return $this->belongsToMany(User::class, 'interest_user', 'interest_id', 'user_id')
                    ->withTimestamps(); // Tambahkan jika tabel pivot memiliki kolom timestamps
    }
}
