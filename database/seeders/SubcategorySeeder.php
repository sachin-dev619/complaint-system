<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Database\Seeder;

class SubcategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            'Infrastructure' => ['Fan Issue', 'Light Issue', 'Water Leakage', 'Cleaning Problem'],
            'Library' => ['Book Not Available', 'Damaged Book'],
            'IT Support' => ['WiFi Issue', 'Projector Not Working'],
            'Faculty' => ['Teaching Quality', 'Attendance Issue'],
            'Student' => ['Bullying', 'Misbehavior'],
            'Other' => ['General Query'],
        ];

        foreach ($items as $categoryName => $subcategories) {
            $category = Category::where('name', $categoryName)->first();

            if (!$category) {
                continue;
            }

            foreach ($subcategories as $name) {
                Subcategory::updateOrCreate(
                    [
                        'category_id' => $category->id,
                        'name' => $name,
                    ]
                );
            }
        }
    }
}
