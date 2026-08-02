<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ClassSeeder::class,
            DivisionSeeder::class,
            CategorySeeder::class,
            SubcategorySeeder::class,
            StudentSeeder::class,
            UserSeeder::class,
            ComplaintSeeder::class,
        ]);
    }
}
