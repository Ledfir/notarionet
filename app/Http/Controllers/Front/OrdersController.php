<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderImage;
use App\Models\ContractClause;
use App\Models\Contract;
use App\Models\User;
use App\Models\Document;
use App\Models\State;
use App\Models\Town;
use App\Models\OrderContact;
use App\Models\ContractImages;

use App\Mail\ContractContraMail;
use App\Mail\ContractMail;
use App\Mail\ContractComplete;
use App\Mail\ContractUpdateMail;
use App\Models\Image;

use Smalot\PdfParser\Parser;
use Gufy\PdfToHtml\Pdf as Pdfguffy;

use Illuminate\Support\Facades\Http;

use Documents;
use Images;
class OrdersController extends Controller
{
    public function saveOrder (Request $request)
    {
        
        $userdata = Auth::user();
        //se crea la order
        $order = new Order();
        $order->no_signature_creator = $request->no_signature_creator;
        $order->type_contracts_id = $request->type_contracts_id;
        if ($request->contracts_id != 0 && $request->contracts_id != 999999999) {
            $order->total = $request->contractdata['price'];
            $order->subtotal = $request->contractdata['price'];
            $order->contracts_id = $request->contractdata['id'];
        }
        else{
            $order->total = 0;
            $order->subtotal = 0;
            $order->contracts_id = 0;
        }
        $order->user_id = $userdata->id;
        $order->signature_user_id = $userdata->signature_image_id;
        //$order->user_contra_id = $request->user_contra_id;
        $order->status = 'pendiente';
        $order->stamp = Str::random(20);
        $order->certificate = Str::random(20);
        $order->date_signature_user = date('Y-m-d H:i:s');
        $body_format = '';
        if ($request->contracts_id == 0) {
            
            //remplazamos la parte 1
            $body_format = $request->body;
            $body_format = str_replace("[parte1_nombre]", $userdata->name, $body_format);
            $body_format = str_replace("[parte1_email]", $userdata->email, $body_format);
            $body_format = str_replace("[parte1_telefono]", $userdata->phone, $body_format);
            $body_format = str_replace("[parte1_rfc]", $userdata->rfc, $body_format);
            $body_format = str_replace("[parte1_curp]", $userdata->curp, $body_format);
            $body_format = str_replace("[parte1_calle]", $userdata->address->calle, $body_format);
            $body_format = str_replace("[parte1_numext]", $userdata->address->num_ext, $body_format);
            $body_format = str_replace("[parte1_numint]", $userdata->address->num_int, $body_format);
            $body_format = str_replace("[parte1_colonia]", $userdata->address->neighborhood, $body_format);
            $body_format = str_replace("[parte1_cp]", $userdata->address->zipcode, $body_format);
            $body_format = str_replace("[parte1_ciudad]", $userdata->address->town, $body_format);
            $body_format = str_replace("[parte1_estado]", $userdata->address->state, $body_format);

            $order->body = $body_format;
        }
        elseif ($request->contracts_id == 999999999) {
            $body_format = $request->body;
            $order->body =  $request->body;
        }
        
        
        $order->save();

        
        $contractdata = Contract::find($request->contracts_id);
        /*if ( $contractdata) {
            $restcredits = User::find($order->user_id);
            $restcredits->credits = $restcredits->credits - $contractdata->price;
            $restcredits->save();
        }
        else{
            $restcredits = User::find($order->user_id);
            $restcredits->credits = $restcredits->credits - 1;
            $restcredits->save();
        }*/
        

        //guardamos clausulas
        $clauses_detail = [];
        if ($request->contracts_id != 0 && $request->contracts_id != 999999999) {
            
            /*foreach ($request->contractdata['clauses'] as $key => $value) {
                $detail = new OrderDetail();
                $detail->order_id = $order->id;
                $detail->contracts_id = $order->contracts_id;
                $detail->contract_clauses_id = $value['id'];
                $detail->response_data = $value['descriptionview'];
                $detail->save();

                $detail->title = ContractClause::find($detail->contract_clauses_id)->title;
                array_push($clauses_detail,$detail);
            }*/
        }

        $name_contras = null;
        $email_contras = null;
        $phone_contras = null;
        $rfc_contras = null;
        $curp_contras = null;

        $calle_contras = null;
        $num_ext_contras = null;
        $num_int_contras = null;
        $neighborhood_contras = null;
        $zipcode_contras = null;
        $town_contras = null;
        $state_contras = null;
        $address_contras = null;
        //guardamos y obtenemos los usuarios contrarios
        $users_contras = User::whereIn('id',$request->contacts)->get();
        foreach ($users_contras as $key => $value) {
            $value->signature_contra_imageUrl = Images::getUrl($value->signature_value_id);
            $value->address;
            if ($value->address != null) {

                if (State::find($value->address->state_id)) {
                    $value->address->state = State::find($value->address->state_id)->name;
                }
                if (Town::find($value->address->town_id)) {
                    $value->address->town = Town::find($value->address->town_id)->name;
                }
            }

            $row_contra = new OrderContact();
            $row_contra->user_id = $value->id;
            $row_contra->order_id = $order->id;
            $row_contra->save();
            $keynext = $key + 1;

            if ($request->contracts_id == 0) {
                $body_format = str_replace("[contraparte".$keynext."_nombre]", $value->name, $body_format);
                $body_format = str_replace("[contraparte".$keynext."_email]", $value->email, $body_format);
                $body_format = str_replace("[contraparte".$keynext."_telefono]", $value->phone, $body_format);
                $body_format = str_replace("[contraparte".$keynext."_rfc]", $value->rfc, $body_format);
                $body_format = str_replace("[contraparte".$keynext."_curp]", $value->curp, $body_format);
                if ($value->address != null) {
                    $body_format = str_replace("[contraparte".$keynext."_calle]", $value->address->calle, $body_format);
                    $body_format = str_replace("[contraparte".$keynext."_numext]", $value->address->num_ext, $body_format);
                    $body_format = str_replace("[contraparte".$keynext."_numint]", $value->address->num_int, $body_format);
                    $body_format = str_replace("[contraparte".$keynext."_colonia]", $value->address->neighborhood, $body_format);
                    $body_format = str_replace("[contraparte".$keynext."_cp]", $value->address->zipcode, $body_format);
                    $body_format = str_replace("[contraparte".$keynext."_ciudad]", $value->address->town, $body_format);
                    $body_format = str_replace("[contraparte".$keynext."_estado]", $value->address->state, $body_format);
                }

                $order->body = $body_format;
            }

            $name_contras = $name_contras.', '.$value->name;
            $email_contras = $email_contras.', '.$value->email;
            $phone_contras = $phone_contras.', '.$value->phone;
            $rfc_contras = $rfc_contras.', '.$value->rfc;
            $curp_contras = $curp_contras.', '.$value->curp;

            if ($value->address != null) {
                $calle_contras = $calle_contras.', '.$value->address->calle;
                $num_ext_contras = $num_ext_contras.', '.$value->address->num_ext;
                $num_int_contras = $num_int_contras.', '.$value->address->num_int;
                $neighborhood_contras = $neighborhood_contras.', '.$value->address->neighborhood;
                $zipcode_contras = $zipcode_contras.', '.$value->address->zipcode;
                $town_contras = $town_contras.', '.$value->address->town;
                $state_contras = $state_contras.', '.$value->address->state;
                $address_contras = $address_contras.', '.$value->address->calle.', '.$value->address->num_ext.', '.$value->address->num_int.', '.$value->address->neighborhood.', '.$value->address->zipcode.', '.$value->address->town.', '.$value->address->state;
            }
            
        }
        $order->header_format = $request->contractdata['header_format_preview'];
        $order->medio_format = $request->contractdata['medio_format_preview'];
        $order->inferior_format = $request->contractdata['inferior_format_preview'];
        $order->save();
        
        //guardamos las imagenes de referencia
        
        if (isset($request->images)) {
            if (sizeof($request->images) > 0) {
                foreach ($request->images as $key => $value) {
                    
                    $rowimage = new OrderImage();
                    $rowimage->orders_id = $order->id;
                    $rowimage->description = $value['comments'];
                    if (isset($value['image'])) {
                        
                        if ($value['image'] != null && $value['image'] != false && $value['image'] != 'false') {
                            $data = $value['image'];
                            list($type, $data) = explode(';', $data);
                            list(, $data)      = explode(',', $data);
                            $data = base64_decode($data);
                                        
                            Storage::disk('public')->put('imagestmp/imageone'.$order->id.$key.'.png',$data);
                            $dir = base_path().'/storage/app/public/imagestmp';
                            $file = 'imageone'.$order->id.$key.'.png';
                            $content = file_get_contents($dir.'/'. $file,  FILE_USE_INCLUDE_PATH);
                            if($content){
                                    Storage::disk('public')->put('photos/'.$file, $content);
                                    $image = new Image(array(
                                        "path"=> 'photos/'.$file,
                                        "disk"=> 'public',
                                        "key"=>uniqid(),
                                    ));
                                    $image->save(); 
                                $rowimage->images_id = $image->id;
                                
                            }
                        }
                    } 
                    $rowimage->save();
                 
                }
            }
        }
        $user = User::find($order->user_id);
        if (Images::getUrl($user->image_id_logo)) {
            $user->imageUrl = Images::getUrl($user->image_id_logo);
        }
        else{
            $user->imageUrl = 'https://notarionet.com/public/images/logo.png';
        }
        
        $user->signature_user_imageUrl = Images::getUrl($order->signature_user_id);
        $user->address;
        if(State::find($user->address->state_id)){
             $user->address->state = State::find($user->address->state_id)->name;
        }
        if(Town::find($user->address->town_id)){
            $user->address->town = Town::find($user->address->town_id)->name;

        }
        /* $user_contra = User::find($order->user_contra_id);
        $user_contra->signature_contra_imageUrl = Images::getUrl($order->signature_user_contra_id);
        $user_contra->address;
        if (State::find($user_contra->address->state_id)) {
            $user_contra->address->state = State::find($user_contra->address->state_id)->name;
        }
        if (Town::find($user_contra->address->town_id)) {
            $user_contra->address->town = Town::find($user_contra->address->town_id)->name;
        } */

        /*$header_format = null;
        $medio_format = null;
        $inferior_format = null;
        if ($request->contracts_id != 0 && $request->contracts_id != 999999999) {
            $contrat_data = Contract::find($order->contracts_id);
       
            $header_format = $contrat_data->header_format;
            $header_format = str_replace("[parte1_nombre]", $user->name, $header_format);
            $header_format = str_replace("[parte1_email]", $user->email, $header_format);
            $header_format = str_replace("[parte1_telefono]", $user->phone, $header_format);
            $header_format = str_replace("[parte1_rfc]", $user->rfc, $header_format);
            $header_format = str_replace("[parte1_curp]", $user->curp, $header_format);
            $header_format = str_replace("[parte1_calle]", $user->address->calle, $header_format);
            $header_format = str_replace("[parte1_numext]", $user->address->num_ext, $header_format);
            $header_format = str_replace("[parte1_numint]", $user->address->num_int, $header_format);
            $header_format = str_replace("[parte1_colonia]", $user->address->neighborhood, $header_format);
            $header_format = str_replace("[parte1_cp]", $user->address->zipcode, $header_format);
            $header_format = str_replace("[parte1_ciudad]", $user->address->town, $header_format);
            $header_format = str_replace("[parte1_estado]", $user->address->state, $header_format);

            $header_format = str_replace("[contraparte_nombre]", $name_contras, $header_format);
            $header_format = str_replace("[contraparte_email]", $email_contras, $header_format);
            $header_format = str_replace("[contraparte_telefono]", $phone_contras, $header_format);
            $header_format = str_replace("[contraparte_rfc]", $rfc_contras, $header_format);
            $header_format = str_replace("[contraparte_curp]", $curp_contras, $header_format);
            $header_format = str_replace("[contraparte_calle]", $calle_contras, $header_format);
            $header_format = str_replace("[contraparte_numext]", $num_ext_contras, $header_format);
            $header_format = str_replace("[contraparte_numint]", $num_int_contras, $header_format);
            $header_format = str_replace("[contraparte_colonia]", $neighborhood_contras, $header_format);
            $header_format = str_replace("[contraparte_cp]", $zipcode_contras, $header_format);
            $header_format = str_replace("[contraparte_ciudad]", $town_contras, $header_format);
            $header_format = str_replace("[contraparte_estado]", $state_contras, $header_format);
            $header_format = str_replace("[contraparte_domicilio]", $address_contras, $header_format);

            $medio_format = $contrat_data->medio_format;
            $medio_format = str_replace("[parte1_nombre]", $user->name, $medio_format);
            $medio_format = str_replace("[parte1_email]", $user->email, $medio_format);
            $medio_format = str_replace("[parte1_telefono]", $user->phone, $medio_format);
            $medio_format = str_replace("[parte1_rfc]", $user->rfc, $medio_format);
            $medio_format = str_replace("[parte1_curp]", $user->curp, $medio_format);
            $medio_format = str_replace("[parte1_calle]", $user->address->calle, $medio_format);
            $medio_format = str_replace("[parte1_numext]", $user->address->num_ext, $medio_format);
            $medio_format = str_replace("[parte1_numint]", $user->address->num_int, $medio_format);
            $medio_format = str_replace("[parte1_colonia]", $user->address->neighborhood, $medio_format);
            $medio_format = str_replace("[parte1_cp]", $user->address->zipcode, $medio_format);
            $medio_format = str_replace("[parte1_ciudad]", $user->address->town, $medio_format);
            $medio_format = str_replace("[parte1_estado]", $user->address->state, $medio_format);

            $medio_format = str_replace("[contraparte_nombre]", $name_contras, $medio_format);
            $medio_format = str_replace("[contraparte_email]", $email_contras, $medio_format);
            $medio_format = str_replace("[contraparte_telefono]", $phone_contras, $medio_format);
            $medio_format = str_replace("[contraparte_rfc]", $rfc_contras, $medio_format);
            $medio_format = str_replace("[contraparte_curp]", $curp_contras, $medio_format);
            $medio_format = str_replace("[contraparte_calle]", $calle_contras, $medio_format);
            $medio_format = str_replace("[contraparte_numext]", $num_ext_contras, $medio_format);
            $medio_format = str_replace("[contraparte_numint]", $num_int_contras, $medio_format);
            $medio_format = str_replace("[contraparte_colonia]", $neighborhood_contras, $medio_format);
            $medio_format = str_replace("[contraparte_cp]", $zipcode_contras, $medio_format);
            $medio_format = str_replace("[contraparte_ciudad]", $town_contras, $medio_format);
            $medio_format = str_replace("[contraparte_estado]", $state_contras, $medio_format);
            $medio_format = str_replace("[contraparte_domicilio]", $address_contras, $medio_format);

            /* $medio_format = str_replace("[parte2_nombre]", $user_contra->name, $medio_format);
            $medio_format = str_replace("[parte2_email]", $user_contra->email, $medio_format);
            $medio_format = str_replace("[parte2_telefono]", $user_contra->phone, $medio_format);
            $medio_format = str_replace("[parte2_rfc]", $user_contra->rfc, $medio_format);
            $medio_format = str_replace("[parte2_curp]", $user_contra->curp, $medio_format);
            $medio_format = str_replace("[parte2_calle]", $user_contra->address->calle, $medio_format);
            $medio_format = str_replace("[parte2_numext]", $user_contra->address->num_ext, $medio_format);
            $medio_format = str_replace("[parte2_numint]", $user_contra->address->num_int, $medio_format);
            $medio_format = str_replace("[parte2_colonia]", $user_contra->address->neighborhood, $medio_format);
            $medio_format = str_replace("[parte2_cp]", $user_contra->address->zipcode, $medio_format);
            $medio_format = str_replace("[parte2_ciudad]", $user_contra->address->town, $medio_format);
            $medio_format = str_replace("[parte2_estado]", $user_contra->address->state, $medio_format);*-/

            $inferior_format = $contrat_data->inferior_format;
            $inferior_format = str_replace("[parte1_nombre]", $user->name, $inferior_format);
            $inferior_format = str_replace("[parte1_email]", $user->email, $inferior_format);
            $inferior_format = str_replace("[parte1_telefono]", $user->phone, $inferior_format);
            $inferior_format = str_replace("[parte1_rfc]", $user->rfc, $inferior_format);
            $inferior_format = str_replace("[parte1_curp]", $user->curp, $inferior_format);
            $inferior_format = str_replace("[parte1_calle]", $user->address->calle, $inferior_format);
            $inferior_format = str_replace("[parte1_numext]", $user->address->num_ext, $inferior_format);
            $inferior_format = str_replace("[parte1_numint]", $user->address->num_int, $inferior_format);
            $inferior_format = str_replace("[parte1_colonia]", $user->address->neighborhood, $inferior_format);
            $inferior_format = str_replace("[parte1_cp]", $user->address->zipcode, $inferior_format);
            $inferior_format = str_replace("[parte1_ciudad]", $user->address->town, $inferior_format);
            $inferior_format = str_replace("[parte1_estado]", $user->address->state, $inferior_format);

            $inferior_format = str_replace("[contraparte_nombre]", $name_contras, $inferior_format);
            $inferior_format = str_replace("[contraparte_email]", $email_contras, $inferior_format);
            $inferior_format = str_replace("[contraparte_telefono]", $phone_contras, $inferior_format);
            $inferior_format = str_replace("[contraparte_rfc]", $rfc_contras, $inferior_format);
            $inferior_format = str_replace("[contraparte_curp]", $curp_contras, $inferior_format);
            $inferior_format = str_replace("[contraparte_calle]", $calle_contras, $inferior_format);
            $inferior_format = str_replace("[contraparte_numext]", $num_ext_contras, $inferior_format);
            $inferior_format = str_replace("[contraparte_numint]", $num_int_contras, $inferior_format);
            $inferior_format = str_replace("[contraparte_colonia]", $neighborhood_contras, $inferior_format);
            $inferior_format = str_replace("[contraparte_cp]", $zipcode_contras, $inferior_format);
            $inferior_format = str_replace("[contraparte_ciudad]", $town_contras, $inferior_format);
            $inferior_format = str_replace("[contraparte_estado]", $state_contras, $inferior_format);
            $inferior_format = str_replace("[contraparte_domicilio]", $address_contras, $inferior_format);

            /*$inferior_format = str_replace("[parte2_nombre]", $user_contra->name, $inferior_format);
            $inferior_format = str_replace("[parte2_email]", $user_contra->email, $inferior_format);
            $inferior_format = str_replace("[parte2_telefono]", $user_contra->phone, $inferior_format);
            $inferior_format = str_replace("[parte2_rfc]", $user_contra->rfc, $inferior_format);
            $inferior_format = str_replace("[parte2_curp]", $user_contra->curp, $inferior_format);
            $inferior_format = str_replace("[parte2_calle]", $user_contra->address->calle, $inferior_format);
            $inferior_format = str_replace("[parte2_numext]", $user_contra->address->num_ext, $inferior_format);
            $inferior_format = str_replace("[parte2_numint]", $user_contra->address->num_int, $inferior_format);
            $inferior_format = str_replace("[parte2_colonia]", $user_contra->address->neighborhood, $inferior_format);
            $inferior_format = str_replace("[parte2_cp]", $user_contra->address->zipcode, $inferior_format);
            $inferior_format = str_replace("[parte2_ciudad]", $user_contra->address->town, $inferior_format);
            $inferior_format = str_replace("[parte2_estado]", $user_contra->address->state, $inferior_format); *-/
        }
        else{

        }*/

        
         //ACA

        $contract = [
            'title'=>$request->contractdata['title'],
            'title_url'=>str_replace(' ','-',$request->contractdata['title']),
            'contracts_id'=>$request->contracts_id,
            'body'=>$order->body,
            'stamp'=>$order->stamp,
            'certificate'=>$order->certificate,
            'date_signature_user'=>$order->date_signature_user,
            //'date_signature_user_contra'=>$order->date_signature_user_contra,
            'created'=>$order->created_at->format('d-m-Y H:i:s'),
            'clauses'=>$clauses_detail,
            
            'header_format'=>$order->header_format,
            'medio_format'=>$order->medio_format,
            'inferior_format'=>$order->inferior_format,
        ];

       
        
        
        $url_code = "https://notarionet.com/#/contrato/".$order->id;

        $images = OrderImage::where('orders_id',$order->id)->get();
        foreach ($images as $key => $value) {
            $value->imageUrl = Images::getUrl($value->images_id);
        }
        
        $inputs = [
            'order_id'=>$order->id,
            'user'=>$user,
            'users_contras'=>$users_contras,
            'contract'=>$contract,
            'images'=>$images,
            'qr' => QrCode::format('png')->size(150)->margin(0)->generate($url_code),
            'cancel'=> 0
        ];
        
        $pdf = $this->createPDF($inputs);
        
        if($pdf != 'error'){
            if ($contractdata != null) {

                if ($contractdata->plain_receipt == 1) {
                    try{
                        Mail::to($inputs['user']['email'])->send(new ContractComplete( $inputs, $pdf));
                    
                    }catch (\Exception $e) {
                        //report($e);
                    }
                }
                else{
                    try{
                        Mail::to($inputs['user']['email'])->send(new ContractMail( $inputs, $pdf));
                        foreach ($users_contras as $key => $value) {
                            $inputs['user_contra'] = $value;
                            Mail::to($value->email)->send(new ContractContraMail( $inputs,$pdf));
                        }
                    }catch (\Exception $e) {
                        //report($e);
                    }
                }
                //se resta los creditos
                $restcredits = User::find($order->user_id);
                $restcredits->credits = $restcredits->credits - $contractdata->price;
                $restcredits->save();
            }
            else{
                try{
                    Mail::to($inputs['user']['email'])->send(new ContractMail( $inputs, $pdf));
                    foreach ($users_contras as $key => $value) {
                        $inputs['user_contra'] = $value;
                        Mail::to($value->email)->send(new ContractContraMail( $inputs,$pdf));
                    }
                }catch (\Exception $e) {
                    //report($e);
                }
                //se resta los creditos
                $restcredits = User::find($order->user_id);
                $restcredits->credits = $restcredits->credits - 1;
                $restcredits->save();
            }
            


            $data = Order::find($order->id);
            $data->documentUrl = Documents::getUrl($data->documents_id);
            $data->created = $data->created_at->format('d-m-Y H:i:s');
            
            
            return response()->json($data);
        }
        else{
            return response()->json(['msg'=>'Ocurrio un error al guardar'],500);
        }

    }
    public function saveOrderImage(Request $request)
    {
       
        ini_set('memory_limit', '-1');
        /*$pdf_data_string = '<body>';
        if ($request->file('imagecertificate')->getClientOriginalExtension() == 'pdf') {
            $pdf = new Pdfguffy($request->file('imagecertificate'));
            $pdf_data = $pdf->html();
            
            foreach ($pdf_data->contents as $key => $value) {

                $string_aux = null;
                $pos = strpos($value,'<body');
                $posend = strpos($value,'</body>');
                $string_aux = substr($value,$pos,$posend - ($pos - 8));
                $string_aux = str_replace('<body bgcolor="#A0A0A0" vlink="blue" link="blue">','<div>',$string_aux);
                $string_aux = str_replace('</body>','</div>',$string_aux);
                $string_aux = str_replace('alt="background image"','style="display:none"',$string_aux);
                    
                $pdf_data_string = $pdf_data_string.'<br>'.$string_aux;
            }
           
        }
        else{

        }
        $pdf_data_string = $pdf_data_string.'</body>';*/
        $dataform = json_decode($request->form);
        
        $userdata = Auth::user();
        if (Images::getUrl($userdata->image_id_logo)) {
            $userdata->imageUrl = Images::getUrl($userdata->image_id_logo);
        }
        else{
            $userdata->imageUrl = 'https://notarionet.com/public/images/logo.png';
        }

        $order = new Order();
        $order->no_signature_creator = $dataform->no_signature_creator;
        $order->type_contracts_id = $dataform->type_contracts_id;
        $order->total = 0;
        $order->subtotal = 0;
        $order->contracts_id = $dataform->contracts_id;
        $order->user_id = $userdata->id;
        $order->signature_user_id = $userdata->signature_image_id;
        $order->status = 'completado';
        $order->stamp = Str::random(20);
        $order->certificate = Str::random(20);
        $order->date_signature_user = date('Y-m-d H:i:s');

        $type = null;
        if ($request->file('imagecertificate')->getClientOriginalExtension() == 'pdf') {
            $order->pdf_id = Documents::save($request->file('imagecertificate'));
            $order->pdf_name = $request->file('imagecertificate')->getClientOriginalName();
            $order->body = null;//$pdf_data_string;
            $type = 'pdf';
        }
        else{
            $order->images_id = Images::save($request->file('imagecertificate'));
            $order->image_name = $request->file('imagecertificate')->getClientOriginalName();
            $type = 'image';
        }
        
        
        $order->save();
        $users_contras = [];
        if ($order->contracts_id == 888899999) {
            //usuarios contrarios
            $users_contras = User::whereIn('id',$dataform->contacts)->get();
            foreach ($users_contras as $key => $value) {
            
                $row_contra = new OrderContact();
                $row_contra->user_id = $value->id;
                $row_contra->order_id = $order->id;
                $row_contra->save();
            }
        }
        

        


        $url_code = "https://notarionet.com/#/contrato/".$order->id;

        $inputs = [
            'order_id'=>$order->id,
            'user'=>$userdata,
            'imageUrl'=>Images::getUrl($order->images_id),
            'qr' => QrCode::format('png')->size(150)->margin(0)->generate($url_code),
            'stamp'=>$order->stamp,
            'certificate'=>$order->certificate,
            'date_signature_user'=>$order->date_signature_user,
            'created'=>$order->created_at->format('d-m-Y H:i:s'),
            'cancel'=> 0,
            'type' => $type,
            'body'=>$order->body,
            'users_contras'=>$users_contras,
            'contract' => [
                'title'=>'Certifica una imagen o documento existente'
            ],
            'contracts_id'=>$order->contracts_id
        ];
        
        $pdf = $this->createPDFImage($inputs);

        if($pdf != 'error'){
            if ($order->contracts_id == 888888888) {
                try{
                    Mail::to($inputs['user']['email'])->send(new ContractComplete( $inputs, $pdf));
                
                }catch (\Exception $e) {
                    //report($e);
                }
            }
            else{
                try{
                    Mail::to($inputs['user']['email'])->send(new ContractMail( $inputs, $pdf));
                    foreach ($users_contras as $key => $value) {
                        $inputs['user_contra'] = $value;
                        Mail::to($value->email)->send(new ContractContraMail( $inputs,$pdf));
                    }
                }catch (\Exception $e) {
                    //report($e);
                }
            }

            /*try{
            Mail::to($inputs['user']['email'])->send(new ContractComplete( $inputs, $pdf));
                
            }catch (\Exception $e) {
                //report($e);
            }*/

            //se resta los creditos
            $restcredits = User::find($order->user_id);
            $restcredits->credits = $restcredits->credits - 1;
            $restcredits->save();
            
            $data = Order::find($order->id);
            $data->documentUrl = Documents::getUrl($data->documents_id);
            $data->created = $data->created_at->format('d-m-Y H:i:s');
            
            return response()->json($data);
        }
        else{
            return response()->json(['msg'=>'Ocurrio un error al guardar'],500);
        }
        
        

    }
    public function createPDF($inputs)
    {
      ini_set('memory_limit', '-1');

      $pdf = PDF::loadView('plantillas.contrato',['inputs' => $inputs]);
      $content = $pdf->download()->getOriginalContent();

      
      // Crear el archivo y almacenarlo en el storage
      Storage::disk('public')->put('docs/contrato-'.$inputs['order_id'].'.pdf',$content);

      //Crear el registro del documento y guardar el id en el contrato
      $doc = new Document(array(
        "path"=>'docs/contrato-'.$inputs['order_id'].'.pdf',
        "disk"=>'public',
        "key"=>uniqid()
      ));

      $doc->save();
    
        //guardamos y obtenemos el ID de multilateral
        $multilateral = $this->saveSeguridata($inputs['order_id'],( sizeof($inputs['users_contras']) + 1));
        
        if ($multilateral != 'error') {
            $updateorder = Order::find($inputs['order_id']);
            $updateorder->documents_id = $doc->id;

            $updateorder->multilateralId = $multilateral['multilateralId'];
            $updateorder->save();

            //enviamos la firma y obtenemos el hash desde java

            if ($updateorder->no_signature_creator == 0) {
                $hassh = [];
                $contractdata = Contract::find($updateorder->contracts_id); 
                if ($contractdata) {
                    if ($contractdata->plain_receipt == 1) {
                        $hassh = $this->getHash($inputs['order_id'],$multilateral['multilateralId'],'false');
                    }
                    else{
                        $hassh = $this->getHash($inputs['order_id'],$multilateral['multilateralId'],'true');
                    }
                    
                }
                else{
                    $hassh = $this->getHash($inputs['order_id'],$multilateral['multilateralId'],'true');
                }
                
                    
                $content_bin = base64_decode($hassh['pdf_base64'], true);
                Storage::disk('public')->put('docs/contrato-'.$inputs['order_id'].'.pdf',$content_bin);
                
                if ($hassh['nom_base64'] != null) {
                    $updateorder->nom_base64 = $hassh['nom_base64'];
                    $updateorder->save();
                    $contentnom_bin = base64_decode($hassh['nom_base64'], true);
                    Storage::disk('public')->put('docs/contrato-nom'.$inputs['order_id'].'.nom',$contentnom_bin);
    
                    $pdfnom = PDF::loadView('plantillas.nomdata',['inputs' => $hassh['datanom']]);
                    $contentnom = $pdfnom->download()->getOriginalContent();
                    // Crear el archivo y almacenarlo en el storage
                    Storage::disk('public')->put('docs/contrato-nom-data'.$inputs['order_id'].'.pdf',$contentnom);
    
    
                }
                return $content_bin;
            }
            else{
                return $content;
            }
           
            
        }
        else{
            return 'error';
        }
    }

    public function createPDFImage($inputs)
    {
      ini_set('memory_limit', '-1');
        if ($inputs['type'] == 'pdf') {
            $oderinf = Order::find($inputs['order_id']);
            $docinfo = Document::find($oderinf->pdf_id);

            $doc_content = file_get_contents('https://notarionet.com/storage/app/public/'.$docinfo->path);

            Storage::disk('public')->put('docs/contrato-'.$inputs['order_id'].'.pdf',$doc_content);

            //Crear el registro del documento y guardar el id en el contrato
            $doc = new Document(array(
                "path"=>'docs/contrato-'.$inputs['order_id'].'.pdf',
                "disk"=>'public',
                "key"=>uniqid()
            ));

            $doc->save();
        }
        else{
            $pdf = PDF::loadView('plantillas.contratoimage',['inputs' => $inputs]);
            $content = $pdf->download()->getOriginalContent();
            // Crear el archivo y almacenarlo en el storage
            Storage::disk('public')->put('docs/contrato-'.$inputs['order_id'].'.pdf',$content);

            //Crear el registro del documento y guardar el id en el contrato
            $doc = new Document(array(
                "path"=>'docs/contrato-'.$inputs['order_id'].'.pdf',
                "disk"=>'public',
                "key"=>uniqid()
            ));

            $doc->save();
        }
      

      
      

        //guardamos y obtenemos el ID de multilateral
        $multilateral = $this->saveSeguridata($inputs['order_id'],( sizeof($inputs['users_contras']) + 1));
        if ($multilateral != 'error') {
            $updateorder = Order::find($inputs['order_id']);
            $updateorder->documents_id = $doc->id;
            $updateorder->multilateralId = $multilateral['multilateralId'];
            $updateorder->save();

            if ($updateorder->no_signature_creator == 0) {
                //enviamos la firma y obtenemos el hash desde java
                if ($inputs['contracts_id'] == 888888888) {
                        $hassh = $this->getHash($inputs['order_id'],$multilateral['multilateralId'],'false');
                }
                else{
                        $hassh = $this->getHash($inputs['order_id'],$multilateral['multilateralId'],'true');
                }
                
                    
                $content_bin = base64_decode($hassh['pdf_base64'], true);
                Storage::disk('public')->put('docs/contrato-'.$inputs['order_id'].'.pdf',$content_bin);

                if ($hassh['nom_base64'] != null) {
                        $updateorder->nom_base64 = $hassh['nom_base64'];
                        $updateorder->save();
                        $contentnom_bin = base64_decode($hassh['nom_base64'], true);
                        Storage::disk('public')->put('docs/contrato-nom'.$inputs['order_id'].'.nom',$contentnom_bin);


                        $pdfnom = PDF::loadView('plantillas.nomdata',['inputs' => $hassh['datanom']]);
                        $contentnom = $pdfnom->download()->getOriginalContent();
                        // Crear el archivo y almacenarlo en el storage
                        Storage::disk('public')->put('docs/contrato-nom-data'.$inputs['order_id'].'.pdf',$contentnom);

                    }
                

                return $content_bin;
            }
            else{
                if ($inputs['type'] == 'pdf') {
                    return $doc_content;
                }
                else{
                    return $content;
                }
            }
        }
        else{
            return 'error';
        }
    }

    public function validateOrder($id)
    {
        $order = Order::find($id);
        $order->created = $order->created_at->format('d-m-Y H:i:s');
        $order->documentUrl = Documents::getUrl($order->documents_id);

        $order->documentUrlNom = 'https://notarionet.com/storage/app/public/docs/contrato-nom'.$order->id.'.nom';
        

        return response()->json($order);
    }

    public function saveSeguridata($order_id,$totalSigners)
    {
        //obtenemos el pdf en base 64 para  enviarlo
        $b64Doc = chunk_split(base64_encode(file_get_contents('https://notarionet.com/storage/app/public/docs/contrato-'.$order_id.'.pdf')));
        
        //obtenemos el token
        $response_token = Http::withoutVerifying()->post('https://cloudsign.seguridata.com:4444/sign_rest_ccpunto/user?password=44y.Punt0&user=ccpunto')->json();
            
        if ($response_token['enabled'] == true) {

            //enviamos el documento
            $response = Http::withoutVerifying()->withHeaders([ 
                'authorization' => $response_token['token'],
            ])->post('https://cloudsign.seguridata.com:4444/sign_rest_ccpunto/multilateral/', [
                'data' => 'contrato-'.$order_id.'.pdf',
                'document2Sign' => [
                    'base64' => true,
                    'data' => $b64Doc,
                    'name' => 'contrato-'.$order_id.'.pdf',
                ],
                'hashAlg' => 'SHA256',
                'pdfPassword' => '',
                'processType' => 'PDF',
                'totalSigners' => $totalSigners,
            ])->json();
            \Log::error( 'Guarda en seguridata: '.json_encode($response) );
            if (isset($response['multilateralId'])) {
                return($response);
            }
            else{
                $conts_del = OrderContact::where('order_id',$order_id)->delete();
                $orderdel = Order::find($order_id);
                $orderdel->delete();
                \Log::error( 'Error al guarda en seguridata: ID:'.$order_id );
                return 'error';
            }
           
            
        }
        else{
            \Log::error( 'Error al guarda en seguridata no hay login en seguridata: ID:'.$order_id );
            $conts_del = OrderContact::where('order_id',$order_id)->delete();
            $orderdel = Order::find($order_id);
            $orderdel->delete();
            return 'error';
            
        }

    }

    public function getHash($order_id,$multilateral_id,$finalize)
    {
        $orderdata = Order::find($order_id);
        $user = User::find($orderdata->user_id);
        
       
        $points_signature = str_replace('\"','"',$user->points_signature);
        $data_points = []; 
        $points_signature_array = explode('","',$points_signature);
        foreach ($points_signature_array as $key => $value) {
            $value_array = explode(',',$value);
            $array_x = explode(':',$value_array[0]);
            $array_y = explode(':',$value_array[1]);
            $array_t = explode(':',$value_array[2]);
            $array_p = explode(':',$value_array[3]);

            $aux = [
                'x' => $array_x[1],
                'y' => $array_y[1],
                't' => $array_t[1],
                'p' => '0',
            ];
            array_push($data_points,$aux);
        }
        
        //obtenemos el token
        $response_token = Http::withoutVerifying()->post('https://cloudsign.seguridata.com:4444/sign_rest_ccpunto/user?password=44y.Punt0&user=ccpunto')->json();
        
        if ($response_token['enabled'] == true) {
            
            //enviamos el documento
            $response = Http::withoutVerifying()->withHeaders([ 
                'authorization' => $response_token['token'],
            ])->post('https://cloudsign.seguridata.com:4444/sign_rest_ccpunto/multilateral/getHash/'.$multilateral_id, [
                'biometricData'=> [
                    'aspectRatio'=> [
                        'height'=> 250,
                        'width'=> 450
                    ],
                    'biometricTool'=> 'FINGER',
                    'collectedBioParameters'=> [
                        'p'=> false,
                        't'=> true,
                        'x'=> true,
                        'y'=> true
                    ],
                    
                    //'date'=> date(DATE_ATOM),
                    'date'=> '2023-01-24T10:28:08.000Z+0000',
                    'id'=> $multilateral_id,
                    'signatureValues'=> $data_points
                ],
                'idKey'=> 1,
                'location'=> null,
                'signatureImage'=> [
                    'base64'=> true,
                    'data'=> $user->signature_base64,
                    'evidenceType'=> 'SIGNATURE'
                ],
                'signatureReason'=> null,
                'signerCertificate'=> [
                    'base64'=> true,
                    'data'=> 'MIIFVDCCAzygAwIBAgIUMDAwMDAwMDAwMDAwMDAwMDkyNzIwDQYJKoZIhvcNAQELBQAwgfMxFjAUBgNVBC0DDQBTUFI5NjEyMTdOSzkxFzAVBgNVBAcTDkFsdmFybyBPYnJlZ29uMQ0wCwYDVQQIEwRDRE1YMQswCQYDVQQGEwJNWDEOMAwGA1UEERMFMDEwMDAxHTAbBgNVBAkTFEluc3VyZ2VudGVzIFN1ciAyMzc1MSgwJgYDVQQDEx9BdXRvcmlkYWQgQ2VydGlmaWNhZG9yYSBJbnRlcm5hMSQwIgYDVQQKExtTZWd1cmlkYXRhIFByaXZhZGEgU0EgZGUgQ1YxJTAjBgkqhkiG9w0BCQEWFnNvcG9ydGVAc2VndXJpZGF0YS5jb20wHhcNMjMwMjAxMDAwMDAwWhcNMjcwMjI4MDAwMDAwWjA7MQswCQYDVQQGEwJNWDEVMBMGA1UEAwwMRkFCXzQ0eVB1bnRvMRUwEwYDVQQKDAxGQUJfNDR5UHVudG8wggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQDoc2J4BQLtR9TTk0kJDecYKRhzbMH7gy80qhfLCqNcImVI09WmcynBt0Txrmt/6+YWe1n1jXYZYMxh2+FsQESVHT1H1FN0GswFrpXAlmZzIArHUb3920ObjFji52+lIJgAR9THB/FbOITE955hxnL2/NGil/V24c8jrSot6PgTSG28kde+8qjTRUWO7lxi7AqVHYi2mdo7OWQNHwVek2jDitFOz79mxyxvoDGqZlL3b+sivxDS82FEmezxGth2OYsw+6PIjs0I5fh94SS7IyeTr7fX5+dI+6XGN4lJH/pp8+bxOYqwh4pP5ag4t+rADZUgq/k1x/aC+BmA5XaLDY0LAgMBAAGjgZYwgZMwHwYDVR0jBBgwFoAUEqUtMn3fXoeKw9Iev4MlBx2lFAUwHQYDVR0OBBYEFMR3kwyh4GlKJv+Cpy6Y5XtSt0SVMB0GA1UdJQQWMBQGCCsGAQUFBwMEBggrBgEFBQcDAjAPBgNVHRMBAf8EBTADAgEAMA4GA1UdDwEB/wQEAwID6DARBglghkgBhvhCAQEEBAMCBaAwDQYJKoZIhvcNAQELBQADggIBAI2OqTOixVoulO4kPjbyX+hta5qZVYvoBAAB1i8gpfExFAPc/adlwQPBtfUIh0SiZ2fUUlRW780xUM28DYX+UWjfXoc2Fna7zSw8BrGdpUrRBa18T/Ga1gnze91bW5bE7cRI6tmRsexDkcy+a7ZC47giz3CnRvGnbN+9lRpjYCMIJcFdUip6ci404T6JJZXRn0ANQ6jjBoE7uHOyK6tpPpTfGIZ6nnj0GgcQRoqyYaQpG38OcH8SKn9J/zyOEVyD+odTj/dp+Wm7Sx8fWDP80a5QKX707Ktzeloki9s2JTENuqO0ogqAI5Yc+19Pczwrlf71SUKy3TDgyQbPFEPmK7qLRhTFsfBKbYZ48Hgpm5jzDiqX5nt2IysT0cn4xGsq1URY3w1ioYC1S3dEpVQJ0s6N8A7cTkxnBeqCfdPmZpreQuBfW725Iuey/9ZNa/KgjwDExWJZgztSxoBMFq+Ko2YXf659h14JhU8Fah3zjrEiaFiFPP54wQNIaMJpTE2PfQb2/75JwZwXmI5wSANJ4/XxJQoWyeDyc2CfHgAo1mJ0V43fu0lubuduSnsDN5Ja5d82LIClLVoa8vSGX7HnD4FOzly5fRKJdC7mG13pD3rOLGYhVpp5HJefvJiF+Qb1szEZC30/lsKiJHEEsdfGo4p1o6aJLAe7XBrIHvFhPX97',
                    'evidenceType'=> 'CERTIFICATE'
                ],
                'signerName'=> $user->name.' '.$user->lastname,
            ])->json();
            
            \Log::error( 'Respuesta primer multilateral: '.json_encode($response) );

            $updateorderaux = Order::find($order_id);
            $updateorderaux->signature_hexHash = $response['hexHash'];
            $updateorderaux->signature_hash = $response['hash'];
            $updateorderaux->save();
            $datareturn = [
                'signature_hexHash' => $response['hexHash'],
                'signature_hash' => $response['hash'],
                'java_hash' => null,
                'sequence' => null,
                'pdf_base64' => null
            ];

            $tohash = $response['hexHash'];
            //estos cambiaran a prodcutivo
            $key_file = 'seguridata/files/system.key';
            $pass_file = '12121212Qw.';
            $cer_file = 'seguridata/files/system.cer';
            $output = null;
           
            ini_set('memory_limit',-1);
            exec('java -jar seguridata/SgSignSignerAPIv2.6.1_C.jar  '.$tohash.' '.$key_file.' '.$pass_file.' '.$cer_file, $output);
            
            

            if (isset($output[13])) {
                $datareturn['java_hash'] = $output[13];
                $updateorderaux->java_hash = $output[13];

                $response_update = Http::withoutVerifying()->withHeaders([ 
                    'authorization' => $response_token['token'],
                ])->post('https://cloudsign.seguridata.com:4444/sign_rest_ccpunto/multilateral/update/'.$multilateral_id, [
                    'serial'=> '3030303030303030303030303030303039323732',
                    'signedMessage'=> [
                      'base64'=> true,
                      'data'=> $output[13],
                      'name'=> 'signature.p7'
                      ]
                ])->json();
                
                $updateorderaux->sequence = $response_update['sequence'];
                $updateorderaux->save();

                $datareturn['sequence'] = $response_update['sequence'];

                //obtenemos el pdf ya firmado  en base 64
                if($finalize == 'true'){
                    $response_finalize = Http::withoutVerifying()->withHeaders([ 
                        'authorization' => $response_token['token'],
                    ])->get('https://cloudsign.seguridata.com:4444/sign_rest_ccpunto/multilateral/finalize/'.$multilateral_id.'/'.$finalize, [
        
                    ])->json();
                    
                    $datareturn['pdf_base64'] = $response_finalize[0]['data'];
                    $datareturn['nom_base64'] = null;
                }
                else{
                    $response_finalize = Http::withoutVerifying()->withHeaders([ 
                        'authorization' => $response_token['token'],
                    ])->get('https://cloudsign.seguridata.com:4444/sign_rest_ccpunto/multilateral/finalize/'.$multilateral_id.'/'.$finalize, [
        
                    ])->json();
                    
                    $datareturn['pdf_base64'] = $response_finalize[0]['data'];
                    $datareturn['nom_base64'] = $response_finalize[1]['data'];

                    $datanom = Http::withHeaders([ 
                        'Content-Type' => 'application/json',
                    ])->withBasicAuth('44ypunto', '44yPunT0.23')
                    ->post('https://validador.seguridata.com:8443/SNomRestAPI/2016/getConstanciaNOM151Data', [
                        'value'=> $datareturn['nom_base64'],
                    ])->json();

                    $datareturn['datanom'] = $datanom['data'];
                    
                }
                

                
            }
            
           
            return($datareturn);
        }
    }

     public function testlogin()
    {
       $response_token = Http::withoutVerifying()->post('https://cloudsign.seguridata.com:4444/sign_rest_ccpunto/sign_rest_ccpunto/user?password=44y.Punt0&user=ccpunto')->json();
       dd($response_token);
    }

}
