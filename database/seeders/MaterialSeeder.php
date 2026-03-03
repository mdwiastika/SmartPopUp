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
        $materials = [

        ['grade_id' => 1, 'name' => 'Comparing Numbers', 'description' => 'Comparing Numbers for Grade 1', 'image_url' => '/storage/materials/comparing-numbers.svg'],
        ['grade_id' => 1, 'name' => 'Addition', 'description' => 'Addition for Grade 1', 'image_url' => '/storage/materials/addition.svg'],
        ['grade_id' => 1, 'name' => 'Measurement', 'description' => 'Measurement for Grade 1', 'image_url' => '/storage/materials/measurement.svg'],
        ['grade_id' => 1, 'name' => 'Counting Money', 'description' => 'Counting Money for Grade 1', 'image_url' => '/storage/materials/counting-money.svg'],
        ['grade_id' => 1, 'name' => 'Telling Time', 'description' => 'Telling Time for Grade 1', 'image_url' => '/storage/materials/telling-time.svg'],
        ['grade_id' => 1, 'name' => 'Word Problem', 'description' => 'Word Problem for Grade 1', 'image_url' => '/storage/materials/word-problem.svg'],

        ['grade_id' => 2, 'name' => 'Addition', 'description' => 'Addition for Grade 2', 'image_url' => '/storage/materials/addition.svg'],
        ['grade_id' => 2, 'name' => 'Substraction', 'description' => 'Substraction for Grade 2', 'image_url' => '/storage/materials/substraction.svg'],
        ['grade_id' => 2, 'name' => 'Multiplication', 'description' => 'Multiplication for Grade 2', 'image_url' => '/storage/materials/multiplication.svg'],
        ['grade_id' => 2, 'name' => 'Counting Money', 'description' => 'Counting Money for Grade 2', 'image_url' => '/storage/materials/counting-money.svg'],
        ['grade_id' => 2, 'name' => 'Telling Time', 'description' => 'Telling Time for Grade 2', 'image_url' => '/storage/materials/telling-time.svg'],
        ['grade_id' => 2, 'name' => 'Word Problem', 'description' => 'Word Problem for Grade 2', 'image_url' => '/storage/materials/word-problem.svg'],

        ['grade_id' => 3, 'name' => 'Divisions', 'description' => 'Divisions for Grade 3', 'image_url' => '/storage/materials/divisions.svg'],
        ['grade_id' => 3, 'name' => 'Order Of Operations', 'description' => 'Order Of Operations for Grade 3', 'image_url' => '/storage/materials/order-of-operations.svg'],
        ['grade_id' => 3, 'name' => 'Fractions & Decimals', 'description' => 'Fractions & Decimals for Grade 3', 'image_url' => '/storage/materials/fractions-decimals.svg'],
        ['grade_id' => 3, 'name' => 'Counting Money', 'description' => 'Counting Money for Grade 3', 'image_url' => '/storage/materials/counting-money.svg'],
        ['grade_id' => 3, 'name' => 'Time & Calendar', 'description' => 'Time & Calendar for Grade 3', 'image_url' => '/storage/materials/time-calendar.svg'],
        ['grade_id' => 3, 'name' => 'Word Problem', 'description' => 'Word Problem for Grade 3', 'image_url' => '/storage/materials/word-problem.svg'],

        ['grade_id' => 4, 'name' => 'Long Divisions', 'description' => 'Long Divisions for Grade 4', 'image_url' => '/storage/materials/long-divisions.svg'],
        ['grade_id' => 4, 'name' => 'Order Of Operations', 'description' => 'Order Of Operations for Grade 4', 'image_url' => '/storage/materials/order-of-operations.svg'],
        ['grade_id' => 4, 'name' => 'Fractions', 'description' => 'Fractions for Grade 4', 'image_url' => '/storage/materials/fractions.svg'],
        ['grade_id' => 4, 'name' => 'Decimals', 'description' => 'Decimals for Grade 4', 'image_url' => '/storage/materials/decimals.svg'],
        ['grade_id' => 4, 'name' => 'Factoring', 'description' => 'Factoring for Grade 4', 'image_url' => '/storage/materials/factoring.svg'],
        ['grade_id' => 4, 'name' => 'Word Problem', 'description' => 'Word Problem for Grade 4', 'image_url' => '/storage/materials/word-problem.svg'],

        ['grade_id' => 5, 'name' => 'Order Of Operations', 'description' => 'Order Of Operations for Grade 5', 'image_url' => '/storage/materials/order-of-operations.svg'],
        ['grade_id' => 5, 'name' => 'Fractions - Multiply/Divide', 'description' => 'Fractions - Multiply/Divide for Grade 5', 'image_url' => '/storage/materials/fractions-multiply-divide.svg'],
        ['grade_id' => 5, 'name' => 'Decimals - Division', 'description' => 'Decimals - Division for Grade 5', 'image_url' => '/storage/materials/decimals-division.svg'],
        ['grade_id' => 5, 'name' => 'Integers', 'description' => 'Integers for Grade 5', 'image_url' => '/storage/materials/integers.svg'],
        ['grade_id' => 5, 'name' => 'Algebra', 'description' => 'Algebra for Grade 5', 'image_url' => '/storage/materials/algebra.svg'],
        ['grade_id' => 5, 'name' => 'Word Problem', 'description' => 'Word Problem for Grade 5', 'image_url' => '/storage/materials/word-problem.svg'],

        ['grade_id' => 6, 'name' => 'Fractions - Add/Subtract', 'description' => 'Fractions - Add/Subtract for Grade 6', 'image_url' => '/storage/materials/fractions-add-subtract.svg'],
        ['grade_id' => 6, 'name' => 'Decimals - Division', 'description' => 'Decimals - Division for Grade 6', 'image_url' => '/storage/materials/decimals-division.svg'],
        ['grade_id' => 6, 'name' => 'Factoring', 'description' => 'Factoring for Grade 6', 'image_url' => '/storage/materials/factoring.svg'],
        ['grade_id' => 6, 'name' => 'Exponents', 'description' => 'Exponents for Grade 6', 'image_url' => '/storage/materials/exponents.svg'],
        ['grade_id' => 6, 'name' => 'Proportions', 'description' => 'Proportions for Grade 6', 'image_url' => '/storage/materials/proportions.svg'],
        ['grade_id' => 6, 'name' => 'Percents', 'description' => 'Percents for Grade 6', 'image_url' => '/storage/materials/percents.svg'],

    ];

    Material::insert($materials);
    }
}
