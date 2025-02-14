<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Academic;
use App\Models\Award;
use App\Models\Course;
use App\Models\Internship;
use App\Models\Job;
use App\Models\Organization;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            MitraSeeder::class,
            InterestSeeder::class,
            // DataLogangSeeder::class,
            // DataLokerSeeder::class,
        ]);
    }
}