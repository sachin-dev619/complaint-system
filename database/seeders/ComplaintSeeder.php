<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Complaint;
use App\Models\User;
use Illuminate\Database\Seeder;

class ComplaintSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = User::where('role', 'student')->pluck('id');
        $categories = Category::with('subcategories')->get();

        if ($students->isEmpty() || $categories->isEmpty()) {
            return;
        }

        $priorities = ['Low', 'Medium', 'High'];
        $statuses = ['Pending', 'In Progress', 'Resolved'];

        for ($i = 1; $i <= 10; $i++) {
            $category = $categories->random();
            $subcategory = $category->subcategories->isNotEmpty()
                ? $category->subcategories->random()
                : null;

            $status = $statuses[array_rand($statuses)];

            Complaint::updateOrCreate(
                ['complaint_no' => 'CMP-' . date('Y') . '-' . str_pad((string) $i, 4, '0', STR_PAD_LEFT)],
                [
                    'user_id' => $students->random(),
                    'category_id' => $category->id,
                    'subcategory_id' => $subcategory?->id,
                    'title' => 'Complaint ' . $i,
                    'complaint_text' => 'Sample complaint description for case ' . $i,
                    'priority' => $priorities[array_rand($priorities)],
                    'file' => null,
                    'status' => $status,
                    'admin_remark' => $status === 'Resolved' ? 'Checked by admin' : null,
                    'resolved_at' => $status === 'Resolved' ? now() : null,
                ]
            );
        }
    }
}
