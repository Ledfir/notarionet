<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Extra;

class ExtrasController extends Controller
{
	public function getPrivacidad()
    {
    	$body = Extra::where('key', 'AVISO_PRIVACIDAD')->first()->value;

    	$data = [
    		'body' => $body,
    		'id' => 11
    	];

    	return response()->json($data);
	}

	public function updatePrivacidad(Request $request)
    {
    	if($request->body)	
    		Extra::where('key', 'AVISO_PRIVACIDAD')->update(['value' => $request->body]);

    	return response()->json(['msg' => "Actualizado correctamente"]);
    }
	
    public function getTerminos()
    {
    	$body = Extra::where('key', 'TERMINOS_CONDICIONES')->first()->value;

    	$data = [
    		'body' => $body,
    		'id' => 8
    	];

    	return response()->json($data);
	}

    public function updateTerminos(Request $request)
    {
    	if($request->body)	
    		Extra::where('key', 'TERMINOS_CONDICIONES')->update(['value' => $request->body]);

    	return response()->json(['msg' => "Actualizado correctamente"]);
    }

    public function getRedes()
    {
    	$facebook = Extra::where('key', 'URL_FACEBOOK')->first()->value;;
		$instagram = Extra::where('key', 'URL_INSTAGRAM')->first()->value;;
		$youtube = Extra::where('key', 'URL_YOUTUBE')->first()->value;;
    	$email = Extra::where('key', 'EMAIL')->first()->value;;
		$telefono = Extra::where('key', 'TELPHONE')->first()->value;;
		$address = Extra::where('key', 'ADDRESS')->first()->value;;

    	$data = [
    		'facebook' => $facebook,
			'instagram' => $instagram,
			'youtube' => $youtube,
    		'email' => $email,
			'telefono' => $telefono,
			'address' => $address
    	];

    	return response()->json($data);
	}

	public function updateRedes(Request $request)
    {

    	if($request->has('facebook'))
    		Extra::where('key', 'URL_FACEBOOK')->update(['value' => $request->facebook]);

    	if($request->has('instagram'))
			Extra::where('key', 'URL_INSTAGRAM')->update(['value' => $request->instagram]);
			
		if($request->has('youtube'))
    		Extra::where('key', 'URL_YOUTUBE')->update(['value' => $request->youtube]);

		if($request->has('email'))
    		Extra::where('key', 'EMAIL')->update(['value' => $request->email]);

		if($request->has('telefono'))
			Extra::where('key', 'TELPHONE')->update(['value' => $request->telefono]);
		
		if($request->has('address'))
    		Extra::where('key', 'ADDRESS')->update(['value' => $request->address]);

    	return response()->json([ "msg" => "ok" ]);
	}
	
	public function getFaq()
    {
		$body = Extra::where('key', 'FAQ_BODY')->first()->value;;
		
		$data = [
    		'body' => $body,
    	];

    	return response()->json($data);
	}
	
	public function updateFaq(Request $request)
    {
		if($request->has('body'))
			Extra::where('key', 'FAQ_BODY')->update(['value' => $request->body]);

    	return response()->json([ "msg" => "ok" ]);
    }
}
