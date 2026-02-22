<?php

namespace Database\Seeders;

use App\Models\Grade;
use Illuminate\Database\Seeder;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $grades = [
            [
                'name' => '1 SD',
            ],
            [
                'name' => '2 SD',
            ],
            [
                'name' => '3 SD',
            ],
            [
                'name' => '4 SD',
            ],
            [
                'name' => '5 SD',
            ],
            [
                'name' => '6 SD',
            ],
        ];

        Grade::insert($grades);
    }
}
