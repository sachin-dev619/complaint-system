<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('class_models')->insert([
            ['class_name'=>'7','level'=>'middle'],
            ['class_name'=>'8','level'=>'middle'],
            ['class_name'=>'9','level'=>'secondary'],
            ['class_name'=>'10','level'=>'secondary'],
            ['class_name'=>'11','level'=>'higher'],
            ['class_name'=>'12','level'=>'higher'],
            ['class_name'=>'BSc IT','level'=>'college'],
            ['class_name'=>'BSc CS','level'=>'college'],
        ]);
    }
}
