<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Alumni;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MitraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan role telah dibuat
        $roles = ['mitra'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'api']);
        }

        // Membuat user mitra
        $mitra = User::create([
            'name' => 'Mitra Udinus',
            'email' => 'mitra@gmail.com',
            'password' => Hash::make('mitra123'),
            'role' => 'mitra',
            'email_verified_at' => Carbon::now(),
        ]);
        $mitra->assignRole('mitra');

    }
}
