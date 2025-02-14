<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Alumni;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan role telah dibuat
        $roles = ['admin', 'mahasiswa', 'alumni'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'api']);
        }

        // Membuat user admin
        $admin = User::create([
            'name' => 'Dosen Koordinator Alumni S1-Teknik Informatika',
            'email' => 'kooralumni@websti.com',
            'password' => Hash::make('Alumni#123'),
            'role' => 'admin',
            'email_verified_at' => Carbon::now(),
        ]);
        $admin->assignRole('admin');

        // Membuat user mahasiswa
        $mahasiswa = User::create([
            'name' => 'Mahasiswa',
            'email' => 'mahasiswa@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'mahasiswa',
            'email_verified_at' => Carbon::now(),
        ]);
        $mahasiswa->assignRole('mahasiswa');

        $mahasiswa = User::create([
            'name' => 'Geraldi Theo',
            'email' => 'geralditheo@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'mahasiswa',
            'email_verified_at' => Carbon::now(),
        ]);
        $mahasiswa->assignRole('mahasiswa');

        // Membuat user alumni
        $alumni = User::create([
            'name' => 'Alumni',
            'email' => 'alumni@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'alumni',
            'email_verified_at' => Carbon::now(),
        ]);
        $alumni->assignRole('alumni');
        // Seeding ke table alumni
        // Alumni::create([
        //     'user_id' => $alumni->id,
        //     'name' => $alumni->name,
        //     'email' => $alumni->email,
        //     'nim' => 'A11.2020.12345',
        //     'tahun_masuk' => 2020,
        //     'tahun_lulus' => 2024,
        //     'bulan_lulus' => 'Januari',
        //     'jns_kelamin' => 'Laki-Laki',
        //     'wisuda' => 'Lulus',
        //     'no_hp' => '081234567890',
        //     'status' => 'Bekerja',
        //     'masa_tunggu' => 3, // Dalam bulan
        //     'bidang_job' => 'Teknologi Informasi',
        //     'jns_job' => 'Full-Time',
        //     'nama_job' => 'Software Developer',
        //     'jabatan_job' => 'Junior Developer',
        //     'lingkup_job' => 'Nasional',
        //     'biaya_studi' => 'Beasiswa',
        //     'jenjang_pendidikan' => 'Sarjana',
        //     'universitas' => 'Universitas Dian Nuswantoro',
        //     'program_studi' => 'Teknik Informatika',
        // ]);
    }
}
