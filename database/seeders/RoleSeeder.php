<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Cek dan buat role 'alumni'
        if (Role::where('name', 'alumni')->doesntExist()) {
            Role::create(['name' => 'alumni', 'guard_name' => 'api']);
        }

        // Cek dan buat role 'mahasiswa'
        if (Role::where('name', 'mahasiswa')->doesntExist()) {
            Role::create(['name' => 'mahasiswa', 'guard_name' => 'api']);
        }

        // Cek dan buat role 'admin'
        if (Role::where('name', 'admin')->doesntExist()) {
            Role::create(['name' => 'admin', 'guard_name' => 'api']);
        }

        // Cek dan buat role 'mitra'
        if (Role::where('name', 'mitra')->doesntExist()) {
            Role::create(['name' => 'mitra', 'guard_name' => 'api']);
        }
    }
}

