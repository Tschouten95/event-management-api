<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $categories = [
            'Music',
            'Art',
            'Food & Drink',
            'Sports & Fitness',
            'Business & Professional',
            'Health & Wellness',
            'Science & Technology',
            'Travel & Outdoor',
            'Film & Media',
            'Fashion',
            'Charity & Causes',
            'Community & Culture',
            'Family & Education',
            'Holiday',
            'Government & Politics',
            'Home & Lifestyle',
            'Auto, Boat & Air',
            'Hobbies',
            'Religion & Spirituality',
            'Other'
        ];


        
        foreach ($categories as $category) {
            Category::create(['name' => $category]);
        }
    }
}
