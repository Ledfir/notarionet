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
use App\Models\UsersCredit;

use App\Mail\ContractContraMail;
use App\Mail\ContractMail;
use App\Mail\ContractComplete;
use App\Mail\NewCredits;
use App\Mail\SendEmailOrderdone;
use App\Mail\ContractUpdateMail;
use App\Models\Image;

use Smalot\PdfParser\Parser;
use Gufy\PdfToHtml\Pdf as Pdfguffy;

use Illuminate\Support\Facades\Http;

use Documents;
use Images;

class EmailsAppController extends Controller
{
    public function newOrder($id)
    {
        ini_set('memory_limit',-1);
        $order = Order::find($id);
        $contractdata = Contract::find($order->contracts_id);
        
        $contaacts_id = OrderContact::where('order_id',$id)->pluck('user_id');
        //usuario que crea el contrato
        $user = User::find($order->user_id);
        if (Images::getUrl($user->image_id_logo)) {
            $user->imageUrl = Images::getUrl($user->image_id_logo);
        }
        else{
            $user->imageUrl = 'https://notarionet.com/public/images/logo.png';
        }
        
        $clauses_detail = [];
        $details = OrderDetail::where('order_id',$order->id)->get();
        
        foreach ($details as $key => $value) {
            $clausu = ContractClause::find($value->contract_clauses_id);
            if ($clausu) {
                $value->title = $clausu->title;
                array_push($clauses_detail,$value);
            }
            
        }

        $user->signature_user_imageUrl = Images::getUrl($order->signature_user_id);
        $user->address;
        if($user->address != null){
            if(State::find($user->address->state_id)){
                $user->address->state = State::find($user->address->state_id)->name;
            }
            if(Town::find($user->address->town_id)){
                $user->address->town = Town::find($user->address->town_id)->name;
            }
        } 

        //obtenemos los usuarios contrarios
        $users_contras = User::whereIn('id',$contaacts_id)->get();
        
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
        $users_contras_ids = OrderContact::where('order_id',$id)->pluck('user_id');
     
        $users_contras = User::whereIn('id',$users_contras_ids)->get();

        foreach ($users_contras as $key => $value) {
            $cot = OrderContact::where('order_id',$id)->where('user_id',$value->id)->first();
            if ($cot->signature_user_contra_id != null) {
                $value->signature_contra_imageUrl = Images::getUrl($value->signature_image_id);
                $value->date_signature_user = $cot->date_signature_user;
            }
            
            $value->address;
            if($value->address != null){
                if (State::find($value->address->state_id)) {
                    $value->address->state = State::find($value->address->state_id)->name;
                }
                if (Town::find($value->address->town_id)) {
                    $value->address->town = Town::find($value->address->town_id)->name;
                }
            }

            $name_contras = $name_contras.', '.$value->name;
            $email_contras = $email_contras.', '.$value->email;
            $phone_contras = $phone_contras.', '.$value->phone;
            $rfc_contras = $rfc_contras.', '.$value->rfc;
            $curp_contras = $curp_contras.', '.$value->curp;
        
            if ($user->address != null) {

                if (isset($value->address->calle)) {
                    $calle_contras = $calle_contras.', '.$value->address->calle;
                }
                if (isset($value->address->num_ext)) {
                    $num_ext_contras = $num_ext_contras.', '.$value->address->num_ext;
                }
                if (isset($value->address->num_in)) {
                    $num_int_contras = $num_int_contras.', '.$value->address->num_int;
                }
                if (isset($value->address->neighborhood)) {
                    $neighborhood_contras = $neighborhood_contras.', '.$value->address->neighborhood;
                }
                if (isset($value->address->zipcode)) {
                    $zipcode_contras = $zipcode_contras.', '.$value->address->zipcode;
                }
                if (isset($value->address->town)) {
                    $town_contras = $town_contras.', '.$value->address->town;
                }
                if (isset($value->address->state)) {
                    $state_contras = $state_contras.', '.$value->address->state;
                }

                if (isset($value->address->calle) ) {
                    $address_contras = $address_contras.', '.$value->address->calle;
                }
                if (isset($value->address->num_ext)) {
                    $address_contras = $address_contras.', '.$value->address->num_ext;
                }
                if (isset($value->address->num_int)) {
                    $address_contras = $address_contras.', '.$value->address->num_int;
                }
                if (isset($value->address->neighborhood)) {
                    $address_contras = $address_contras.', '.$value->address->neighborhood;
                }
                if (isset($value->address->zipcode)) {
                    $address_contras = $address_contras.', '.$value->address->zipcode;
                }
                if (isset($value->address->town)) {
                    $address_contras = $address_contras.', '.$value->address->town;
                }
                if (isset($value->address->state)) {
                    $address_contras = $address_contras.', '.$value->address->state;
                }
                
                
               
                
                
                //$address_contras = $address_contras.', '.$value->address->calle.', '.$value->address->num_ext.', '.$value->address->num_int.', '.$value->address->neighborhood.', '.$value->address->zipcode.', '.$value->address->town.', '.$value->address->state;
              
            }
            
        }

        //obtenemos las clausulas y el header,medio e inferior
        //AQUI
        $header_format = null;
        $medio_format = null;
        $inferior_format = null;
        if ($order->contracts_id != 0 && $order->contracts_id != 999999999) {
           $contrat_data = Contract::find($order->contracts_id);
       
           $header_format = $contrat_data->header_format;
           $header_format = str_replace("[parte1_nombre]", $user->name, $header_format);
           $header_format = str_replace("[parte1_email]", $user->email, $header_format);
           $header_format = str_replace("[parte1_telefono]", $user->phone, $header_format);
           $header_format = str_replace("[parte1_rfc]", $user->rfc, $header_format);
           $header_format = str_replace("[parte1_curp]", $user->curp, $header_format);

           if ($user->address != null) {
                if (isset($user->address->calle)) {
                    $header_format = str_replace("[parte1_calle]", $user->address->calle, $header_format);
                }
                if (isset($user->address->num_ext)) {
                    $header_format = str_replace("[parte1_numext]", $user->address->num_ext, $header_format);
                }
                if (isset($user->address->num_int)) {
                    $header_format = str_replace("[parte1_numint]", $user->address->num_int, $header_format);
                }
                if (isset($user->address->neighborhood)) {
                    $header_format = str_replace("[parte1_colonia]", $user->address->neighborhood, $header_format);
                }
                if (isset($user->address->zipcode)) {
                    $header_format = str_replace("[parte1_cp]", $user->address->zipcode, $header_format);
                }
                if (isset($user->address->town)) {
                    $header_format = str_replace("[parte1_ciudad]", $user->address->town, $header_format);
                }
                if (isset($user->address->state)) {
                    $header_format = str_replace("[parte1_estado]", $user->address->state, $header_format);
                }
                   
           }
           

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
           if ($user->address != null) {
            
                if (isset($user->address->calle)) {
                    $medio_format = str_replace("[parte1_calle]", $user->address->calle, $medio_format);
                }
                if (isset($user->address->num_ext)) {
                    $medio_format = str_replace("[parte1_numext]", $user->address->num_ext, $medio_format);
                }
                if (isset($user->address->num_int)) {
                    $medio_format = str_replace("[parte1_numint]", $user->address->num_int, $medio_format);
                }
                if (isset($user->address->neighborhood)) {
                    $medio_format = str_replace("[parte1_colonia]", $user->address->neighborhood, $medio_format);
                }
                if (isset($user->address->zipcode)) {
                    $medio_format = str_replace("[parte1_cp]", $user->address->zipcode, $medio_format);
                }
                if (isset($user->address->town)) {
                    $medio_format = str_replace("[parte1_ciudad]", $user->address->town, $medio_format);
                }
                if (isset($user->address->state)) {
                    $medio_format = str_replace("[parte1_estado]", $user->address->state, $medio_format);
                }

           }

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

           $inferior_format = $contrat_data->inferior_format;
           $inferior_format = str_replace("[parte1_nombre]", $user->name, $inferior_format);
           $inferior_format = str_replace("[parte1_email]", $user->email, $inferior_format);
           $inferior_format = str_replace("[parte1_telefono]", $user->phone, $inferior_format);
           $inferior_format = str_replace("[parte1_rfc]", $user->rfc, $inferior_format);
           $inferior_format = str_replace("[parte1_curp]", $user->curp, $inferior_format);

           if ($user->address != null) {

                if (isset($user->address->calle)) {
                    $inferior_format = str_replace("[parte1_calle]", $user->address->calle, $inferior_format);
                }
                if (isset($user->address->num_ext)) {
                    $inferior_format = str_replace("[parte1_numext]", $user->address->num_ext, $inferior_format);
                }
                if (isset($user->address->num_int)) {
                    $inferior_format = str_replace("[parte1_numint]", $user->address->num_int, $inferior_format);
                }
                if (isset($user->address->neighborhood)) {
                    $inferior_format = str_replace("[parte1_colonia]", $user->address->neighborhood, $inferior_format);
                }
                if (isset($user->address->zipcode)) {
                    $inferior_format = str_replace("[parte1_cp]", $user->address->zipcode, $inferior_format);
                }
                if (isset($user->address->town)) {
                    $inferior_format = str_replace("[parte1_ciudad]", $user->address->town, $inferior_format);
                }
                if (isset($user->address->state)) {
                    $inferior_format = str_replace("[parte1_estado]", $user->address->state, $inferior_format);
                }

           }
           

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
        }
        else{

        }

        
       
        $contract = [
            'title'=>$contractdata->title,
            'title_url'=>str_replace(' ','-',$contractdata->title),
            'contracts_id'=>$id,
            'body'=>$order->body,

            'stamp'=>$order->stamp,
            'certificate'=>$order->certificate,
            'date_signature_user'=>$order->date_signature_user,
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
            'cancel'=>0
        ];

        $pdf = $this->createPDF($inputs);
        if($pdf != 'error'){
            if ($contractdata){
                if ($contractdata->plain_receipt == 1) {
                    if (sizeof($users_contras) > 0) {
                        Mail::to($inputs['user']['email'])->send(new ContractMail( $inputs, $pdf));
                        
                        foreach ($users_contras as $key => $value) {
                            $inputs['user_contra'] = $value;
                            Mail::to($value->email)->send(new ContractContraMail( $inputs,$pdf));
                        }
                    }
                    else{
                        Mail::to($inputs['user']['email'])->send(new ContractComplete( $inputs,$pdf));
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
            
            return response()->json('ok');
        }
        else{
            return response()->json(['msg'=>'Ocurrio un error al guardar'],500);
        }

    }

    public function signatureOrder(Request $request)
    {
        
        ini_set('memory_limit',-1);
        $userdata = User::find($request['users_id']);
        $order = Order::find($request['order_id']);
        
        
        $contact_auth = OrderContact::where('order_id',$order->id)->where('user_id',$userdata->id)->first();
        if ($contact_auth) {
            $contact_auth_update = OrderContact::find($contact_auth->id);
            $contact_auth_update->signature_user_contra_id = $userdata->signature_image_id;
            $contact_auth_update->date_signature_user =  date('Y-m-d H:i:s');
            $contact_auth_update->save();
        }


        $clauses_detail = [];
        $details = OrderDetail::where('order_id',$order->id)->get();
        
        foreach ($details as $key => $value) {
            $clausu = ContractClause::find($value->contract_clauses_id);
            if ($clausu) {
                $value->title = $clausu->title;
                array_push($clauses_detail,$value);
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
        if($user->address != null){
            if(State::find($user->address->state_id)){
                $user->address->state = State::find($user->address->state_id)->name;
            }
            if(Town::find($user->address->town_id)){
                $user->address->town = Town::find($user->address->town_id)->name;
            }
        }   
        
        
        

       /*  $user_contra = User::find($order->user_contra_id);
        $user_contra->signature_contra_imageUrl = Images::getUrl($order->signature_user_contra_id);
        $user_contra->address;
        $user_contra->address->state = State::find($user_contra->address->state_id)->name;
        $user_contra->address->town = Town::find($user_contra->address->town_id)->name; */

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
        $users_contras_ids = OrderContact::where('order_id',$order->id)->pluck('user_id');
     
        $users_contras = User::whereIn('id',$users_contras_ids)->get();

        foreach ($users_contras as $key => $value) {
            $cot = OrderContact::where('order_id',$order->id)->where('user_id',$value->id)->first();
            if ($cot->signature_user_contra_id != null) {
                $value->signature_contra_imageUrl = Images::getUrl($value->signature_image_id);
                $value->date_signature_user = $cot->date_signature_user;
            }
            
            $value->address;
            if (State::find($value->address->state_id)) {
                $value->address->state = State::find($value->address->state_id)->name;
            }
            if (Town::find($value->address->town_id)) {
                $value->address->town = Town::find($value->address->town_id)->name;
            }

            $name_contras = $name_contras.', '.$value->name;
            $email_contras = $email_contras.', '.$value->email;
            $phone_contras = $phone_contras.', '.$value->phone;
            $rfc_contras = $rfc_contras.', '.$value->rfc;
            $curp_contras = $curp_contras.', '.$value->curp;

            $calle_contras = $calle_contras.', '.$value->address->calle;
            $num_ext_contras = $num_ext_contras.', '.$value->address->num_ext;
            $num_int_contras = $num_int_contras.', '.$value->address->num_int;
            $neighborhood_contras = $neighborhood_contras.', '.$value->address->neighborhood;
            $zipcode_contras = $zipcode_contras.', '.$value->address->zipcode;
            $town_contras = $town_contras.', '.$value->address->town;
            $state_contras = $state_contras.', '.$value->address->state;
            $address_contras = $address_contras.', '.$value->address->calle.', '.$value->address->num_ext.', '.$value->address->num_int.', '.$value->address->neighborhood.', '.$value->address->zipcode.', '.$value->address->town.', '.$value->address->state;
          
            
        }
       

         //AQUI
         $header_format = null;
         $medio_format = null;
         $inferior_format = null;
         if ($order->contracts_id != 0 && $order->contracts_id != 999999999) {
            $contrat_data = Contract::find($order->contracts_id);
        
            $header_format = $contrat_data->header_format;
            $header_format = str_replace("[parte1_nombre]", $user->name, $header_format);
            $header_format = str_replace("[parte1_email]", $user->email, $header_format);
            $header_format = str_replace("[parte1_telefono]", $user->phone, $header_format);
            $header_format = str_replace("[parte1_rfc]", $user->rfc, $header_format);
            $header_format = str_replace("[parte1_curp]", $user->curp, $header_format);

            if ($user->address != null) {
                $header_format = str_replace("[parte1_calle]", $user->address->calle, $header_format);
                $header_format = str_replace("[parte1_numext]", $user->address->num_ext, $header_format);
                $header_format = str_replace("[parte1_numint]", $user->address->num_int, $header_format);
                $header_format = str_replace("[parte1_colonia]", $user->address->neighborhood, $header_format);
                $header_format = str_replace("[parte1_cp]", $user->address->zipcode, $header_format);
                $header_format = str_replace("[parte1_ciudad]", $user->address->town, $header_format);
                $header_format = str_replace("[parte1_estado]", $user->address->state, $header_format);
            }
            

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
            if ($user->address != null) {
                $medio_format = str_replace("[parte1_calle]", $user->address->calle, $medio_format);
                $medio_format = str_replace("[parte1_numext]", $user->address->num_ext, $medio_format);
                $medio_format = str_replace("[parte1_numint]", $user->address->num_int, $medio_format);
                $medio_format = str_replace("[parte1_colonia]", $user->address->neighborhood, $medio_format);
                $medio_format = str_replace("[parte1_cp]", $user->address->zipcode, $medio_format);
                $medio_format = str_replace("[parte1_ciudad]", $user->address->town, $medio_format);
                $medio_format = str_replace("[parte1_estado]", $user->address->state, $medio_format);
            }

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

            $inferior_format = $contrat_data->inferior_format;
            $inferior_format = str_replace("[parte1_nombre]", $user->name, $inferior_format);
            $inferior_format = str_replace("[parte1_email]", $user->email, $inferior_format);
            $inferior_format = str_replace("[parte1_telefono]", $user->phone, $inferior_format);
            $inferior_format = str_replace("[parte1_rfc]", $user->rfc, $inferior_format);
            $inferior_format = str_replace("[parte1_curp]", $user->curp, $inferior_format);

            if ($user->address != null) {
                $inferior_format = str_replace("[parte1_calle]", $user->address->calle, $inferior_format);
                $inferior_format = str_replace("[parte1_numext]", $user->address->num_ext, $inferior_format);
                $inferior_format = str_replace("[parte1_numint]", $user->address->num_int, $inferior_format);
                $inferior_format = str_replace("[parte1_colonia]", $user->address->neighborhood, $inferior_format);
                $inferior_format = str_replace("[parte1_cp]", $user->address->zipcode, $inferior_format);
                $inferior_format = str_replace("[parte1_ciudad]", $user->address->town, $inferior_format);
                $inferior_format = str_replace("[parte1_estado]", $user->address->state, $inferior_format);
            }
            

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
         }
         else{

         }
         //ACA
        $contract_title = null;
        $contracttile = Contract::find($order->contracts_id);
         if ($contracttile) {
            $contract_title = $contracttile->tile;
         }
        $contract = [
            'title'=>$contract_title,
            'clauses'=>$clauses_detail,
            'contracts_id'=>$order->contracts_id,
            'body'=>$order->body,

            'stamp'=>$order->stamp,
            'certificate'=>$order->certificate,
            'date_signature_user'=>$order->date_signature_user,
            'date_signature_user_contra'=>$order->date_signature_user_contra,
            'created'=>$order->created_at->format('d-m-Y H:i:s'),

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
            //'user_contra'=>$user_contra,
            'users_contras'=>$users_contras,
            'contract'=>$contract,
            'images'=>$images,
            'qr' => QrCode::format('png')->size(150)->margin(0)->generate($url_code),
            'cancel'=>0
        ];
        

        $hassh = $this->getHashContract($order->id,$order->multilateralId,$contact_auth_update->id);
        $content_bin = base64_decode($hassh['pdf_base64'], true);
        Storage::disk('public')->put('docs/contrato-'.$order->id.'.pdf',$content_bin);

        if ($hassh['nom_base64'] != null) {
            $contentnom_bin = base64_decode($hassh['nom_base64'], true);
            Storage::disk('public')->put('docs/contrato-nom'.$order->id.'.nom',$contentnom_bin);

            $pdfnom = PDF::loadView('plantillas.nomdata',['inputs' => $hassh['datanom']]);
            $contentnom = $pdfnom->download()->getOriginalContent();
            // Crear el archivo y almacenarlo en el storage
            Storage::disk('public')->put('docs/contrato-nom-data'.$order->id.'.pdf',$contentnom);


        }
        //$pdf = $this->createPDF($inputs);
        
        try{
            $inputs = [
                'order_id'=>$order->id,
                'name'=>$user->name,
                'user'=>$user,
                //'user_contra'=>$user_contra,
                
                'contract'=>$contract
            ];

            Mail::to($inputs['user']['email'])->send(new ContractUpdateMail( $inputs));

            foreach ($users_contras as $key => $value) {
                $inputs = [
                    'order_id'=>$order->id,
                    'name'=>$value->name,
                    'user'=>$user,
                    //'user_contra'=>$user_contra,
                    'contract'=>$contract
                ];
    
                Mail::to($value['email'])->send(new ContractUpdateMail( $inputs));
            }
           
        }catch (\Exception $e) {
            //report($e);
        }
        
        //verificar si ya estan firmados  todas la contrapartes
        $checksignatures = OrderContact::where('order_id',$order->id)->where('date_signature_user',null)->count();
        if ($checksignatures == 0) {
            try{
                $inputs = [
                    'order_id'=>$order->id,
                    'name'=>$user->name,
                    'user'=>$user,
                    //'user_contra'=>$user_contra,
                    
                    'contract'=>$contract
                ];
    
                Mail::to($inputs['user']['email'])->send(new ContractComplete( $inputs,$content_bin));
    
                foreach ($users_contras as $key => $value) {
                    $inputs = [
                        'order_id'=>$order->id,
                        'name'=>$value->name,
                        'user'=>$user,
                        //'user_contra'=>$user_contra,
                        'contract'=>$contract
                    ];
        
                    Mail::to($value['email'])->send(new ContractComplete( $inputs, $content_bin));
                }
               
            }catch (\Exception $e) {
                //report($e);
            }
        }
        $updateorder = Order::find($order->id);
        $data = [
            'documentUrl' => Documents::getUrl($updateorder->documents_id)
        ];
        return response()->json($data);

    }
    public function sendEmail(Request $request)
    {
        ini_set('memory_limit',-1);
        $row = UsersCredit::find($request->users_credits_id);
        $user = User::find($request->users_id);
        $inputs = [
            'quantity'=>$row->quantity,
            'username'=>$user->name,
            'id'=>$request->users_credits_id,
        ];
       
        try{
            Mail::to($user->email)->send(new NewCredits( $inputs));
        }catch (\Exception $e) {
            //report($e);
        }
        return response()->json('ok');
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

    public function saveOrderImage(Request $request)
    {
        
        ini_set('memory_limit', '-1');
        
        $dataform = json_decode($request->form);
        
        
        $userdata = User::find($request->users_id);
        if (Images::getUrl($userdata->image_id_logo)) {
            $userdata->imageUrl = Images::getUrl($userdata->image_id_logo);
        }
        else{
            $userdata->imageUrl = 'https://notarionet.com/public/images/logo.png';
        }

        $order = new Order();
        $order->total = 0;
        $order->subtotal = 0;
        $order->contracts_id = 0;
        $order->user_id = $userdata->id;
        $order->signature_user_id = $userdata->signature_image_id;
        $order->status = 'completado';
        $order->stamp = Str::random(20);
        $order->certificate = Str::random(20);
        $order->date_signature_user = date('Y-m-d H:i:s');
        $order->no_signature_creator = $dataform->no_signature_creator;

        $type = null;
        if ($request->file('imagecertificate')->getClientOriginalExtension() == 'pdf') {
            $order->pdf_id = Documents::save($request->file('imagecertificate'));
            $order->pdf_name = $request->file('imagecertificate')->getClientOriginalName();
            $order->body = $pdf_data_string;
            $type = 'pdf';
        }
        else{
            $order->images_id = Images::save($request->file('imagecertificate'));
            $order->image_name = $request->file('imagecertificate')->getClientOriginalName();
            $type = 'image';
        }
        
        
        $order->save();
        
        //guarda contrarios
        $user_contra_format = json_decode($request->contacts,true);
        $users_contras_ids = [];
        if($user_contra_format != null){
            foreach ($user_contra_format as $key => $value) {
                array_push($users_contras_ids,$value['id']);
            }
        }
        
        $users_contras = User::whereIn('id',$users_contras_ids)->get();
        foreach ($users_contras as $key => $value) {
        
            $row_contra = new OrderContact();
            $row_contra->user_id = $value->id;
            $row_contra->order_id = $order->id;
            $row_contra->save();
        }

        //se resta los creditos
        $restcredits = User::find($order->user_id);
        $restcredits->credits = $restcredits->credits - 1;
        $restcredits->save();


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
            ]
        ];
        
        $pdf = $this->createPDFImage($inputs);
        if ($pdf != 'error') {
            if (sizeof($users_contras) > 0) {
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
            else{
                try{
                    Mail::to($inputs['user']['email'])->send(new ContractComplete( $inputs,$pdf));
                
                }catch (\Exception $e) {
                    //report($e);
                }
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
        $multilateral = $this->saveSeguridata($inputs['order_id'],1);
        if ($multilateral != 'error') {
            $updateorder = Order::find($inputs['order_id']);
            $updateorder->documents_id = $doc->id;
            $updateorder->multilateralId = $multilateral['multilateralId'];
            $updateorder->save();

            //enviamos la firma y obtenemos el hash desde java
            if ($updateorder->no_signature_creator == 0) {
                $hassh = $this->getHash($inputs['order_id'],$multilateral['multilateralId'],'false');
                    
                $content_bin = base64_decode($hassh['pdf_base64'], true);
                Storage::disk('public')->put('docs/contrato-'.$inputs['order_id'].'.pdf',$content_bin);

                if ($hassh['nom_base64'] != null) {
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

    public function saveSeguridata($order_id,$totalSigners)
    {
        ini_set('memory_limit',-1);
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

            \Log::error( 'Guarda seguridata APP: '.json_encode($response) );

            if (isset($response['multilateralId'])) {
                return($response);
            }
            else{
                $orderdel = Order::find($order_id);
                $orderdel->delete();
                \Log::error( 'Error al guarda en seguridata App: ID:'.$order_id );
                return 'error';
            }

            
        }

    }

    public function getHash($order_id,$multilateral_id,$finalize)
    {
        ini_set('memory_limit',-1);
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
            \Log::error( 'Respuesta primer multilateral APP: '.json_encode($response) );
            
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

    public function getHashContract($order_id,$multilateral_id,$order_contacts_id)
    {
        ini_set('memory_limit',-1);
        $orderdata = Order::find($order_id);

        $contact_auth_update = OrderContact::find($order_contacts_id);
        
        
        $user = User::find($contact_auth_update->user_id);
        
       
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
            \Log::error( 'Respuesta firma contraparte multilateral APP: '.json_encode($response) );
                
            $contact_auth_update->signature_hexHash = $response['hexHash'];
            $contact_auth_update->signature_hash = $response['hash'];
            $contact_auth_update->save();
            $datareturn = [
                'signature_hexHash' => $response['hexHash'],
                'signature_hash' => $response['hash'],
                'java_hash' => null,
                'sequence' => null,
                'pdf_base64' => null
            ];

            $tohash = $response['hexHash'];
            $key_file = 'seguridata/files/system.key';
            $pass_file = '12121212Qw.';
            $cer_file = 'seguridata/files/system.cer';
            $output = null;
           
            ini_set('memory_limit',-1);
            exec('java -jar seguridata/SgSignSignerAPIv2.6.1_C.jar  '.$tohash.' '.$key_file.' '.$pass_file.' '.$cer_file, $output);
            
            

            if (isset($output[13])) {
                $datareturn['java_hash'] = $output[13];
                $contact_auth_update->java_hash = $output[13];

                $response_update = Http::withoutVerifying()->withHeaders([ 
                    'authorization' => $response_token['token'],
                ])->post('https://cloudsign.seguridata.com:4444/sign_rest_ccpunto/multilateral/update/'.$multilateral_id, [
                    'serial'=> '3030303030303030303030303030303039323732',
                    'signedMessage'=> [
                      'base64'=> true,
                      'data'=> $output[13],
                      'name'=> 'contrato-'.$order_id.'.pdf',
                      ]
                ])->json();
                
                $contact_auth_update->sequence = $response_update['sequence'];
                $contact_auth_update->save();

                $datareturn['sequence'] = $response_update['sequence'];
                    
                //check si ya es el  ultimo firmate
                $contacts_count = OrderContact::where('order_id',$order_id)->where('date_signature_user',null)->count();

                //obtenemos el pdf ya firmado  en base 64
                if ($contacts_count > 0) {
                    $response_finalize = Http::withoutVerifying()->withHeaders([ 
                        'authorization' => $response_token['token'],
                    ])->get('https://cloudsign.seguridata.com:4444/sign_rest_ccpunto/multilateral/finalize/'.$multilateral_id.'/true', [
        
                    ])->json();
                    $datareturn['pdf_base64'] = $response_finalize[0]['data'];
                    $datareturn['nom_base64'] = null;
                }
                else{
                    $response_finalize = Http::withoutVerifying()->withHeaders([ 
                        'authorization' => $response_token['token'],
                    ])->get('https://cloudsign.seguridata.com:4444/sign_rest_ccpunto/multilateral/finalize/'.$multilateral_id.'/false', [
        
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
    
    public function resendEmails(Request $request)
    {
        ini_set('memory_limit',-1);
        $order = Order::find($request->orders_id);        
        $user = User::find($order->user_id);

        $contract = Contract::find($order->contracts_id);
        $contract_name = null;
        if ($contract) {
            $contract_name = $contract->title;
        }
        else{
            $contract_name = 'Documento libre';
        }
        $inputs = [
            'order_id'=>$order->id,
            'user_name'=>$user->name,
            'contract_name'=>$contract_name,
        ];
        
        foreach ($request->correos as $key => $value) {
            try{
                $doc = Document::find($order->documents_id);

                $dir = base_path().'/storage/app/public/'.$doc->path;;
                $pdf = file_get_contents($dir,  FILE_USE_INCLUDE_PATH);
                
                Mail::to($value['email'])->send(new SendEmailOrderdone( $inputs,$pdf));

            }catch (\Exception $e) {
                //report($e);
            }
        }
        return 'Correos enviados';
    }
}
