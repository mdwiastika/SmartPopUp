<?php

namespace Database\Seeders;

use App\Models\Material;
use Illuminate\Database\Seeder;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            1 => [
                'comparing numbers',
                'addition',
                'measurement',
                'counting money',
                'telling time',
                'word problem',
            ],
            2 => [
                'addition',
                'substraction',
                'multiplication',
                'counting money',
                'telling time',
                'word problem',
            ],
            3 => [
                'divisions',
                'order of operations',
                'fractions & decimals',
                'counting money',
                'time & calendar',
                'word problem',
            ],
            4 => [
                'long divisions',
                'order of operations',
                'fractions',
                'decimals',
                'factoring',
                'word problem',
            ],
            5 => [
                'order of operations',
                'fractions - multiply/divide',
                'decimals - division',
                'integers',
                'algebra',
                'word problem',
            ],
            6 => [
                'fractions - add/subtract',
                'decimals - division',
                'factoring',
                'exponents',
                'proportions',
                'percents',
            ],
        ];

        foreach ($data as $gradeId => $subjects) {
            foreach ($subjects as $subject) {
                Material::insert([
                    'grade_id' => $gradeId,
                    'name' => $subject,
                    'description' => ucfirst($subject) . ' for Grade ' . $gradeId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
