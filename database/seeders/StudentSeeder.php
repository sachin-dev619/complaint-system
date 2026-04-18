<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('students')->insert([
            [
                'first_name'=>'Rahul',
                'last_name'=>'Sharma',
                'email'=>'rahul@gmail.com', // ✅ ADD
                'roll_no'=>'1',
                'class_models_id'=>3,
                'division_id'=>1,
                'gender'=>'Male'
            ],
            [
                'first_name'=>'Priya',
                'last_name'=>'Patel',
                'email'=>'priya@gmail.com', // ✅ ADD
                'roll_no'=>'2',
                'class_models_id'=>4,
                'division_id'=>2,
                'gender'=>'Female'
            ],
        ]);
    }
}
