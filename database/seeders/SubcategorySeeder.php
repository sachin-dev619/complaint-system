<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubcategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('subcategories')->insert([
            [
                'category_id' => 1,
                'name' => 'Fan Issue'
            ],
            [
                'category_id' => 1,
                'name' => 'Light Issue'
            ],
            [
                'category_id' => 2,
                'name' => 'Water Leakage'
            ],
            [
                'category_id' => 3,
                'name' => 'Cleaning Problem'
            ]
        ]);
    }
}
