<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
//        Power Tool
        $powerTool = Category::factory()
            ->create([
                'name' => 'Power Tool',
            ]);

        Category::factory()
            ->create([
                'category_id' => $powerTool->id,
                'name' => 'Fijación',
            ]);
        Category::factory()
            ->create([
                'category_id' => $powerTool->id,
                'name' => 'Perforación',
            ]);

//        Lighting
        Category::factory()
            ->create([
                'name' => 'Lighting'
            ]);


//        Plumbing
        Category::factory()
            ->create([
                'name' => 'Plumbing '
            ]);

//        Accessories
        $accessories = Category::factory()
            ->create([
                'name' => 'Accessories'
            ]);

        Category::factory()
            ->create([
                'category_id' => $accessories->id,
                'name' => 'Corte',
            ]);

        Category::factory()
            ->create([
                'category_id' => $accessories->id,
                'name' => 'Conducción y Fijación',
            ]);


    }
}
