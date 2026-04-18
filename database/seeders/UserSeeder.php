<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // DB::table('users')->insert([
        //     [
        //         'name'=>'Admin',
        //         'email'=>'admin@gmail.com',
        //         'password'=>bcrypt('123456'),
        //         'role'=>'admin'
        //     ]
        // ]);
        // ADMIN
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456'),
            'role' => 'admin'
        ]);

        // STUDENT 1
        // User::create([
        //     'name' => 'Student One',
        //     'email' => 'student1@gmail.com',
        //     'password' => Hash::make('123456'),
        //     'role' => 'student'
        // ]);

        // // STUDENT 2
        // User::create([
        //     'name' => 'Student Two',
        //     'email' => 'student2@gmail.com',
        //     'password' => Hash::make('123456'),
        //     'role' => 'student'
        // ]);
    }
}
