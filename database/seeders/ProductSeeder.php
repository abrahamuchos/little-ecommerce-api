<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Get all suppliers
        $suppliers = User::whereHas('roles', function (Builder $query) {
            $query->where('id', '=', User::IS_SUPPLIER);
        })->get(['id']);

        // Get all subcategories
        $powerToolCategory = Category::where('category_id', 1)->get();
        $accessoriesCategory = Category::where('category_id', 6)->get();

        // Añado 3 productos por cada categoria a cada usuario (supplier)
        foreach ($suppliers as $supplier) {
            Product::factory(3)
                ->hasAttached($supplier)
                ->create([
                    'category_id' => $powerToolCategory->random()->id,
                ]);
            Product::factory(3)
                ->hasAttached($supplier)
                ->create([
                    'category_id' => 4, // Lighting
                ]);
            Product::factory(3)
                ->hasAttached($supplier)
                ->create([
                    'category_id' => 5, // Plumbing
                ]);
            Product::factory(3)
                ->hasAttached($supplier)
                ->create([
                    'category_id' => $accessoriesCategory->random()->id,
                ]);

        }
    }
}
