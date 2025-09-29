<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

use App\Models\Category;
use App\Models\Contract;
use App\Models\Contact;
use App\Models\ContractClause;
use App\Models\Document;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use App\Models\State;
use App\Models\Town;
use App\Models\OrderContact;
use App\Models\OrderImage;

use App\Models\ContractImages;

use App\Mail\ContractComplete;
use App\Mail\ContractCancel;
use App\Mail\ContractContraMail;
use App\Mail\ContractUpdateMail;

use Illuminate\Support\Facades\Http;
use Images;
use Documents;
class ContractsController extends Controller
{
    public function categoriesContracts()
    {
        $data = [];
        $categories = Category::all();
        foreach ($categories as $key => $value) {
            $aux = [
                'id'=>$value->id,
                'name'=>$value->name,
                'name_en'=>$value->name_en,
                'category'=>true
            ];
            array_push($data,$aux);
            $contracts = Contract::select('id','title', 'description','title_en','categories_id','position', 'images_id')->where('categories_id',$value->id)->orderBy('position','asc')->get();
            foreach ($contracts as $keyc => $valuec) {
                if($valuec->id != 25 && $valuec->id != 29 && $valuec->id != 30){
                    $image = 'https://notarionet.com'.str_replace('http://127.0.0.1:8000','',Images::getUrl($valuec->images_id));
                    $aux = [
                        'id'=>$valuec->id,
                        'name'=>$valuec->title,
                        'name_en'=>$valuec->title_en,
                        'description'=>$valuec->description,
                        'category'=>false,
                        'categories_id'=>$valuec->categories_id,
                        'images' => $image
                    ];
                    array_push($data,$aux);
                }
            }
        }
        return response()->json($data);
    }

    public function contracts()
    {
        ini_set('memory_limit',-1);
        $contracts = Contract::whereNotIn('id',[25,30])->orderBy('position','asc')->get();

        foreach ($contracts as $key => $value) {
            $fields = [];
            $clauses = ContractClause::where('contracts_id',$value->id)->get();

            foreach ($clauses as $keycla => $valuecla) {
                $valuecla->descriptionview = $valuecla->description;

                /*$array_description = explode("[", $valuecla->description);
                foreach ($array_description as $keydes => $valuedes) {
                    $pos = strpos($valuedes, ']');
                    if ($pos === false) {
                    }
                    else{

                        $field = substr($valuedes, 0, $pos);
                        $aux = [
                            'index'=>$keydes,
                            'name'=>$field,
                            'response'=>null
                        ];
                        array_push($fields,$aux);

                    }
                }
                $valuecla->fields = $fields;*/




            }
           // $value->clauses = $clauses;


            $header_format = explode("[", $value->header_format);

                $fields_header = [];
                foreach ($header_format as $keydes => $valuedes) {
                    $pos = strpos($valuedes, ']');
                    if ($pos === false) {
                    }
                    else{
                        $field = substr($valuedes, 0, $pos);
                        $clausedata = ContractClause::where('description','['.$field.']')->first();
                        if ($clausedata) {
                            $aux = [
                                'index'=>$keydes,
                                'name'=>$field,
                                'title'=>$clausedata->title,
                                'response'=>null,
                                'type'=>$clausedata->type
                            ];
                        }
                        else{
                            $aux = [
                                'index'=>$keydes,
                                'name'=>$field,
                                'title'=>$field,
                                'response'=>null
                            ];
                        }
                        array_push($fields_header,$aux);
                    }
                }
                $fields['fields_header'] = $fields_header;

                $medio_format = explode("[", $value->medio_format);
                $fields_medio = [];
                foreach ($medio_format as $keydes => $valuedes) {
                    $pos = strpos($valuedes, ']');
                    if ($pos === false) {
                    }
                    else{
                        $field = substr($valuedes, 0, $pos);
                        $clausedata = ContractClause::where('description','['.$field.']')->first();
                        if ($clausedata) {
                            $aux = [
                                'index'=>$keydes,
                                'name'=>$field,
                                'title'=>$clausedata->title,
                                'response'=>null,
                                'type'=>$clausedata->type
                            ];
                        }
                        else{
                            $aux = [
                                'index'=>$keydes,
                                'name'=>$field,
                                'response'=>null
                            ];
                        }

                        array_push($fields_medio,$aux);
                    }
                }
                $fields['fields_medio'] = $fields_medio;

                $inferior_format = explode("[", $value->inferior_format);
                $fields_inferior = [];
                foreach ($inferior_format as $keydes => $valuedes) {
                    $pos = strpos($valuedes, ']');
                    if ($pos === false) {
                    }
                    else{
                        $field = substr($valuedes, 0, $pos);
                        $clausedata = ContractClause::where('description','['.$field.']')->first();
                        if ($clausedata) {
                            $aux = [
                                'index'=>$keydes,
                                'name'=>$field,
                                'title'=>$clausedata->title,
                                'response'=>null,
                                'type'=>$clausedata->type
                            ];
                        }
                        else{
                            $aux = [
                                'index'=>$keydes,
                                'name'=>$field,
                                'response'=>null
                            ];
                        }
                        array_push($fields_inferior,$aux);
                    }
                }
                $fields['fields_inferior'] = $fields_inferior;
                $value->clauses = $fields;
        }
        return response()->json($contracts);
    }
    public function detail($id)
    {
        $row = Contract::find($id);
        $header_format = str_replace('[','',$row->header_format);
        $header_format = str_replace(']','',$header_format);

        $medio_format = str_replace('[','',$row->medio_format);
        $medio_format = str_replace(']','',$medio_format);

        $inferior_format = str_replace('[','',$row->inferior_format);
        $inferior_format = str_replace(']','',$inferior_format);

        $row->body_view = $header_format.'<br>'.$medio_format.'<br>'.$inferior_format;
        if($row->body_view == '<br><br>'){
            $row->body_view = $row->body_format;
        }
        $gallery = [];
        array_push($gallery, Images::getUrl($row->images_id));
        $row->gallery = $gallery;
        $row->img = 'https://notarionet.com'.str_replace('http://127.0.0.1:8000','',Images::getUrl($row->images_id));
        return response()->json($row);
    }
    public function saveSignatureContracts($id)
    {

        ini_set('memory_limit',-1);
        $userdata = Auth::user();
        $order = Order::find($id);
        //$order->signature_user_contra_id = $userdata->signature_image_id;
        //$order->date_signature_user_contra = date('Y-m-d H:i:s');
        $order->save();

        $contact_auth = OrderContact::where('order_id',$id)->where('user_id',$userdata->id)->first();
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

            if($value->address != null){
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


         //AQUI
         $header_format = null;
         $medio_format = null;
         $inferior_format = null;
         if ($order->contracts_id != 888899999 && $order->contracts_id != 888888888 && $order->contracts_id != 999999999 && $order->contracts_id != 777777777) {
            $contrat_data = Contract::find($order->contracts_id);

            if($contrat_data){
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

            'header_format'=>$header_format,
            'medio_format'=>$medio_format,
            'inferior_format'=>$inferior_format,
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


        $hassh = $this->getHash($order->id,$order->multilateralId,$contact_auth_update->id);
        $content_bin = base64_decode($hassh['pdf_base64'], true);
        Storage::disk('public')->put('docs/contrato-'.$order->id.'.pdf',$content_bin);

        if ($hassh['nom_base64'] != null) {
            $order->nom_base64 = $hassh['nom_base64'];
            $order->save();
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
            'documentUrl' => Documents::getUrl($updateorder->documents_id),
            'stripe_link' => $updateorder->stripe_link
        ];
        return response()->json($data);

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
      $updateorder = Order::find($inputs['order_id']);
      $updateorder->documents_id = $doc->id;
      $updateorder->save();

      return $content;
    }

    public function getReview(Request $request)
    {

        $user = Auth::user();
        $user->address;

        //guardamos y obtenemos los usuarios contrarios
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


        $users_contras = User::whereIn('id',$request->contacts)->get();

        foreach ($users_contras as $key => $value) {

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

            if($value->address != null){
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

        //AQUI
        $header_format = null;
        $medio_format = null;
        $inferior_format = null;
        if ($request->contracts_id != 0 && $request->contracts_id != 999999999) {
           $contrat_data = Contract::find($request->contracts_id);
           if($contrat_data){
                $header_format = $contrat_data->header_format;
                foreach ($request->contractdata['clauses']['fields_header'] as $key => $value) {
                    $header_format = str_replace("[".$value['name']."]", $value['response'], $header_format);
                }

                $medio_format = $contrat_data->medio_format;
                foreach ($request->contractdata['clauses']['fields_medio'] as $key => $value) {
                    $medio_format = str_replace("[".$value['name']."]", $value['response'], $medio_format);
                }

                $inferior_format = $contrat_data->inferior_format;
                foreach ($request->contractdata['clauses']['fields_inferior'] as $key => $value) {
                    $inferior_format = str_replace("[".$value['name']."]", $value['response'], $inferior_format);
                }
            }
           /*$header_format = $contrat_data->header_format;
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
           $header_format = str_replace("[parte1_domicilio]", $user->address->calle.', '.$user->address->num_ext.', '.$user->address->num_int.', '.$user->address->neighborhood.', '.$user->address->zipcode.', '.$user->address->town.', '.$user->address->state, $header_format);


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
           $medio_format = str_replace("[parte1_domicilio]", $user->address->calle.', '.$user->address->num_ext.', '.$user->address->num_int.', '.$user->address->neighborhood.', '.$user->address->zipcode.', '.$user->address->town.', '.$user->address->state, $medio_format);


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
           $inferior_format = str_replace("[parte1_calle]", $user->address->calle, $inferior_format);
           $inferior_format = str_replace("[parte1_numext]", $user->address->num_ext, $inferior_format);
           $inferior_format = str_replace("[parte1_numint]", $user->address->num_int, $inferior_format);
           $inferior_format = str_replace("[parte1_colonia]", $user->address->neighborhood, $inferior_format);
           $inferior_format = str_replace("[parte1_cp]", $user->address->zipcode, $inferior_format);
           $inferior_format = str_replace("[parte1_ciudad]", $user->address->town, $inferior_format);
           $inferior_format = str_replace("[parte1_estado]", $user->address->state, $inferior_format);
           $inferior_format = str_replace("[parte1_domicilio]", $user->address->calle.', '.$user->address->num_ext.', '.$user->address->num_int.', '.$user->address->neighborhood.', '.$user->address->zipcode.', '.$user->address->town.', '.$user->address->state, $inferior_format);


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
           $inferior_format = str_replace("[contraparte_domicilio]", $address_contras, $inferior_format);*/
        }
        else{

        }


        $contract = [
            'header_format_preview'=>$header_format,
            'medio_format_preview'=>$medio_format,
            'inferior_format_preview'=>$inferior_format,
        ];

        return response()->json($contract);
    }

    public function search(Request $request)
    {
        $rows = Contract::where(function($query) use($request){
        if($request->keywords != null)
        {
            $query->where(function($subquery) use($request){
                    $subquery->orWhere('title','like',"%{$request->keywords}%");
                    $subquery->orWhere('description','like',"%{$request->keywords}%");

            });
        }
        })->get();
        foreach ($rows as $key => $value) {
            $value->imageUrl = str_replace('http://127.0.0.1:8000/','https://notarionet.com/',Images::getUrl($value->images_id));
        }
        return response()->json($rows);
    }

    public function cancelContracts($id)
    {
        ini_set('memory_limit',-1);

        $order = Order::find($id);
        if ($order) {


            $userdata = Auth::user();
            $order = Order::find($id);
            $order->status = 'cancelado';
            $order->id_cancel = $id.Str::random(6);
            $order->date_cancel = date('Y-m-d H:i:s');
            $order->save();

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

                if($value->address != null){
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


            //AQUI
            $header_format = null;
            $medio_format = null;
            $inferior_format = null;
            if ($order->contracts_id != 0 && $order->contracts_id != 999999999) {
                $contrat_data = Contract::find($order->contracts_id);
                if ($contrat_data) {
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

                'header_format'=>$header_format,
                'medio_format'=>$medio_format,
                'inferior_format'=>$inferior_format,
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
                'cancel'=>1
            ];


            $pdf = $this->createPDF($inputs);

            try{
                $inputs = [
                    'order_id'=>$order->id,
                    'name'=>$user->name,
                    'user'=>$user,
                    'contract'=>$contract
                ];

                Mail::to($inputs['user']['email'])->send(new ContractCancel( $inputs,$pdf));

                foreach ($users_contras as $key => $value) {
                    $inputs = [
                        'order_id'=>$order->id,
                        'name'=>$value->name,
                        'user'=>$user,
                        'contract'=>$contract
                    ];

                    Mail::to($value['email'])->send(new ContractCancel( $inputs, $pdf));
                }

            }catch (\Exception $e) {
                //report($e);
            }

            $updateorder = Order::find($order->id);
            $data = [
                'documentUrl' => Documents::getUrl($updateorder->documents_id)
            ];
            return response()->json($data);

        }
    }

    public function sedEmail(Request $request)
    {
        $order = Order::find($request->orders_id);
        $ordercontact = OrderContact::find($request->order_contacts_id);
        $user_contra = User::find($ordercontact->user_id);
        $user = User::find($order->user_id);

        $contract = Contract::find($order->contracts_id);
        $contractdata = [
            'title'=>$contract['title'],
            'title_url'=>str_replace(' ','-',$contract['title']),
        ];
        $inputs = [
            'order_id'=>$order->id,
            'name'=>$user->name,
            'user'=>$user,

            'contract'=>$contractdata,
            'user_contra'=>$user_contra

        ];


        try{
            $doc = Document::find($order->documents_id);

            $dir = base_path().'/storage/app/public/'.$doc->path;;
            $pdf = file_get_contents($dir,  FILE_USE_INCLUDE_PATH);

            Mail::to($inputs['user_contra']['email'])->send(new ContractContraMail( $inputs,$pdf));

            return response()->json('ok');
        }catch (\Exception $e) {
            //report($e);
        }
    }

    public function getHash($order_id,$multilateral_id,$order_contacts_id)
    {
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

            \Log::error( 'Respuesta firma contraparte multilateral: '.json_encode($response) );
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


    public function compareSiganature(Request $request)
    {
        ini_set('memory_limit',-1);
        $userdata = Auth::user();

        //obtenemos los datos de la firma registra al inicio
            $points_signature = str_replace('\"','"',$userdata->points_signature);
            $data_points_left = [];
            $data_points_left_aux = [];

            $points_signature_array = explode('","',$points_signature);
            foreach ($points_signature_array as $key => $value) {
                $value_array = explode(',',$value);
                $array_x = explode(':',$value_array[0]);
                $array_y = explode(':',$value_array[1]);
                $array_t = explode(':',$value_array[2]);
                $array_p = explode(':',$value_array[3]);

                $aux = [
                    'coordinateX' => floatval($array_x[1]),
                    'coordinateY' => floatval($array_y[1]),
                    'dataPointTimestamp' => floatval($array_t[1]),
                    'idSignature' => 0,
                    'idSignatureDataPoint' => 0,
                    'pressure' => 0,
                    'traceNumber' => 0,
                    'velocityX' => 0,
                    'velocityY' => 0,
                ];
                array_push($data_points_left,$aux);
            }

            //sacamos el mayo valor de X
            $data_points_left_aux = $data_points_left;

            $xx_left = array();
            foreach ($data_points_left_aux as $key => $row){
                $xx_left[$key] = $row['coordinateX'];
            }
            array_multisort($xx_left, SORT_DESC, $data_points_left_aux);
            $coordinateMaxValueX_left = $xx_left[0];


            //sacamos el mayo valor de Y
            $yy_left = array();
            foreach ($data_points_left_aux as $key => $row){
                $yy_left[$key] = $row['coordinateY'];
            }
            array_multisort($yy_left, SORT_DESC, $data_points_left_aux);
            $coordinateMaxValueY_left = $yy_left[0];


            //sacamos el primero y ultimo valor de T
            $signatureStartTimestamp_left = $data_points_left[0]['dataPointTimestamp'];
            $signatureEndTimeStamp_left = $data_points_left[sizeof($data_points_left) - 1]['dataPointTimestamp'];

        //sacamos los datos de la firma que se acaba de enviar
        $data_points_right = [];
        $points_data = json_decode($request->points);
        if (isset($points_data)) {
            if ($points_data != null) {
                $rows_json = [];
                for ($i=0; $i < sizeof($points_data->xValues) ; $i++) {
                    $aux = [
                        'coordinateX' => floatval($points_data->xValues[$i]),
                        'coordinateY' => floatval($points_data->yValues[$i]),
                        'dataPointTimestamp' => floatval($points_data->timeValues[$i]),
                        'idSignature' => 0,
                        'idSignatureDataPoint' => 0,
                        'pressure' => 0,
                        'traceNumber' => 0,
                        'velocityX' => 0,
                        'velocityY' => 0,
                    ];

                    array_push($data_points_right,$aux);
                }
            }
        }
        //sacamos el mayo valor de X
        $data_points_right_aux = $data_points_right;

        $xx_right = array();
        foreach ($data_points_right_aux as $key => $row){
            $xx_right[$key] = $row['coordinateX'];
        }
        array_multisort($xx_right, SORT_DESC, $data_points_right_aux);
        $coordinateMaxValueX_right = $xx_right[0];


        //sacamos el mayo valor de Y
        $yy_right = array();
        foreach ($data_points_right_aux as $key => $row){
            $yy_right[$key] = $row['coordinateY'];
        }
        array_multisort($yy_right, SORT_DESC, $data_points_right_aux);
        $coordinateMaxValueY_right = $yy_right[0];


        //sacamos el primero y ultimo valor de T
        $signatureStartTimestamp_right = $data_points_right[0]['dataPointTimestamp'];
        $signatureEndTimeStamp_right = $data_points_right[sizeof($data_points_right) - 1]['dataPointTimestamp'];



        //dd($signatureEndTimeStamp_right,$signatureStartTimestamp_right);
        $response_compare = Http::withoutVerifying()
        ->withHeaders([
			'Content-Type' => 'application/json',
		])->withBasicAuth('ccpunto', '44y.Punt0')
		->post('https://cloudsign.seguridata.com:2443/compareSignatures_1.2.0/prediction', [

            'left'=> [
				'coordinateMaxValueX'=> $coordinateMaxValueX_left,
				'coordinateMaxValueY'=> $coordinateMaxValueY_left,
				'signatureDataPointEntityList'=>$data_points_left,
				'signatureEndTimeStamp'=> $signatureEndTimeStamp_left,
				'signatureStartTimestamp'=> $signatureStartTimestamp_left,
				'velocityMaxValueX'=> 0,
				'velocityMaxValueY'=> 0
			],
			'right'=> [
				'coordinateMaxValueX'=> $coordinateMaxValueX_right,
				'coordinateMaxValueY'=> $coordinateMaxValueY_right,
				'signatureDataPointEntityList'=> $data_points_right,
				'signatureEndTimeStamp'=> $signatureEndTimeStamp_right,
				'signatureStartTimestamp'=> $signatureStartTimestamp_right,
				'velocityMaxValueX'=> 0,
				'velocityMaxValueY'=> 0
			]

        ])->json();


        if ($response_compare['success'] == true) {
            $data_response = [
                'prediction' => intval(number_format($response_compare['prediction'] * 100)),
                'status' => $response_compare['success']
            ];

            return response()->json($data_response);
        }
        else{
            $data_response = [
                'status' => false
            ];
            return response()->json($data_response);
        }
    }

}
