<?php

namespace Database\Seeders;

use App\Models\Attribute;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $statusParent = Attribute::create([
            'attribute_id' => null,
            'name' => 'Status',
            'value' => null
        ]);

        Attribute::create([
            'attribute_id' => $statusParent->id,
            'name' => 'Incomplete',
            'value' => null
        ]);
        Attribute::create([
            'attribute_id' => $statusParent->id,
            'name' => 'Complete',
            'value' => null
        ]);

    }
}
