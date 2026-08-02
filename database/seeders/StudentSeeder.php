<?php

namespace Database\Seeders;

use App\Models\ClassModel;
use App\Models\Division;
use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $class9 = ClassModel::where('class_name', '9')->first();
        $class10 = ClassModel::where('class_name', '10')->first();
        $divisionA = Division::where('division_name', 'A')->first();
        $divisionB = Division::where('division_name', 'B')->first();

        if (!$class9 || !$class10 || !$divisionA || !$divisionB) {
            return;
        }

        $students = [
            [
                'first_name' => 'Rahul',
                'last_name' => 'Sharma',
                'email' => 'rahul@gmail.com',
                'roll_no' => '1',
                'class_models_id' => $class9->id,
                'division_id' => $divisionA->id,
                'gender' => 'Male',
            ],
            [
                'first_name' => 'Priya',
                'last_name' => 'Patel',
                'email' => 'priya@gmail.com',
                'roll_no' => '2',
                'class_models_id' => $class10->id,
                'division_id' => $divisionB->id,
                'gender' => 'Female',
            ],
        ];

        foreach ($students as $student) {
            Student::updateOrCreate(
                ['email' => $student['email']],
                $student
            );
        }
    }
}
