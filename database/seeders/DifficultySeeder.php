<?php

namespace Database\Seeders;

use App\Models\Difficulty;
use Illuminate\Database\Seeder;

class DifficultySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $difficulties = [
            ['name' => 'Short Question'],
            ['name' => 'Long Question'],
        ];

        Difficulty::insert($difficulties);
    }
}
