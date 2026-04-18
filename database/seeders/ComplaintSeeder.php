<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComplaintSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = DB::table('users')->pluck('id')->toArray();
        $categories = DB::table('categories')->pluck('id')->toArray();
        $subcategories = DB::table('subcategories')->pluck('id')->toArray();

        for ($i = 1; $i <= 10; $i++) {
            DB::table('complaints')->insert([
                'complaint_no' => 'CMP-' . date('Y') . '-' . rand(1000, 9999),

                'user_id' => $users[array_rand($users)],
                'category_id' => $categories[array_rand($categories)],
                'subcategory_id' => !empty($subcategories) 
                                    ? $subcategories[array_rand($subcategories)] 
                                    : null,

                // ✅ NEW FIELDS
                'title' => 'Complaint ' . $i,
                'complaint_text' => 'Fan not working in classroom ' . $i,

                'priority' => ['Low', 'Medium', 'High'][rand(0,2)],

                'file' => null,

                'status' => ['Pending', 'In Progress', 'Resolved'][rand(0,2)],

                'admin_remark' => 'Checked by admin',
                'resolved_at' => rand(0,1) ? now() : null,

                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
