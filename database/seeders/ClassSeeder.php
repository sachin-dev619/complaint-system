<?php

namespace Database\Seeders;

use App\Models\ClassModel;
use Illuminate\Database\Seeder;

class ClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = [
            ['class_name' => '7', 'level' => 'middle'],
            ['class_name' => '8', 'level' => 'middle'],
            ['class_name' => '9', 'level' => 'secondary'],
            ['class_name' => '10', 'level' => 'secondary'],
            ['class_name' => '11', 'level' => 'higher'],
            ['class_name' => '12', 'level' => 'higher'],
            ['class_name' => 'BSc IT', 'level' => 'college'],
            ['class_name' => 'BSc CS', 'level' => 'college'],
        ];

        foreach ($classes as $class) {
            ClassModel::updateOrCreate(
                ['class_name' => $class['class_name']],
                ['level' => $class['level']]
            );
        }
    }
}
