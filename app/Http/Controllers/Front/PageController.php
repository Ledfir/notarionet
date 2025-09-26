<?php

namespace App\Http\Controllers\Front;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class PageController extends Controller
{
    public function contact(Request $request){
      $data=$request->all();

      foreach ($data as $key => $value) {
        $data[$key] = strip_tags($data[$key]);
      }

      $data['phone'] = (empty($data['phone'])) ? '-' : $data['phone'];

      $niceNames = array(
        'name' => 'El Nombre',
        'email' => 'El Email',
        'phone' => 'El Teléfono',
        'msg' => 'El Mensaje',
      );
      $validator = Validator::make($request->all(), [
        'name' => 'required',
        'email' => 'required|email',
        'phone' => 'required|numeric|digits_between:8,10',
        'msg' => 'required',
      ]);
      $validator->setAttributeNames($niceNames);

      if ($validator->fails()) {
        return response()->json($validator->errors(), 400);
      }

      Mail::send('emails.contact',(array)$data,function($m) use ($data){
        $m->from('no-reply@notarionet.com.mx', $name = 'NOTARYNET');
        $m->to('hola@notarionet.com');
  	    $m->subject('NOTARYNET | Formulario de Contacto');
  		});

      if( count(Mail::failures()) > 0 ) {
        return response()->json(['name'=> [0 => 'Error. Intente nuevamente'] ], 400);
      }

      return response()->json(['response'=>'Gracias por enviarnos su mensaje, nos pondremos en contacto con usted lo antes posible.']);
    }
}
