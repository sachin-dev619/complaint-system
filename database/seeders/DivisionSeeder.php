<?php

namespace Database\Seeders;

use App\Models\Division;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (['A', 'B', 'C'] as $name) {
            Division::updateOrCreate(
                ['division_name' => $name]
            );
        }
    }
}
