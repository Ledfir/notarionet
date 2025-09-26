<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExtrasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('extras')->insert([
        	"id"=>1,
        	"key"=>"NOSOTROS",
        	"value" => "contenido de nosotros (empresa)",
        ]);

        DB::table('extras')->insert([
        	"id"=>2,
        	"key"=>"HISTORIA",
        	"value" => "Historia de la empresa",
        ]);

        DB::table('extras')->insert([
        	"id"=>3,
        	"key"=>"URL_FACEBOOK",
        	"value" => "https://facebook.com",
        ]);

        DB::table('extras')->insert([
        	"id"=>4,
        	"key"=>"URL_INSTAGRAM",
        	"value" => "https://instagram.com",
		]);

        DB::table('extras')->insert([
        	"id"=>5,
        	"key"=>"EMAIL",
        	"value" => "contacto@domain.com",
        ]);

        DB::table('extras')->insert([
        	"id"=>6,
        	"key"=>"TELPHONE",
        	"value" => "1234567890",
		]);

		DB::table('extras')->insert([
        	"id"=>7,
        	"key"=>"URL_YOUTUBE",
        	"value" => "https://youtube.com",
        ]);
		
		DB::table('extras')->insert([
        	"id"=>8,
        	"key"=>"ADDRESS",
        	"value" => "Av. Diamante 2680.<br />Col. Bosques de la Victoria<br />Guadalajara, Jal.",
        ]);

        DB::table('extras')->insert([
        	"id"=>9,
        	"key"=>"TERMINOS_CONDICIONES",
        	"value" => "terminos y condiciones de la pagina",
        ]);

		DB::table('extras')->insert([
        	"id"=>10,
        	"key"=>"FAQ_BODY",
        	"value" => "Preguntas Frecuentas de la pagina",
        ]);

		DB::table('extras')->insert([
        	"id"=>11,
        	"key"=>"AVISO_PRIVACIDAD",
        	"value" => "Aviso de privacidad de la pagina",
        ]);
    }
}
