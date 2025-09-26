<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function home(){
		return view('page');
	}

	public function admin(){
		return view('admin');
	}
	
	public function testt(){
        ini_set('memory_limit',-1);
        
        $tohash = '559aead08264d5795d3909718cdd05abd49572e84fe55590eef31a88a08fdffd';
        $key_file = 'seguridata/dist/files/system.key';
        $pass_file = '12121212Qw.+';
        $cer_file = 'seguridata/dist/files/system.cer';
        $output = null;
        //$resultado = exec('java -cp seguridata/notarynet-1.0.jar com.mycompany.notarynet.Notarynet '.$hash, $output);
            
        exec('java -jar seguridata/dist/SgSignSignerAPIv2.6.1_C.jar  '.$tohash.' '.$key_file.' '.$pass_file.' '.$cer_file, $output);
          
        dd($output);
       
        
	}
	
	
}
