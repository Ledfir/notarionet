<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Menu;

class MenusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
         DB::table('menus')->insert([
            "name"=>"Catalogos",
            "description"=>"Catalogos de tu sitio",
            "route"=>"/",
            "icon"=>"fa fa-bars",
            "permissions_name"=>"catalogos",
            "position"=>1
        ]);

         DB::table('menus')->insert([
            "name"=>"Categorias",
            "description"=>"Categorias de tu sitio",
            "route"=>"/categories",
            "icon"=>"fa fa-bars",
            "parent"=>1,
            "permissions_name"=>"categories",
            "position"=>null
        ]);

         DB::table('menus')->insert([
            "name"=>"Contratos",
            "description"=>"Contratos de tu sitio",
            "route"=>"/contracts",
            "icon"=>"fa fa-bars",
            "parent"=>1,
            "permissions_name"=>"contracts",
            "position"=>null
        ]);

        DB::table('menus')->insert([
            "name"=>"Usuarios",
            "description"=>"Usuarios de tu sitio",
            "route"=>"/",
            "icon"=>"fa fa-users",
            "permissions_name"=>"usuarios",
            "position"=>2
        ]);

         DB::table('menus')->insert([
            "name"=>"Clientes",
            "description"=>"Clientes de tu sitio",
            "route"=>"/customers",
            "icon"=>"fa fa-bars",
            "parent"=>4,
            "permissions_name"=>"customers",
            "position"=>null
        ]);
        DB::table('menus')->insert([
            "name"=>"Administradores",
            "description"=>"Administradores de tu sitio",
            "route"=>"/users",
            "icon"=>"fa fa-bars",
            "parent"=>4,
            "permissions_name"=>"users",
            "position"=>null
        ]);
        DB::table('menus')->insert([
            "name"=>"Paquetes",
            "description"=>"Creditos y paquetes de tu sitio",
            "route"=>"/packages",
            "icon"=>"fa fa-bars",
            "parent"=>4,
            "permissions_name"=>"packages",
            "position"=>null
        ]);
        DB::table('menus')->insert([
            "name"=>"Pedidos",
            "description"=>"Pedidos de tu sitio",
            "route"=>"/orders",
            "icon"=>"fa fa-bars",
            "parent"=>4,
            "permissions_name"=>"orders",
            "position"=>null
        ]);

        

        DB::table('menus')->insert([
            "name"=>"Contenidos",
            "description"=>"Contenidos de tu sitio",
            "route"=>"/",
            "icon"=>"fa fa-circle",
            "permissions_name"=>"contenidos",
            "position"=>3
        ]);

        DB::table('menus')->insert([
            "name"=>"Banners",
            "description"=>"Banners de tu sitio",
            "route"=>"/banners",
            "icon"=>"fa fa-bars",
            "parent"=>9,
            "permissions_name"=>"banners",
            "position"=>null
        ]);

        DB::table('menus')->insert([
            "name"=>"Blog",
            "description"=>"Blog de tu sitio",
            "route"=>"/blogs",
            "icon"=>"fa fa-bars",
            "parent"=>9,
            "permissions_name"=>"blog",
            "position"=>null
        ]);

        DB::table('menus')->insert([
            "name"=>"Terminos y condiciones",
            "description"=>"Terminos y condiciones de tu sitio",
            "route"=>"/terminos-condiciones",
            "icon"=>"fa fa-bars",
            "parent"=>9,
            "permissions_name"=>"contenidos",
            "position"=>null
        ]);
        DB::table('menus')->insert([
            "name"=>"Aviso de privacidad",
            "description"=>"Aviso de privacidad de tu sitio",
            "route"=>"/aviso-privacidad",
            "icon"=>"fa fa-bars",
            "parent"=>9,
            "permissions_name"=>"contenidos",
            "position"=>null
        ]);


        DB::table('menus')->insert([
            "name"=>"Base de datos de contratos generados",
            "description"=>"Base de datos de contratos generados de tu sitio",
            "route"=>"/contracts_generated",
            "icon"=>"fa fa-invoce",
            "permissions_name"=>"contracts_generated",
            "position"=>4
        ]);
        
    }
}
