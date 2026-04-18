<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            ['name'=>'Faculty'],
            ['name'=>'Student'],
            ['name'=>'Infrastructure'],
            ['name'=>'Library'],
            ['name'=>'IT Support'],
            ['name'=>'Other'],
        ]);
    }
}
