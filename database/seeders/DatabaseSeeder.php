<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        //$this->call(StatesTableSeeder::class);

        // $this->call(TownsTableSeeder::class);
        // $this->call(MenusTableSeeder::class);
        // $this->call(PermsSeeder::class);
        // $this->call(UsersTableSeeder::class);
        // $this->call(ExtrasTableSeeder::class);

        $this->call([
            StatesTableSeeder::class,
            TownsTableSeeder::class,
            MenusTableSeeder::class,
            PermsSeeder::class,
            UsersTableSeeder::class,
            ExtrasTableSeeder::class
        ]);
    }
}
