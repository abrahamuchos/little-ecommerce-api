<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $products = Product::where('is_available', true)->get(['id', 'price']);
        $clients = User::whereHas('roles', function( Builder $query){
            $query->where('id', '=', User::IS_CLIENT);
        })->get(['id']);

        foreach ($clients as $client){
            $productsRandom = $products->random(3);
            $qty = mt_rand(1,5);
            $total = 0;

            foreach ($productsRandom as $productRandom){
                $total += ($productRandom->price * $qty);
            }

            $order = Order::factory(1)
                ->for($client)
                ->create([
                    'total' => $total
                ]);

            foreach ($productsRandom as $product){
                Item::factory(1)
                    ->for($product)
                    ->create([
                        'order_id' => $order[0]->id,
                        'qty' => $qty,
                        'price' => ($qty * $product->price)
                    ]);
            }
        }

    }
}

