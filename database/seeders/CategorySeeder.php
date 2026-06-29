<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * The core church asset categories.
     *
     * @var array<int, array{name: string, description: string}>
     */
    protected array $categories = [
        ['name' => 'Vehicles', 'description' => 'Church vans, cars, and passenger transport vehicles.'],
        ['name' => 'Media & Musical Instruments', 'description' => 'Audio, video, lighting equipment, and musical instruments.'],
        ['name' => 'Office & Furniture', 'description' => 'Office furniture, computers, plastic chairs, and printing equipment.'],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->categories as $category) {
            Category::query()->updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                $category,
            );
        }
    }
}
