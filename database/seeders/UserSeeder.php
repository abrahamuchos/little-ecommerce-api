<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
//        Si hago uso del sequence me va a crear en un  orden, lo quiero alterno
//        $roles = Role::factory()
//            ->count(3)
//            ->sequence(
//                ['name' => 'admin'],
//                ['name' => 'supplier'],
//                ['name' => 'client']
//            )
//            ->create();

        $adminRole = Role::factory(1)
            ->create(['name' => 'admin']);
        $supplierRole = Role::factory(1)
            ->create(['name' => 'supplier']);
        $clientRole = Role::factory(1)
            ->create(['name' => 'client']);

        //User roles
        User::factory()
            ->count(3)
            ->hasAttached($adminRole)
            ->create();

        // User supplier role
        User::factory()
            ->count(10)
            ->hasAttached($supplierRole)
            ->create();

        //Client role
        User::factory()
            ->count(50)
            ->hasAttached($clientRole)
            ->create();


    }
}


