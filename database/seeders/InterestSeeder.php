<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Interest;

class InterestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $interests = [
            'Data analysis',
            'Natural Language Processing',
            'Artificial Intelligence',
            'Neural Networks',
            'Pattern Recognition',
            'Internet of Things (IoT)',
            'Remote Sensing',
            'Image Processing',
            'Fuzzy Logic',
            'Genetic Algorithm',
            'Bioinformatics/Biomedical Applications',
            'Biometrical Application',
            'Computer Network and Architecture',
            'Network Security',
            'Content-Based Multimedia Retrievals',
            'Augmented Reality',
            'Virtual Reality',
            'Information System',
            'Game Mobile',
            'IT Bussiness Incubation',
        ];

        foreach ($interests as $interest) {
            Interest::create(['name' => $interest]);
        }
    }
}
