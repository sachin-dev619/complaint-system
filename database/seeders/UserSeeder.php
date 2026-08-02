<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => '123456',
                'role' => 'admin',
                'student_id' => null,
            ]
        );

        $students = Student::all();

        foreach ($students as $student) {
            User::updateOrCreate(
                ['email' => $student->email],
                [
                    'name' => trim($student->first_name . ' ' . $student->last_name),
                    'password' => '123456',
                    'role' => 'student',
                    'student_id' => $student->id,
                ]
            );
        }
    }
}
