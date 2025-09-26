<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Address;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::factory()->create(['name' => 'admin', "lastname" => "sustam", 'email' => 'admin@xxxxxx.com', 'password' => bcrypt('adminxxxxxxxx'), 'access' => 1]);
        $adminAddress = Address::create(['user_id' => $admin->id]);
        $admin->assignRole('administrador');

        $user = User::factory()->create(['name' => 'cliente', "lastname" => "sustam", 'email' => 'cliente@xxxxxx.com', 'password' => bcrypt('clientexxxxxxx')]);
        $userAddress = Address::create(['user_id' => $user->id]);   
        $user->assignRole('cliente');
    }
}
