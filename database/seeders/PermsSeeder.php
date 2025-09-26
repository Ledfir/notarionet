<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()['cache']->forget('spatie.permission.cache');

        Permission::create(['name' => 'catalogos']);
        Permission::create(['name' => 'categories']);
        Permission::create(['name' => 'contracts']);

        Permission::create(['name' => 'usuarios']);
        Permission::create(['name' => 'customers']);
        Permission::create(['name' => 'users']);
        Permission::create(['name' => 'packages']);
        Permission::create(['name' => 'orders']);

        Permission::create(['name' => 'contenidos']);
        Permission::create(['name' => 'banners']);
        Permission::create(['name' => 'blog']);
        Permission::create(['name' => 'contracts_generated']);
     

        $admins = Role::create(['name' => 'administrador']);

        $admins->givePermissionTo('catalogos');
        $admins->givePermissionTo('categories');
        $admins->givePermissionTo('contracts');

        $admins->givePermissionTo('usuarios');
        $admins->givePermissionTo('customers');
        $admins->givePermissionTo('users');
        $admins->givePermissionTo('packages');
        $admins->givePermissionTo('orders');

        $admins->givePermissionTo('contenidos');
        $admins->givePermissionTo('banners');
        $admins->givePermissionTo('blog');
        $admins->givePermissionTo('contracts_generated');



        $customer = Role::create(['name' => 'cliente']);

    }
}
