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
        ['name' => 'Media', 'description' => 'Cameras, lenses, tripods and studio lighting.'],
        ['name' => 'Musical Instruments', 'description' => 'Keyboards, guitars, drums and microphones.'],
        ['name' => 'Electronics & Gadgets', 'description' => 'Laptops, routers, switchers and TVs.'],
        ['name' => 'General Church Property', 'description' => 'Chairs, pulpits and stage decorations.'],
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
