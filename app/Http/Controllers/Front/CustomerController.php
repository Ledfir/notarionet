<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Address;
use App\Models\Image;
use App\Models\UsersContact;
use App\Models\Contract;
use App\Models\Order;
use App\Models\OrderContact;
use App\Models\UserApp;
use App\Models\Document;
use App\Models\UsersCredit;
use App\Models\Package;
use App\Models\FoldersOrder;
use App\Models\Folder;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\ResetPassword;
use App\Mail\AuthorizedProccessAccount;
use App\Mail\ConfirmEmail;

use DB;
use Documents;
use Images;

use stdClass;

class CustomerController extends Controller
{
    public function getData()
    {
        $id = Auth::user()->id;
        $user = User::find($id);
        $user->address;
        if($user->address == null){
            $obj = new stdClass();
            $obj->street = null;
            $obj->num_ext = null;
            $obj->num_int = null;
            $obj->neighborhood = null;
            $obj->zipcode = null;
            $obj->state_id = null;
            $obj->town_id = null;
               $user->address = $obj;
        }
        $user->rfcDoc = Documents::getUrl($user->rfc_documents_id);
        $user->curpDoc = Documents::getUrl($user->curp_documents_id);
        $user->inefrontDoc = Documents::getUrl($user->inefront_documents_id);
        $user->inebackDoc = Documents::getUrl($user->ineback_documents_id);
        $user->imageUrlLogo = Images::getUrl($user->image_id_logo);
        $user->imageUrl = Images::getUrl($user->image_id);
        $user->signatureUrl = Images::getUrl($user->signature_image_id);
        $user->datapoint = json_decode($user->points);
        $count_total = 0;

        $count = 0;
        $user->alldocument = false;
        $document_less = [];
        if ($user->email == null) {
            
            $count++;
            array_push($document_less,'Email');
        }
        if ($user->name  == null) {
            
            $count++;
            array_push($document_less,'Nombre');
        }
        if ($user->phone  == null) {
            $count++;
            array_push($document_less,'Teléfono');
        }
        /*if($user->address != null){
            if($user->business_name != null && $user->business_name != 'null' && $user->business_name != ''){
                
                if ($user->address->street_company == null) {
                    $count++;
                    array_push($document_less,'Calle');
                }
                if ($user->address->num_ext_company  == null) {
                    $count++;
                    array_push($document_less,'Numero Ext.');
                }
                if ($user->address->neighborhood_company  == null) {
                    $count++;
                    array_push($document_less,'Colonia');
                }
                if ($user->address->zipcode_company  == null) {
                    $count++;
                    array_push($document_less,'Codigo postal');
                }
                if ($user->address->state_id_company  == null) {
                  
                    $count++;
                    array_push($document_less,'Estado');
                }
                if ($user->address->town_id_company  == null) {
                    $count++;
                    array_push($document_less,'Ciudad');
                }
            }   
            else{
            
                if ($user->address->street == null) {
                    $count++;
                    array_push($document_less,'Calle');
                }
                if ($user->address->num_ext  == null) {
                    $count++;
                    array_push($document_less,'Numero Ext.');
                }
                if ($user->address->neighborhood  == null) {
                    $count++;
                    array_push($document_less,'Colonia');
                }
                if ($user->address->zipcode  == null) {
                    $count++;
                    array_push($document_less,'Codigo postal');
                }
                if ($user->address->state_id  == null) {
                  
                    $count++;
                    array_push($document_less,'Estado');
                }
                if ($user->address->town_id  == null) {
                    $count++;
                    array_push($document_less,'Ciudad');
                }
            }
            
        }
        else{
            array_push($document_less,'Direccion');
        }*/
        /*if ($user->rfc_documents_id  == null) {
            $count++;
        }
        if ($user->curp_documents_id  == null) {
            $count++;
        }
        if ($user->inefront_documents_id  == null) {
            $count++;
        }
        if ($user->ineback_documents_id  == null) {
            $count++;
        }*/
        if ($user->signature_image_id  == null) {
            array_push($document_less,'Firma');
            $count++;
        }
        if ($count == 0) {
            $user->alldocument = true;
        }
        $user->documents_less = '<b>Informacion faltantes</b><br>';
        $documents_less = implode("<br>", $document_less);
        $user->documents_less = $user->documents_less.' '.$documents_less;
        $user->documents_less_size = sizeof($document_less);
         //contratos que realizo el usuario y espera la firma de otro
        $contractsdata = Order::select('orders.id')
            ->leftJoin('contracts', 'orders.contracts_id', '=', 'contracts.id')
            ->leftJoin('users', 'orders.user_id', '=', 'users.id')
            ->join('order_contacts', 'orders.id', '=', 'order_contacts.order_id')
            ->where('orders.user_id',$id)
            ->get();
        foreach ($contractsdata as $key => $value) {
            $count = OrderContact::where('order_id',$value->id)->where('signature_user_contra_id',null)->count();     
            if ($count > 0) {
                $count_total++;
            }
        }

        //por firmar
        $contractsdosdata = Order::select('orders.id')
            ->leftJoin('contracts', 'orders.contracts_id', '=', 'contracts.id')
            ->leftJoin('users', 'orders.user_id', '=', 'users.id')
            ->join('order_contacts', 'orders.id', '=', 'order_contacts.order_id')
            ->where('order_contacts.user_id',$id)
            ->get();
        foreach ($contractsdosdata as $key => $value) {
            $count = OrderContact::where('order_id',$value->id)->where('signature_user_contra_id',null)->count();
            if ($count > 0) {
                $count_total++;
            }
        }
        //demas
        $contractcomplete = Order::select('orders.id')
            ->leftJoin('contracts', 'orders.contracts_id', '=', 'contracts.id')
            ->leftJoin('users', 'orders.user_id', '=', 'users.id')
            ->leftJoin('order_contacts', 'orders.id', '=', 'order_contacts.order_id')
            ->where('orders.user_id',$id)
            ->get();
        
        $contractcompletedos = Order::select('orders.id')
            ->leftJoin('contracts', 'orders.contracts_id', '=', 'contracts.id')
            ->leftJoin('users', 'orders.user_id', '=', 'users.id')
            ->join('order_contacts', 'orders.id', '=', 'order_contacts.order_id')
            ->where('order_contacts.user_id',$id)
            ->get();
        $contractcompleteimage = Order::select('orders.id')
            ->where('orders.user_id',$id)
            ->where('orders.images_id','!=',null)
            ->leftJoin('users', 'orders.user_id', '=', 'users.id')
            ->get();
        $completes = [];
        $completesdataone = $contractcomplete->merge($contractcompletedos);
        $completesdata =  $completesdataone->merge($contractcompleteimage);
        foreach ($completesdata as $key => $value) {
            $count = OrderContact::where('order_id',$value->id)->where('signature_user_contra_id',null)->count();
            if ($count == 0) {
                $count_total++;
            }
        }

        //$user->total_contracts = Order::where('user_id',$id)->count() + Order::join('order_contacts', 'orders.id', '=', 'order_contacts.order_id')->where('order_contacts.user_id',$id)->count();
        $user->total_contracts = $count_total;

        return response()->json($user);
    }
    public function profile(Request $request)
    {
        
        $id = Auth::user()->id;
        $user = User::find($id);

    
        //try{
           
            $user->name = $request->name;
            $user->lastname = $request->lastname;
            $user->email = $request->email;
            $user->phone = $request->phone;
            if ($request->file('image')) {
                if ($user->image_id != null) {
                    Images::delete($user->image_id);
                }
                $user->image_id = Images::save($request->file('image'));
            }
            
            if ($request->image_base64) {
                if ($request->image_base64 != null && $request->image_base64 != 'null' && substr($request->image_base64,0,27) != 'https://notarionet.com/img/') {
                    $data = $request->image_base64;
                    list($type, $data) = explode(';', $data);
                    list(, $data)      = explode(',', $data);
                    $data = base64_decode($data);
                    
                    $key = uniqid();
                    Storage::disk('public')->put('photos/'.$key.'.png',$data);
                    $dir = base_path().'/storage/app/public/photos';
                    $file = $key.'.png';
                    $content = file_get_contents($dir.'/'. $file,  FILE_USE_INCLUDE_PATH);
                    if($content){
                        if ($user->image_id != null) {
                            Images::delete($user->image_id);
                        }
    
                        Storage::disk('public')->put('photos/'.$file, $content);
                        $doc = new Image(array(
                            "path"=> 'photos/'.$file,
                            "disk"=> 'public',
                            "key"=>$key,
                        ));
                        $doc->save(); 
                        $user->image_id = $doc->id;
                    }
                }
            }

            $user->save();

            $address = Address::where('user_id', $user->id)->first();
            $address->street = $request->street;
            $address->num_ext = $request->num_ext;
            $address->num_int = $request->num_int;
            $address->neighborhood = $request->neighborhood;
            $address->zipcode = $request->zipcode;
            $address->state_id = $request->state_id;
            $address->town_id = $request->town_id;
            $address->country = $request->country;
            $address->foreing = $request->foreing;

            $address->street_company = $request->street_company;
            $address->num_ext_company = $request->num_ext_company;
            $address->num_int_company = $request->num_int_company;
            $address->neighborhood_company = $request->neighborhood_company;
            $address->zipcode_company = $request->zipcode_company;
            $address->state_id_company = $request->state_id_company;
            $address->town_id_company = $request->town_id_company;
            $address->save();

         
            $this->checkAuthorized($user->id);
            
            
            return response()->json(["status" => "success", "msg" => "Informacion actualizada correctamente!"]);

       /* }catch (\Exception $e) {
            return response()->json(["status" => "error", 'msg'=>'Ocurrio un error'],500);
        }*/
    }
    public function documentation(Request $request)
    {
        $id = Auth::user()->id;
        $user = User::find($id);
        if ($request->rfc != null && $request->rfc != 'null' && $request->rfc != '') {
            $user->rfc = $request->rfc;
        }
        else{
            $user->rfc = null;
        }
        
        if ($request->curp != null && $request->curp != 'null' && $request->curp != '') {
            $user->curp = $request->curp;
        }
        else{
            $user->curp = null;
        }
        if ($request->clave_ine != null && $request->clave_ine != 'null' && $request->clave_ine != '') {
            $user->clave_ine = $request->clave_ine;
        }
        else{
            $user->clave_ine = null;
        }

        if ($request->file('rfc_file')) {
            if ($user->rfc_documents_id != null) {
                Documents::delete($user->rfc_documents_id);
            }
            $user->rfc_documents_id = Documents::save($request->file('rfc_file'));
        }
        if ($request->rfc_file_base64) {
            if ($request->rfc_file_base64 != null && $request->rfc_file_base64 != 'null') {
                $data = $request->rfc_file_base64;
                
                list($type, $data) = explode(';', $data);
                list(, $data)      = explode(',', $data);
                $data = base64_decode($data);
                
                $key = uniqid();
                Storage::disk('public')->put('docs/'.$key.'.png',$data);
                $dir = base_path().'/storage/app/public/docs';
                $file = $key.'.png';
                $content = file_get_contents($dir.'/'. $file,  FILE_USE_INCLUDE_PATH);
                if($content){
                    if ($user->rfc_documents_id != null) {
                        Documents::delete($user->rfc_documents_id);
                    }

                    Storage::disk('public')->put('docs/'.$file, $content);
                    $doc = new Document(array(
                        "path"=> 'docs/'.$file,
                        "disk"=> 'public',
                        "key"=>$key,
                    ));
                    $doc->save(); 
                    $user->rfc_documents_id = $doc->id;
                }
            }
        }

        if ($request->file('curp_file')) {
            if ($user->curp_documents_id != null) {
                Documents::delete($user->curp_documents_id);
            }
            $user->curp_documents_id = Documents::save($request->file('curp_file'));
        }
        if ($request->curp_file_base64) {
            if ($request->curp_file_base64 != null && $request->curp_file_base64 != 'null') {
                $data = $request->curp_file_base64;
                list($type, $data) = explode(';', $data);
                list(, $data)      = explode(',', $data);
                $data = base64_decode($data);
                
                $key = uniqid();
                Storage::disk('public')->put('docs/'.$key.'.png',$data);
                $dir = base_path().'/storage/app/public/docs';
                $file = $key.'.png';
                $content = file_get_contents($dir.'/'. $file,  FILE_USE_INCLUDE_PATH);
                if($content){
                    if ($user->curp_documents_id != null) {
                        Documents::delete($user->curp_documents_id);
                    }

                    Storage::disk('public')->put('docs/'.$file, $content);
                    $doc = new Document(array(
                        "path"=> 'docs/'.$file,
                        "disk"=> 'public',
                        "key"=>$key,
                    ));
                    $doc->save(); 
                    $user->curp_documents_id = $doc->id;
                }
            }
        }


        if ($request->file('inefront')) {
            if ($user->inefront_documents_id != null) {
                Documents::delete($user->inefront_documents_id);
            }
            $user->inefront_documents_id = Documents::save($request->file('inefront'));
        }
        if ($request->inefront_file_base64 && $request->inefront_file_base64 != 'null') {
            if ($request->inefront_file_base64 != null) {
                $data = $request->inefront_file_base64;
                list($type, $data) = explode(';', $data);
                list(, $data)      = explode(',', $data);
                $data = base64_decode($data);
                
                $key = uniqid();
                Storage::disk('public')->put('docs/'.$key.'.png',$data);
                $dir = base_path().'/storage/app/public/docs';
                $file = $key.'.png';
                $content = file_get_contents($dir.'/'. $file,  FILE_USE_INCLUDE_PATH);
                if($content){
                    if ($user->inefront_documents_id != null) {
                        Documents::delete($user->inefront_documents_id);
                    }

                    Storage::disk('public')->put('docs/'.$file, $content);
                    $doc = new Document(array(
                        "path"=> 'docs/'.$file,
                        "disk"=> 'public',
                        "key"=>$key,
                    ));
                    $doc->save(); 
                    $user->inefront_documents_id = $doc->id;
                }
            }
        }

        if ($request->file('ineback')) {
            if ($user->ineback_documents_id != null) {
                Documents::delete($user->ineback_documents_id);
            }
            $user->ineback_documents_id = Documents::save($request->file('ineback'));
        }
        if ($request->ineback_file_base64) {
            if ($request->ineback_file_base64 != null) {
                $data = $request->ineback_file_base64;
                list($type, $data) = explode(';', $data);
                list(, $data)      = explode(',', $data);
                $data = base64_decode($data);
                
                $key = uniqid();
                Storage::disk('public')->put('docs/'.$key.'.png',$data);
                $dir = base_path().'/storage/app/public/docs';
                $file = $key.'.png';
                $content = file_get_contents($dir.'/'. $file,  FILE_USE_INCLUDE_PATH);
                if($content){
                    if ($user->ineback_documents_id != null) {
                        Documents::delete($user->ineback_documents_id);
                    }

                    Storage::disk('public')->put('docs/'.$file, $content);
                    $doc = new Document(array(
                        "path"=> 'docs/'.$file,
                        "disk"=> 'public',
                        "key"=>$key,
                    ));
                    $doc->save(); 
                    $user->ineback_documents_id = $doc->id;
                }
            }
        }

        $this->checkAuthorized($user->id);

        $user->save();

        return response()->json(['status'=>'success','data'=>$user,'msg'=>'Documentacion actualizada correctamente']);

    }
    public function signature(Request $request)
    {
        

        $id = Auth::user()->id;
        $user = User::find($id);
        if (isset($request->signature)) {
            if ($request->signature != null) {
                $data = $request->signature;
                
                list($type, $data) = explode(';', $data);
                list(, $data)      = explode(',', $data);
                $base_64_sign = $data;  
                $data = base64_decode($data);
                
                Storage::disk('public')->put('imagestmp/imageone_'.$id.'.png',$data);
                $dir = base_path().'/storage/app/public/imagestmp';
                $file = 'imageone_'.$id.'.png';
                $content = file_get_contents($dir.'/'. $file,  FILE_USE_INCLUDE_PATH);
                if($content){
                        Storage::disk('public')->put('photos/'.$file, $content);
                        $image = new Image(array(
                            "path"=> 'photos/'.$file,
                            "disk"=> 'public',
                            "key"=>uniqid(),
                        ));
                        $image->save(); 
                    $user->signature_image_id = $image->id;
                    $user->signature_base64 = $base_64_sign;
                }
                
            }
        } 
        $points_data = json_decode($request->points);
        if (isset($points_data)) {
            if ($points_data != null) {
                $rows_json = [];
                for ($i=0; $i < sizeof($points_data->xValues) ; $i++) { 
                    $aux = [
                        'x' => $points_data->xValues[$i],
                        'y' => $points_data->yValues[$i],
                        't' => $points_data->timeValues[$i],
                        'p' => 0.0,
                    ];
                    array_push($rows_json,json_encode($aux));
                }
                $data_points = json_encode($rows_json);
                $user->points_signature = $data_points;
            }
        }
    
        $user->save();


        $this->checkAuthorized($user->id);
        return response()->json(['status'=>'success','data'=>$user,'msg'=>'Firma actualizada correctamente']);
    }
    public function image(Request $request)
    {
        $id = Auth::user()->id;
        $user = User::find($id);
        if ($request->file('image')) {
            if ($user->image_id_logo != null) {
                Images::delete($user->image_id_logo);
            }
            $user->image_id_logo = Images::save($request->file('image'));
        }
        $user->save();
        return response()->json(['status'=>'success','data'=>$user,'msg'=>'Imagen actualizada correctamente']);
    }
    public function password(Request $request)
    {
         $id = Auth::user()->id;
        $user = User::find($id);
        $user->password = bcrypt($request->npassword);
        $user->save();
        $check = UserApp::find($user->usuarios_id);
        if ($check) {
            $userapp = UserApp::find($user->usuarios_id);
            $userapp->password = md5($request->npassword);
            $userapp->save();
        }
        return response()->json(['status'=>'success','data'=>$user,'msg'=>'Informacion actualizada correctamente']);

    }

    public function store(Request $request)
    {
        
        
        
        ini_set('memory_limit',-1);
        if (isset($request->users_id)) {
            if ($request->users_id != null && $request->users_id != 'undefined' && $request->users_id != 'null') {
                
                $checkuser = User::where('id','!=',$request->users_id)->where('email',$request->email)->first();
                if ($checkuser) {
                    return response()->json(['msg'=>'Ya existe un contacto con el correo ingresado'],500);
                }
                $user = User::find($request->users_id);
                $user->email = $request->email;
                $user->name = isset($request->name) ? $request->name : null;
                $user->lastname = isset($request->lastname) ? $request->lastname : null;
                $user->password = bcrypt($request->password);
                $user->phone = isset($request->phone) ? $request->phone : null;
                if ($request->rfc != null && $request->rfc != 'null') {
                    $user->rfc = $request->rfc;
                }
                if ($request->curp != null && $request->curp != 'null') {
                    $user->curp = $request->curp;
                }
                if ($request->clave_ine != null && $request->clave_ine != 'null') {
                    $user->clave_ine = $request->clave_ine;
                }
                
                $user->access = 1;
                $user->from_web = 1;

                if ($request->business_name) {
                    $user->business_name = $request->business_name;
                }
                if ($request->rfc_company ) {
                    $user->rfc_company = $request->rfc_company;
                }
                $user->email_verified_at = date('Y-m-d H:i:s');
                $user->save();

                $check_userapp = UserApp::find($user->usuarios_id);
                if ($check_userapp) {
                    $userapp = UserApp::find($check_userapp->id);
                    $userapp->nombre = $request->name.' '.(isset($request->lastname) ? $request->lastname : null);
                    $userapp->email = $request->email;
                    $userapp->estado_id = isset($request->state_id) ? $request->state_id : null;
                    $userapp->ciudad_id = isset($request->town_id) ? $request->town_id : null;
                    $userapp->num_ext = isset($request->num_ext) ? $request->num_ext : null;
                    $userapp->num_int = isset($request->num_int) ? $request->num_int : null;
                    $userapp->colonia = isset($request->neighborhood) ? $request->neighborhood : null;
                    $userapp->cp = isset($request->zipcode) ? $request->zipcode : null;
                    $userapp->domicilio = isset($request->street) ? $request->street : null;
                    $userapp->tipo = 'usuario';
                    $userapp->password = md5($request->password);
                    $userapp->save();

                    $user->usuarios_id = $userapp->id;
                    $user->status = 'completado';
                    $user->save();
                }
                else{
                    $userapp = new UserApp();
                    $userapp->nombre = $user->name.' '.(isset($user->lastname) ? $request->lastname : null);
                    $userapp->email = $user->email;
                    $userapp->estado_id = isset($request->state_id) ? $request->state_id : null;
                    $userapp->ciudad_id = isset($request->town_id) ? $request->town_id : null;
                    $userapp->num_ext = isset($request->num_ext) ? $request->num_ext : null;
                    $userapp->num_int = isset($request->num_int) ? $request->num_int : null;
                    $userapp->colonia = isset($request->neighborhood) ? $request->neighborhood : null;
                    $userapp->cp = isset($request->zipcode) ? $request->zipcode : null;
                    $userapp->domicilio = isset($request->street) ? $request->street : null;
                    $userapp->tipo = 'usuario';
                    $userapp->password = md5($request->password);
                    $userapp->save();

                    $user->usuarios_id = $userapp->id;
                    $user->status = 'completado';
                    $user->save();
                }
                


                if ($request->file('rfc_file')) {
                    $user->rfc_documents_id = Documents::save($request->file('rfc_file'));
                }

                if ($request->file('curp_file')) {
                    $user->curp_documents_id = Documents::save($request->file('curp_file'));
                }

                if ($request->file('inefront')) {
                    $user->inefront_documents_id = Documents::save($request->file('inefront'));
                }

                if ($request->file('ineback')) {
                    $user->ineback_documents_id = Documents::save($request->file('ineback'));
                }

                if ($request->file('image')) {
                    $user->image_id = Images::save($request->file('image'));
                }

                if ($request->file('rfc_file_company')) {
                    $user->rfc_company_documents_id = Documents::save($request->file('rfc_file_company'));
                }
                if ($request->file('constitutive_act')) {
                    $user->constitutive_act_company_documents_id = Documents::save($request->file('constitutive_act'));
                }
                /* if ($request->file('image')) {
                    $user->image_id = Images::save($request->file('image'));
                }*/
                if (isset($request->signature)) {
                    if ($request->signature != null && $request->signature != false && $request->signature != 'false') {
                        $data = $request->signature;
                        list($type, $data) = explode(';', $data);
                        list(, $data)      = explode(',', $data);
                        $base_64_sign = $data; 
                        $data = base64_decode($data);
                                    
                        Storage::disk('public')->put('imagestmp/imageone'.$user->id.'.png',$data);
                        $dir = base_path().'/storage/app/public/imagestmp';
                        $file = 'imageone'.$user->id.'.png';
                        $content = file_get_contents($dir.'/'. $file,  FILE_USE_INCLUDE_PATH);
                        if($content){
                                Storage::disk('public')->put('photos/'.$file, $content);
                                $image = new Image(array(
                                    "path"=> 'photos/'.$file,
                                    "disk"=> 'public',
                                    "key"=>uniqid(),
                                ));
                                $image->save(); 
                            $user->signature_image_id = $image->id;
                            $user->signature_base64 = $base_64_sign;
                        }
                    }
                } 
                

                $points_data = json_decode($request->points);
                if (isset($points_data)) {
                    if ($points_data != null) {
                        $rows_json = [];
                        for ($i=0; $i < sizeof($points_data->xValues) ; $i++) { 
                            $aux = [
                                'x' => $points_data->xValues[$i],
                                'y' => $points_data->yValues[$i],
                                't' => $points_data->timeValues[$i],
                                'p' => 0.0,
                            ];
                            array_push($rows_json,json_encode($aux));
                        }
                        $data_points = json_encode($rows_json);
                        $user->points_signature = $data_points;
                        
                    }
                }
        


                $user->save();
                $user->syncRoles('cliente');

                $customer = Address::where('user_id',$request->users_id)->first();
                $customer->user_id = $user->id;
                $customer->street = $request->street;
                $customer->num_ext = $request->num_ext;
                $customer->num_int = $request->num_int;
                $customer->neighborhood = $request->neighborhood;
                $customer->zipcode = $request->zipcode;
                $customer->state_id = $request->state_id;
                $customer->town_id = $request->town_id;

                if (isset($request->street_company)) {
                    if ($request->street_company != 'null' && $request->street_company != null && $request->street_company != 'undefined') {
                        $customer->street_company = $request->street_company;
                    }
                    else{
                        $customer->street_company = null;
                    }
                }
                else{
                    $customer->street_company = null;
                }
                
                if (isset($request->num_ext_company)) {
                    if ($request->num_ext_company != 'null' && $request->num_ext_company != null && $request->num_ext_company != 'undefined') {
                        $customer->num_ext_company = $request->num_ext_company;
                    }
                    else{
                        $customer->num_ext_company = null;
                    }
                }
                else{
                    $customer->num_ext_company = null;
                }   

                if (isset($request->num_ext_company)) {
                    if ($request->num_int_company != 'null' && $request->num_int_company != null && $request->num_int_company != 'undefined') {
                        $customer->num_int_company = $request->num_int_company;
                    }
                    else{
                        $customer->num_int_company = null;
                    }
                }
                else{
                    $customer->num_int_company = null;
                }

                if (isset($request->neighborhood_company)) {
                    if ($request->neighborhood_company != 'null' && $request->neighborhood_company != null && $request->neighborhood_company != 'undefined') {
                        $customer->neighborhood_company = $request->neighborhood_company;
                    }
                    else{
                        $customer->neighborhood_company = null;
                    }
                }
                else{
                    $customer->neighborhood_company = null;
                }

                if (isset($request->zipcode_company)) {
                    if ($request->zipcode_company != 'null' && $request->zipcode_company != null && $request->zipcode_company != 'undefined') {
                        $customer->zipcode_company = $request->zipcode_company;
                    }
                    else{
                        $customer->zipcode_company = null;
                    }
                }
                else{
                    $customer->zipcode_company = null;
                }

                if (isset($request->state_id_company)) {
                    if ($request->state_id_company != 'null' && $request->state_id_company != null && $request->state_id_company != 'undefined') {
                        $customer->state_id_company = $request->state_id_company;
                    }
                    else{
                        $customer->state_id_company = null;
                    }
                }
                else{
                    $customer->state_id_company = null;
                }

                if (isset($request->town_id_company)) {
                    if ($request->town_id_company != 'null' && $request->town_id_company != null  && $request->town_id_company != 'undefined') {
                        $customer->town_id_company = $request->town_id_company;
                    }
                    else{
                        $customer->town_id_company = null;
                    }
                }
                else{
                    $customer->town_id_company = null;
                }
                

                $customer->save();

                return response()->json($user);
            }

        }
        
        $checkuser = User::where('email',$request->email)->first();
        if ($checkuser) {
            //if ($checkuser->status == 'pendiente') {
                $user = User::find($checkuser->id);
                $user->email = $request->email;
                $user->name = isset($request->name) ? $request->name : null;
                $user->lastname = isset($request->lastname) ? $request->lastname : null;
                $user->password = bcrypt($request->password);
                $user->phone = isset($request->phone) ? $request->phone : null;
                if ($request->rfc != null && $request->rfc != 'null') {
                    $user->rfc = $request->rfc;
                }
                if ($request->curp != null && $request->curp != 'null') {
                    $user->curp = $request->curp;
                }
                if ($request->clave_ine != null && $request->clave_ine != 'null') {
                    $user->clave_ine = $request->clave_ine;
                }
                
                $user->access = 1;
                $user->from_web = 1;

                if ($request->business_name) {
                    $user->business_name = $request->business_name;
                }
                if ($request->rfc_company ) {
                    $user->rfc_company = $request->rfc_company;
                }
                $user->email_verified_at = date('Y-m-d H:i:s');
                $user->save();

                $check_userapp = UserApp::find($user->usuarios_id);
                if ($check_userapp) {
                    $userapp = UserApp::find($check_userapp->id);
                    $userapp->nombre = $request->name.' '.(isset($request->lastname) ? $request->lastname : null);
                    $userapp->email = $request->email;
                    $userapp->estado_id = isset($request->state_id) ? $request->state_id : null;
                    $userapp->ciudad_id = isset($request->town_id) ? $request->town_id : null;
                    $userapp->num_ext = isset($request->num_ext) ? $request->num_ext : null;
                    $userapp->num_int = isset($request->num_int) ? $request->num_int : null;
                    $userapp->colonia = isset($request->neighborhood) ? $request->neighborhood : null;
                    $userapp->cp = isset($request->zipcode) ? $request->zipcode : null;
                    $userapp->domicilio = isset($request->street) ? $request->street : null;
                    $userapp->tipo = 'usuario';
                    $userapp->password = md5($request->password);
                    $userapp->save();

                    $user->usuarios_id = $userapp->id;
                    $user->status = 'completado';
                    $user->save();
                }
                else{
                    $userapp = new UserApp();
                    $userapp->nombre = $request->name.' '.(isset($request->lastname) ? $request->lastname : null);
                    $userapp->email = $request->email;
                    $userapp->estado_id = isset($request->state_id) ? $request->state_id : null;
                    $userapp->ciudad_id = isset($request->town_id) ? $request->town_id : null;
                    $userapp->num_ext = isset($request->num_ext) ? $request->num_ext : null;
                    $userapp->num_int = isset($request->num_int) ? $request->num_int : null;
                    $userapp->colonia = isset($request->neighborhood) ? $request->neighborhood : null;
                    $userapp->cp = isset($request->zipcode) ? $request->zipcode : null;
                    $userapp->domicilio = isset($request->street) ? $request->street : null;
                    $userapp->tipo = 'usuario';
                    $userapp->password = md5($request->password);
                    $userapp->save();

                    $user->usuarios_id = $userapp->id;
                    $user->status = 'completado';
                    $user->save();
                }
                


                if ($request->file('rfc_file')) {
                    $user->rfc_documents_id = Documents::save($request->file('rfc_file'));
                }

                if ($request->file('curp_file')) {
                    $user->curp_documents_id = Documents::save($request->file('curp_file'));
                }

                if ($request->file('inefront')) {
                    $user->inefront_documents_id = Documents::save($request->file('inefront'));
                }

                if ($request->file('ineback')) {
                    $user->ineback_documents_id = Documents::save($request->file('ineback'));
                }

                if ($request->file('image')) {
                    $user->image_id = Images::save($request->file('image'));
                }

                if ($request->file('rfc_file_company')) {
                    $user->rfc_company_documents_id = Documents::save($request->file('rfc_file_company'));
                }
                if ($request->file('constitutive_act')) {
                    $user->constitutive_act_company_documents_id = Documents::save($request->file('constitutive_act'));
                }
                /* if ($request->file('image')) {
                    $user->image_id = Images::save($request->file('image'));
                }*/
                if (isset($request->signature)) {
                    if ($request->signature != null && $request->signature != false && $request->signature != 'false') {
                        $data = $request->signature;
                        list($type, $data) = explode(';', $data);
                        list(, $data)      = explode(',', $data);
                        $base_64_sign = $data; 
                        $data = base64_decode($data);
                                    
                        Storage::disk('public')->put('imagestmp/imageone'.$user->id.'.png',$data);
                        $dir = base_path().'/storage/app/public/imagestmp';
                        $file = 'imageone'.$user->id.'.png';
                        $content = file_get_contents($dir.'/'. $file,  FILE_USE_INCLUDE_PATH);
                        if($content){
                                Storage::disk('public')->put('photos/'.$file, $content);
                                $image = new Image(array(
                                    "path"=> 'photos/'.$file,
                                    "disk"=> 'public',
                                    "key"=>uniqid(),
                                ));
                                $image->save(); 
                            $user->signature_image_id = $image->id;
                            $user->signature_base64 = $base_64_sign;
                        }
                    }
                } 
                

                $points_data = json_decode($request->points);
                if (isset($points_data)) {
                    if ($points_data != null) {
                        $rows_json = [];
                        for ($i=0; $i < sizeof($points_data->xValues) ; $i++) { 
                            $aux = [
                                'x' => $points_data->xValues[$i],
                                'y' => $points_data->yValues[$i],
                                't' => $points_data->timeValues[$i],
                                'p' => 0.0,
                            ];
                            array_push($rows_json,json_encode($aux));
                        }
                        $data_points = json_encode($rows_json);
                        $user->points_signature = $data_points;
                        
                    }
                }
        


                $user->save();
                $user->syncRoles('cliente');

                $customer = Address::where('user_id',$user->users_id)->first();
                if ($customer) {
                    $customer->user_id = $user->id;
                    $customer->street = $request->street;
                    $customer->num_ext = $request->num_ext;
                    $customer->num_int = $request->num_int;
                    $customer->neighborhood = $request->neighborhood;
                    $customer->zipcode = $request->zipcode;
                    $customer->state_id = $request->state_id;
                    $customer->town_id = $request->town_id;

                    if (isset($request->street_company)) {
                        if ($request->street_company != 'null' && $request->street_company != null && $request->street_company != 'undefined') {
                            $customer->street_company = $request->street_company;
                        }
                        else{
                            $customer->street_company = null;
                        }
                    }
                    else{
                        $customer->street_company = null;
                    }
                    
                    if (isset($request->num_ext_company)) {
                        if ($request->num_ext_company != 'null' && $request->num_ext_company != null && $request->num_ext_company != 'undefined') {
                            $customer->num_ext_company = $request->num_ext_company;
                        }
                        else{
                            $customer->num_ext_company = null;
                        }
                    }
                    else{
                        $customer->num_ext_company = null;
                    }   

                    if (isset($request->num_ext_company)) {
                        if ($request->num_int_company != 'null' && $request->num_int_company != null && $request->num_int_company != 'undefined') {
                            $customer->num_int_company = $request->num_int_company;
                        }
                        else{
                            $customer->num_int_company = null;
                        }
                    }
                    else{
                        $customer->num_int_company = null;
                    }

                    if (isset($request->neighborhood_company)) {
                        if ($request->neighborhood_company != 'null' && $request->neighborhood_company != null && $request->neighborhood_company != 'undefined') {
                            $customer->neighborhood_company = $request->neighborhood_company;
                        }
                        else{
                            $customer->neighborhood_company = null;
                        }
                    }
                    else{
                        $customer->neighborhood_company = null;
                    }

                    if (isset($request->zipcode_company)) {
                        if ($request->zipcode_company != 'null' && $request->zipcode_company != null && $request->zipcode_company != 'undefined') {
                            $customer->zipcode_company = $request->zipcode_company;
                        }
                        else{
                            $customer->zipcode_company = null;
                        }
                    }
                    else{
                        $customer->zipcode_company = null;
                    }

                    if (isset($request->state_id_company)) {
                        if ($request->state_id_company != 'null' && $request->state_id_company != null && $request->state_id_company != 'undefined') {
                            $customer->state_id_company = $request->state_id_company;
                        }
                        else{
                            $customer->state_id_company = null;
                        }
                    }
                    else{
                        $customer->state_id_company = null;
                    }

                    if (isset($request->town_id_company)) {
                        if ($request->town_id_company != 'null' && $request->town_id_company != null  && $request->town_id_company != 'undefined') {
                            $customer->town_id_company = $request->town_id_company;
                        }
                        else{
                            $customer->town_id_company = null;
                        }
                    }
                    else{
                        $customer->town_id_company = null;
                    }
                    

                    $customer->save();
                }
                else{
                    $customer = new Address();
                    $customer->user_id = $user->id;
                    $customer->street = $request->street;
                    $customer->num_ext = $request->num_ext;
                    $customer->num_int = $request->num_int;
                    $customer->neighborhood = $request->neighborhood;
                    $customer->zipcode = $request->zipcode;
                    $customer->state_id = $request->state_id;
                    $customer->town_id = $request->town_id;

                    if (isset($request->street_company)) {
                        if ($request->street_company != 'null' && $request->street_company != null && $request->street_company != 'undefined') {
                            $customer->street_company = $request->street_company;
                        }
                        else{
                            $customer->street_company = null;
                        }
                    }
                    else{
                        $customer->street_company = null;
                    }
                    
                    if (isset($request->num_ext_company)) {
                        if ($request->num_ext_company != 'null' && $request->num_ext_company != null && $request->num_ext_company != 'undefined') {
                            $customer->num_ext_company = $request->num_ext_company;
                        }
                        else{
                            $customer->num_ext_company = null;
                        }
                    }
                    else{
                        $customer->num_ext_company = null;
                    }   

                    if (isset($request->num_ext_company)) {
                        if ($request->num_int_company != 'null' && $request->num_int_company != null && $request->num_int_company != 'undefined') {
                            $customer->num_int_company = $request->num_int_company;
                        }
                        else{
                            $customer->num_int_company = null;
                        }
                    }
                    else{
                        $customer->num_int_company = null;
                    }

                    if (isset($request->neighborhood_company)) {
                        if ($request->neighborhood_company != 'null' && $request->neighborhood_company != null && $request->neighborhood_company != 'undefined') {
                            $customer->neighborhood_company = $request->neighborhood_company;
                        }
                        else{
                            $customer->neighborhood_company = null;
                        }
                    }
                    else{
                        $customer->neighborhood_company = null;
                    }

                    if (isset($request->zipcode_company)) {
                        if ($request->zipcode_company != 'null' && $request->zipcode_company != null && $request->zipcode_company != 'undefined') {
                            $customer->zipcode_company = $request->zipcode_company;
                        }
                        else{
                            $customer->zipcode_company = null;
                        }
                    }
                    else{
                        $customer->zipcode_company = null;
                    }

                    if (isset($request->state_id_company)) {
                        if ($request->state_id_company != 'null' && $request->state_id_company != null && $request->state_id_company != 'undefined') {
                            $customer->state_id_company = $request->state_id_company;
                        }
                        else{
                            $customer->state_id_company = null;
                        }
                    }
                    else{
                        $customer->state_id_company = null;
                    }

                    if (isset($request->town_id_company)) {
                        if ($request->town_id_company != 'null' && $request->town_id_company != null  && $request->town_id_company != 'undefined') {
                            $customer->town_id_company = $request->town_id_company;
                        }
                        else{
                            $customer->town_id_company = null;
                        }
                    }
                    else{
                        $customer->town_id_company = null;
                    }
                    

                    $customer->save();
                }
                

                return response()->json($user);
            //}
            //else{
                //return response()->json(['msg'=>'Ya existe un contacto con el correo ingresado.'],500);
            //}
            
        }
        
        $user = new User();
        $user->email = $request->email;
        $user->name = isset($request->name) ? $request->name : null;
        $user->lastname = isset($request->lastname) ? $request->lastname : null;
        $user->password = bcrypt($request->password);
        $user->phone = isset($request->phone) ? $request->phone : null;
        if ($request->rfc != null && $request->rfc != 'null') {
            $user->rfc = $request->rfc;
        }
        if ($request->curp != null && $request->curp != 'null') {
            $user->curp = $request->curp;
        }
        if ($request->clave_ine != null && $request->clave_ine != 'null') {
            $user->clave_ine = $request->clave_ine;
        }
        
        $user->access = 1;
        $user->from_web = 1;
        if ($request->business_name) {
            $user->business_name = $request->business_name;
        }
        if ($request->rfc_company ) {
            $user->rfc_company = $request->rfc_company;
        }
        
        $user->email_verified_at = date('Y-m-d H:i:s');
        $user->save();

        $userapp = new UserApp();
        $userapp->nombre = $request->name.' '.(isset($request->lastname) ? $request->lastname : null);
        $userapp->email = $request->email;
        if ($request->state_id != 'null' && $request->state_id != null && $request->state_id != 'undefined') {
            $userapp->estado_id = $request->state_id;
        }
        else{
            $userapp->estado_id = null;
        }
        if ($request->town_id != 'null' && $request->town_id != null && $request->town_id != 'undefined') {
            $userapp->ciudad_id = $request->town_id;
        }
        else{
            $userapp->ciudad_id = null;
        }

        if ($request->num_ext != 'null' && $request->num_ext != null && $request->num_ext != 'undefined') {
            $userapp->num_ext = $request->num_ext;
        }
        else{
            $userapp->num_ext = null;
        }
        if ($request->num_int != 'null' && $request->num_int != null && $request->num_int != 'undefined') {
            $userapp->num_int = $request->num_int;
        }
        else{
            $userapp->num_int = null;
        }
        if ($request->neighborhood != 'null' && $request->neighborhood != null && $request->neighborhood != 'undefined') {
            $userapp->colonia = $request->neighborhood;
        }
        else{
            $userapp->colonia = null;
        }
        if ($request->zipcode != 'null' && $request->zipcode != null && $request->zipcode != 'undefined') {
            $userapp->cp = $request->zipcode;
        }
        else{
            $userapp->cp = null;
        }
        if ($request->street != 'null' && $request->street != null && $request->street != 'undefined') {
            $userapp->domicilio = $request->street;
        }
        else{
            $userapp->domicilio = null;
        }
        $userapp->tipo = 'usuario';
        $userapp->password = md5($request->password);
        $userapp->save();

        $user->usuarios_id = $userapp->id;
        $user->status = 'completado';
        $user->save();


        if ($request->file('rfc_file')) {
            $user->rfc_documents_id = Documents::save($request->file('rfc_file'));
        }

        if ($request->file('curp_file')) {
            $user->curp_documents_id = Documents::save($request->file('curp_file'));
        }

        if ($request->file('inefront')) {
            $user->inefront_documents_id = Documents::save($request->file('inefront'));
        }

        if ($request->file('ineback')) {
            $user->ineback_documents_id = Documents::save($request->file('ineback'));
        }

        if ($request->file('image')) {
            $user->image_id = Images::save($request->file('image'));
        }

  
        if ($request->file('rfc_file_company')) {
            $user->rfc_company_documents_id = Documents::save($request->file('rfc_file_company'));
        }
        if ($request->file('constitutive_act')) {
            $user->constitutive_act_company_documents_id = Documents::save($request->file('constitutive_act'));
        }

      
    
     
        if (isset($request->signature)) {
            if ($request->signature != null && $request->signature != false && $request->signature != 'false') {
                $data = $request->signature;
                list($type, $data) = explode(';', $data);
                list(, $data)      = explode(',', $data);
                $base_64_sign = $data; 
                $data = base64_decode($data);
                            
                Storage::disk('public')->put('imagestmp/imageone'.$user->id.'.png',$data);
                $dir = base_path().'/storage/app/public/imagestmp';
                $file = 'imageone'.$user->id.'.png';
                $content = file_get_contents($dir.'/'. $file,  FILE_USE_INCLUDE_PATH);
                if($content){
                        Storage::disk('public')->put('photos/'.$file, $content);
                        $image = new Image(array(
                            "path"=> 'photos/'.$file,
                            "disk"=> 'public',
                            "key"=>uniqid(),
                        ));
                        $image->save(); 
                    $user->signature_image_id = $image->id;
                    $user->signature_base64 = $base_64_sign;
                }
            }
        } 
        

        $points_data = json_decode($request->points);
        if (isset($points_data)) {
            if ($points_data != null) {
                $rows_json = [];
                for ($i=0; $i < sizeof($points_data->xValues) ; $i++) { 
                    $aux = [
                        'x' => $points_data->xValues[$i],
                        'y' => $points_data->yValues[$i],
                        't' => $points_data->timeValues[$i],
                        'p' => 0.0,
                    ];
                    array_push($rows_json,json_encode($aux));
                }
                $data_points = json_encode($rows_json);
                $user->points_signature = $data_points;
                
            }
        }


        $user->save();
        $user->syncRoles('cliente');

        $customer = new Address();
        $customer->user_id = $user->id;
        if ($request->street != 'null' && $request->street != null && $request->street != 'undefined') {
            $customer->street = $request->street;
        }
        else{
            $customer->street = null;
        }
        
        if ($request->num_ext != 'null' && $request->num_ext != null && $request->num_ext != 'undefined') {
            $customer->num_ext = $request->num_ext;
        }
        else{
            $customer->num_ext = null;
        }
        if ($request->num_ext != 'null' && $request->num_ext != null && $request->num_ext != 'undefined') {
            $customer->num_ext = $request->num_ext;
        }
        else{
            $customer->num_ext = null;
        }
        if ($request->num_int != 'null' && $request->num_int != null && $request->num_int != 'undefined') {
            $customer->num_int = $request->num_int;
        }
        else{
            $customer->num_int = null;
        }
        
        if ($request->neighborhood != 'null' && $request->neighborhood != null && $request->neighborhood != 'undefined') {
            $customer->neighborhood = $request->neighborhood;
        }
        else{
            $customer->neighborhood = null;
        }

        if ($request->zipcode != 'null' && $request->zipcode != null && $request->zipcode != 'undefined') {
            $customer->zipcode = $request->zipcode;
        }
        else{
            $customer->zipcode = null;
        }

        if ($request->state_id != 'null' && $request->state_id != null && $request->state_id != 'undefined') {
            $customer->state_id = $request->state_id;
        }
        else{
            $customer->state_id = null;
        }

        if ($request->town_id != 'null' && $request->town_id != null && $request->town_id != 'undefined') {
            $customer->town_id = $request->town_id;
        }
        else{
            $customer->town_id = null;
        }
        if ($request->street_company != 'null' && $request->street_company != null && $request->street_company != 'undefined') {
            $customer->street_company = $request->street_company;
        }
        else{
            $customer->street_company = null;
        }

        if ($request->num_ext_company != 'null' && $request->num_ext_company != null && $request->num_ext_company != 'undefined') {
            $customer->num_ext_company = $request->num_ext_company;
        }
        else{
            $customer->num_ext_company = null;
        }

        if ($request->num_int_company != 'null' && $request->num_int_company != null && $request->num_int_company != 'undefined') {
            $customer->num_int_company = $request->num_int_company;
        }
        else{
            $customer->num_int_company = null;
        }
        if ($request->neighborhood_company != 'null' && $request->neighborhood_company != null && $request->neighborhood_company != 'undefined') {
            $customer->neighborhood_company = $request->neighborhood_company;
        }
        else{
            $customer->neighborhood_company = null;
        }
        if ($request->zipcode_company != 'null' && $request->zipcode_company != null && $request->zipcode_company != 'undefined') {
            $customer->zipcode_company = $request->zipcode_company;
        }
        else{
            $customer->zipcode_company = null;
        }

        if ($request->state_id_company != 'null' && $request->state_id_company != null && $request->state_id_company != 'undefined') {
            $customer->state_id_company = $request->state_id_company;
        }
        else{
            $customer->state_id_company = null;
        }

        if ($request->town_id_company != 'null' && $request->town_id_company != null && $request->town_id_company != 'undefined') {
            $customer->town_id_company = $request->town_id_company;
        }
        else{
            $customer->town_id_company = null;
        }

        
        $customer->save();
        
        try{
           // Mail::to($user->email)->send(new ConfirmEmail( $user));
           
        }catch (\Exception $e) {
            //report($e);
        }
        


        return response()->json($user);

    }

    //contactos

    public function contactsUser()
    {
        $users_id = UsersContact::where('user_id',Auth::user()->id)->pluck('contact_user_id');

        $users = User::whereIn('id',$users_id)->orderBy('id','desc')->get();
        foreach ($users as $key => $value) {
            $value->address;
            if ($value->image_id != null) {
                $value->imageUrl = Images::getUrl($value->image_id);
            }
            else{
                $value->imageUrl = Images::getUrl(1);

            }
            $value->user_contact_id = UsersContact::where('user_id',Auth::user()->id)->where('contact_user_id',$value->id)->first()->id;
        }

        return response()->json($users);
    }
    public function checkContact(Request $request)
    {
        $checkuser = User::where('email',$request->email)->first();
        if ($checkuser) {
            $checkuser->status = 'ok';
            return response()->json($checkuser);
        }
        else{
            return response()->json([]);
        }
    }
    public function storeContact(Request $request)
    {   
        $checkuser = User::where('email',$request->email)->first();
        if ($checkuser) {

            $contact = new UsersContact();
            $contact->user_id = Auth::user()->id;
            $contact->contact_user_id = $checkuser->id;
            $contact->save();

            if ($checkuser->status != 'completado') {
                $user = User::find($checkuser->id);
                $user->email = $request->email;
                $user->name = $request->name;
                $user->phone = $request->phone;
                $user->save();
            }
            return response()->json($checkuser);
        }
        else{

            $user = new User();
            $user->email = $request->email;
            $user->name = $request->name;
            $user->lastname = $request->lastname;
            $user->password = bcrypt($request->password);
            $user->phone = $request->phone;
            $user->rfc = $request->rfc;
            $user->curp = $request->curp;
            $user->clave_ine = $request->clave_ine;
            $user->access = 1;
            $user->email_verified_at = date('Y-m-d H:i:s');
            $user->status = 'completado';
            $user->save();
            $user->syncRoles('cliente');

            $customer = new Address();
            $customer->user_id = $user->id;
            $customer->street = $request->street;
            $customer->num_ext = $request->num_ext;
            $customer->num_int = $request->num_int;
            $customer->neighborhood = $request->neighborhood;
            $customer->zipcode = $request->zipcode;
            $customer->state_id = $request->state_id;
            $customer->town_id = $request->town_id;
            $customer->save();

            $contact = new UsersContact();
            $contact->user_id = Auth::user()->id;
            $contact->contact_user_id = $user->id;
            $contact->save();

            return response()->json($user);
        }

        
    }

    public function mycontracts()
    {
        ini_set('memory_limit',-1);
        $id = Auth::user()->id;

        //contratos que realizo el usuario y espera la firma de otro
        $contractsdata = Order::select('orders.id','orders.total','orders.subtotal','orders.stamp','orders.certificate','orders.status','orders.type_contracts_id','orders.contracts_id','orders.documents_id','orders.user_id','orders.signature_user_id','orders.date_signature_user','orders.header_format','orders.medio_format','orders.inferior_format','orders.images_id','orders.image_name','orders.pdf_id','orders.pdf_name','orders.no_signature_creator','orders.id_cancel','orders.date_cancel','orders.multilateralId','orders.signature_hexHash','orders.signature_hash','orders.java_hash','orders.sequence','orders.nom_base64','orders.stripe_link','orders.company_amha','contracts.title as contrac_name','users.name as user_name')
                                ->leftJoin('contracts', 'orders.contracts_id', '=', 'contracts.id')
                                ->leftJoin('users', 'orders.user_id', '=', 'users.id')
                                ->join('order_contacts', 'orders.id', '=', 'order_contacts.order_id')
                                ->where('orders.user_id',$id)
                                ->orderBy('orders.id','desc')
                                ->get();
        $contracts = [];
        foreach ($contractsdata as $key => $value) {
            $count = OrderContact::where('order_id',$value->id)->where('signature_user_contra_id',null)->count();
           
            if ($count > 0) {
                if($value->created_at ){
                    $value->created = $value->created_at->format('d-m-Y H:i:s');
                }
                $users_contra = OrderContact::select('order_contacts.*','users.name','users.email')->join('users', 'order_contacts.user_id', '=', 'users.id')->where('order_contacts.order_id',$value->id)->get();
                foreach ($users_contra as $keyc => $valuec) {
                    if($valuec->date_signature_user != null){
                        $valuec->date_signature_user = date('d-m-Y H:i:s',strtotime($valuec->date_signature_user));
                    }
                }
                $value->users_contra = $users_contra;
                $value->date_signature_user = date('d-m-Y H:i:s',strtotime($value->date_signature_user));
                $value->documentUrl = Documents::getUrl($value->documents_id);

                /*if($value->type_contracts_id == 0){
                    $value->contrac_name = 'Libre';
                }*/
                switch ($value->type_contracts_id) {
                    case '2':
                        $value->contrac_name = 'Crea un contrato desde 0';
                        break;
                    case '3':
                        $value->contrac_name = 'Llena un formato y certificalo';
                        break;
                    case '4':
                        $value->contrac_name = 'Certifica una imagen o documento existente';
                        break;
                }
                array_push($contracts,$value);
            }
        }
        usort($contracts, function ($item1, $item2) {
            return $item2['id'] <=> $item1['id'];
        });
        //por firmar
        $contractsdosdata = Order::select('orders.id','orders.total','orders.subtotal','orders.stamp','orders.certificate','orders.status','orders.type_contracts_id','orders.contracts_id','orders.documents_id','orders.user_id','orders.signature_user_id','orders.date_signature_user','orders.header_format','orders.medio_format','orders.inferior_format','orders.images_id','orders.image_name','orders.pdf_id','orders.pdf_name','orders.no_signature_creator','orders.id_cancel','orders.date_cancel','orders.multilateralId','orders.signature_hexHash','orders.signature_hash','orders.java_hash','orders.sequence','orders.nom_base64','orders.stripe_link','orders.company_amha','orders.created_at','contracts.title as contrac_name','users.name as user_name')
                                ->leftJoin('contracts', 'orders.contracts_id', '=', 'contracts.id')
                                ->leftJoin('users', 'orders.user_id', '=', 'users.id')
                                ->join('order_contacts', 'orders.id', '=', 'order_contacts.order_id')
                                ->where('order_contacts.user_id',$id)
                                ->orderBy('orders.id','desc')
                                ->get();
        $contractsdos = [];
        foreach ($contractsdosdata as $key => $value) {
            $count = OrderContact::where('order_id',$value->id)->where('signature_user_contra_id',null)->count();
            
            if ($count > 0) {
                $value->created = $value->created_at->format('d-m-Y H:i:s');
                
                $value->date_signature_user = date('d-m-Y H:i:s',strtotime($value->date_signature_user));
                $value->documentUrl = Documents::getUrl($value->documents_id);
                $users_contra = OrderContact::select('order_contacts.*','users.name','users.email')->join('users', 'order_contacts.user_id', '=', 'users.id')->where('order_contacts.order_id',$value->id)->get();
                foreach ($users_contra as $keyc => $valuec) {
                    if($valuec->date_signature_user != null){
                        $valuec->date_signature_user = date('d-m-Y H:i:s',strtotime($valuec->date_signature_user));
                    }
                }
                $value->users_contra = $users_contra;

                $users_contrame = OrderContact::where('order_id',$value->id)->where('user_id',$id)->first();
                
                if ($users_contrame->signature_user_contra_id != null) {
                    $value->status = 'Completado';
                }
                else{
                    $value->status = 'Pendiente de firma';
                }
                switch ($value->type_contracts_id) {
                    case '2':
                        $value->contrac_name = 'Crea un contrato desde 0';
                        break;
                    case '3':
                        $value->contrac_name = 'Llena un formato y certificalo';
                        break;
                    case '4':
                        $value->contrac_name = 'Certifica una imagen o documento existente';
                        break;
                }
                
                /*if($value->contracts_id == 0){
                    $value->contrac_name = 'Libre';
                }*/
                array_push($contractsdos,$value);
            }
        }
        usort($contractsdos, function ($item1, $item2) {
            return $item2['id'] <=> $item1['id'];
        });
      
        $contractcomplete = Order::select('orders.id','orders.total','orders.subtotal','orders.stamp','orders.certificate','orders.status','orders.type_contracts_id','orders.contracts_id','orders.documents_id','orders.user_id','orders.signature_user_id','orders.date_signature_user','orders.header_format','orders.medio_format','orders.inferior_format','orders.images_id','orders.image_name','orders.pdf_id','orders.pdf_name','orders.no_signature_creator','orders.id_cancel','orders.date_cancel','orders.multilateralId','orders.signature_hexHash','orders.signature_hash','orders.java_hash','orders.sequence','orders.nom_base64','orders.stripe_link','orders.company_amha','orders.created_at','contracts.title as contrac_name','users.name as user_name')
                                ->leftJoin('contracts', 'orders.contracts_id', '=', 'contracts.id')
                                ->leftJoin('users', 'orders.user_id', '=', 'users.id')
                                ->leftJoin('order_contacts', 'orders.id', '=', 'order_contacts.order_id')
                                ->where('orders.user_id',$id)
                                ->where('orders.status','!=','cancelado')
                                ->orderBy('orders.id','desc')
                                ->get();
        
        $contractcompletedos = Order::select('orders.id','orders.total','orders.subtotal','orders.stamp','orders.certificate','orders.status','orders.type_contracts_id','orders.contracts_id','orders.documents_id','orders.user_id','orders.signature_user_id','orders.date_signature_user','orders.header_format','orders.medio_format','orders.inferior_format','orders.images_id','orders.image_name','orders.pdf_id','orders.pdf_name','orders.no_signature_creator','orders.id_cancel','orders.date_cancel','orders.multilateralId','orders.signature_hexHash','orders.signature_hash','orders.java_hash','orders.sequence','orders.nom_base64','orders.stripe_link','orders.company_amha','orders.created_at','contracts.title as contrac_name','users.name as user_name')
                                ->leftJoin('contracts', 'orders.contracts_id', '=', 'contracts.id')
                                ->leftJoin('users', 'orders.user_id', '=', 'users.id')
                                ->join('order_contacts', 'orders.id', '=', 'order_contacts.order_id')
                                ->where('order_contacts.user_id',$id)
                                ->where('orders.status','!=','cancelado')
                                ->orderBy('orders.id','desc')
                                ->get();
        $contractcompleteimage = Order::select('orders.id','orders.total','orders.subtotal','orders.stamp','orders.certificate','orders.status','orders.type_contracts_id','orders.contracts_id','orders.documents_id','orders.user_id','orders.signature_user_id','orders.date_signature_user','orders.header_format','orders.medio_format','orders.inferior_format','orders.images_id','orders.image_name','orders.pdf_id','orders.pdf_name','orders.no_signature_creator','orders.id_cancel','orders.date_cancel','orders.multilateralId','orders.signature_hexHash','orders.signature_hash','orders.java_hash','orders.sequence','orders.nom_base64','orders.stripe_link','orders.company_amha','orders.created_at','users.name as user_name')
                                ->where('orders.user_id',$id)
                                ->where('orders.images_id','!=',null)
                                ->where('orders.status','!=','cancelado')
                                ->leftJoin('users', 'orders.user_id', '=', 'users.id')
                                ->orderBy('orders.id','desc')
                                ->get();
      
        $completes = [];
        $completesdataone = $contractcomplete->merge($contractcompletedos);
        $completesdata =  $completesdataone->merge($contractcompleteimage);
        foreach ($completesdata as $key => $value) {

            $count = OrderContact::where('order_id',$value->id)->where('signature_user_contra_id',null)->count();
            if ($count == 0) {

                $value->created = $value->created_at->format('d-m-Y H:i:s');
            
                $users_contra = OrderContact::select('order_contacts.*','users.name','users.email')->join('users', 'order_contacts.user_id', '=', 'users.id')->where('order_contacts.order_id',$value->id)->get();
                foreach ($users_contra as $keyc => $valuec) {
                    if($valuec->date_signature_user != null){
                        $valuec->date_signature_user = date('d-m-Y H:i:s',strtotime($valuec->date_signature_user));
                    }
                }
                $value->users_contra = $users_contra;
                $value->date_signature_user = date('d-m-Y H:i:s',strtotime($value->date_signature_user));
                $value->documentUrl = Documents::getUrl($value->documents_id);
                $value->documentUrlNom = null;
                $value->documentUrlNomData = null;

                if(Storage::disk('public')->exists('docs/contrato-nom'.$value->id.'.nom')){
                    $value->documentUrlNom = 'https://notarionet.com/storage/app/public/docs/contrato-nom'.$value->id.'.nom';
                }
               
                if(Storage::disk('public')->exists('docs/contrato-nom-data'.$value->id.'.pdf')){
                    $value->documentUrlNomData = 'https://notarionet.com/storage/app/public/docs/contrato-nom-data'.$value->id.'.pdf';
                }
                /*if($value->contracts_id == 0){
                    $value->contrac_name = 'Libre';
                    if ($value->images_id != null) {
                        $value->contrac_name = $value->image_name;
                    }
                }*/
                switch ($value->type_contracts_id) {
                    case '2':
                        $value->contrac_name = 'Crea un contrato desde 0';
                        break;
                    case '3':
                        $value->contrac_name = 'Llena un formato y certificalo';
                        break;
                    case '4':
                        $value->contrac_name = 'Certifica una imagen o documento existente';
                        break;
                }

                if ($value->contrac_name == null) {
                    if ($value->images_id != null) {
                        $value->contrac_name = 'Certifica una imagen o documento existente';
                    }
                    elseif ($value->pdf_id != null) {
                        $value->contrac_name = 'Certifica una imagen o documento existente';
                    }
                }
              
                $fold = FoldersOrder::where('orders_id',$value->id)->where('users_id',Auth::user()->id)->first();
                $value->folders_id = null;
                if ($fold) {
                    $value->folders_id = $fold->folders_id;
                    $folder = Folder::find($fold->folders_id);
                    if ($folder) {
                        $value->folder_name = $folder->name;
                    }
                    
                }

                array_push($completes,$value);
            }
        }
        usort($completes, function ($item1, $item2) {
            return $item2['id'] <=> $item1['id'];
        });

        //cacelados

        $contractcancel = Order::select('orders.id','orders.total','orders.subtotal','orders.stamp','orders.certificate','orders.status','orders.type_contracts_id','orders.contracts_id','orders.documents_id','orders.user_id','orders.signature_user_id','orders.date_signature_user','orders.header_format','orders.medio_format','orders.inferior_format','orders.images_id','orders.image_name','orders.pdf_id','orders.pdf_name','orders.no_signature_creator','orders.id_cancel','orders.date_cancel','orders.multilateralId','orders.signature_hexHash','orders.signature_hash','orders.java_hash','orders.sequence','orders.nom_base64','orders.stripe_link','orders.company_amha','orders.created_at','contracts.title as contrac_name','users.name as user_name')
                                ->leftJoin('contracts', 'orders.contracts_id', '=', 'contracts.id')
                                ->leftJoin('users', 'orders.user_id', '=', 'users.id')
                                ->join('order_contacts', 'orders.id', '=', 'order_contacts.order_id')
                                ->where('orders.user_id',$id)
                                ->where('orders.status','cancelado')
                                ->orderBy('orders.id','desc')
                                ->get();
        
        $contractcanceldos = Order::select('orders.id','orders.total','orders.subtotal','orders.stamp','orders.certificate','orders.status','orders.type_contracts_id','orders.contracts_id','orders.documents_id','orders.user_id','orders.signature_user_id','orders.date_signature_user','orders.header_format','orders.medio_format','orders.inferior_format','orders.images_id','orders.image_name','orders.pdf_id','orders.pdf_name','orders.no_signature_creator','orders.id_cancel','orders.date_cancel','orders.multilateralId','orders.signature_hexHash','orders.signature_hash','orders.java_hash','orders.sequence','orders.nom_base64','orders.stripe_link','orders.company_amha','orders.created_at','contracts.title as contrac_name','users.name as user_name')
                                ->leftJoin('contracts', 'orders.contracts_id', '=', 'contracts.id')
                                ->leftJoin('users', 'orders.user_id', '=', 'users.id')
                                ->join('order_contacts', 'orders.id', '=', 'order_contacts.order_id')
                                ->where('order_contacts.user_id',$id)
                                ->where('orders.status','cancelado')
                                ->orderBy('orders.id','desc')
                                ->get();
        $contractcancelimage = Order::select('orders.id','orders.total','orders.subtotal','orders.stamp','orders.certificate','orders.status','orders.type_contracts_id','orders.contracts_id','orders.documents_id','orders.user_id','orders.signature_user_id','orders.date_signature_user','orders.header_format','orders.medio_format','orders.inferior_format','orders.images_id','orders.image_name','orders.pdf_id','orders.pdf_name','orders.no_signature_creator','orders.id_cancel','orders.date_cancel','orders.multilateralId','orders.signature_hexHash','orders.signature_hash','orders.java_hash','orders.sequence','orders.nom_base64','orders.stripe_link','orders.company_amha','orders.created_at','users.name as user_name')
                                ->where('orders.user_id',$id)
                                ->where('orders.images_id','!=',null)
                                ->where('orders.status','cancelado')
                                ->leftJoin('users', 'orders.user_id', '=', 'users.id')
                                ->orderBy('orders.id','desc')
                                ->get();

        $cancel = [];
        $canceldataone = $contractcancel->merge($contractcanceldos);
        $canceldata =  $canceldataone->merge($contractcancelimage);
        foreach ($canceldata as $key => $value) {
                        
            $count = OrderContact::where('order_id',$value->id)->where('signature_user_contra_id',null)->count();
            if ($count == 0) {
                        
                $value->created = $value->created_at->format('d-m-Y H:i:s');
                
                $users_contra = OrderContact::select('order_contacts.*','users.name','users.email')->join('users', 'order_contacts.user_id', '=', 'users.id')->where('order_contacts.order_id',$value->id)->get();
                foreach ($users_contra as $keyc => $valuec) {
                    if($valuec->date_signature_user != null){
                        $valuec->date_signature_user = date('d-m-Y H:i:s',strtotime($valuec->date_signature_user));
                    }
                }
                $value->users_contra = $users_contra;
                $value->date_signature_user = date('d-m-Y H:i:s',strtotime($value->date_signature_user));
                $value->documentUrl = Documents::getUrl($value->documents_id);
                    
                /*if($value->contracts_id == 0){
                    $value->contrac_name = 'Libre';
                    if ($value->images_id != null) {
                        $value->contrac_name = $value->image_name;
                    }
                }*/
                switch ($value->type_contracts_id) {
                    case '2':
                        $value->contrac_name = 'Crea un contrato desde 0';
                        break;
                    case '3':
                        $value->contrac_name = 'Llena un formato y certificalo';
                        break;
                    case '4':
                        $value->contrac_name = 'Certifica una imagen o documento existente';
                        break;
                }
                        
                array_push($cancel,$value);
            }
        }
        usort($cancel, function ($item1, $item2) {
            return $item2['id'] <=> $item1['id'];
        });
        $data = [
            'waiting_signature' => $contracts,
            'pending_signature' => $contractsdos,
            'signatured' => $completes,
            'canceled' => $cancel,
        ];
   
        return response()->json($data);

    }

    public function deleteContact($id)
    {
        
        $row = UsersContact::find($id);
        $row->delete();

        return response()->json('asda');
    }
    public function resetpassword(Request $request)
    {
        $user = User::where('email',$request->email)->first();
        if ($user) {
            $pass = Str::random(8);
            $user->password = bcrypt($pass);
            $user->save();
            $data = [
                'name'=>$user->name,
                'email'=>$user->email,
                'pass'=>$pass
            ];

            $check = UserApp::find($user->usuarios_id);
            if ($check) {
                $userapp = UserApp::find($user->usuarios_id);
                $userapp->password = md5($pass);
                $userapp->save();
            }

            try{
                Mail::to($user->email)->send(new ResetPassword($data));
                
            }catch (\Exception $e) {
               report($e);
            }
            
            return response()->json(['status'=>'success','msg'=>'Contrase単a actualizada correctamente']);
        }
        else{
            return response()->json(['msg'=>'No se encontro usuario con el correo ingresado'],500);
        }
    }

    public function paymentHistory()
    {
        $id = Auth::user()->id;
        $credits = UsersCredit::where('user_id',$id)->orderBy('id','desc')->get();
        foreach ($credits as $key => $value) {
            $value->created = $value->created_at->format('d-m-Y H:i:s');
            $value->total = '$ '.number_format($value->total,2);
            $package = Package::find($value->package_id);
            if ($package) {
                $value->package = $package->name;
            }
        }
        return response()->json($credits);
        
    }
    public function checkAuthorized($users_id)
    {
        $user = User::find($users_id);
        
        $count = 0;
        if ($user->access == 0) {
            if ($user->email == null) {
                
                $count++;
            }
            if ($user->name  == null) {
                $count++;
            }
            if ($user->phone  == null) {
                $count++;
            }
            if ($user->address->street  == null) {
                $count++;
            }

            if ($user->address->num_ext  == null) {
                $count++;
            }
            if ($user->address->neighborhood  == null) {
                $count++;
            }
            if ($user->address->zipcode  == null) {
                $count++;
            }
            if ($user->address->state_id  == null) {
                $count++;
            }
            if ($user->address->town_id  == null) {
                $count++;
            }
            /*if ($user->rfc_documents_id  == null) {
                $count++;
            }
            if ($user->curp_documents_id  == null) {
                $count++;
            }
            if ($user->inefront_documents_id  == null) {
                $count++;
            }
            if ($user->ineback_documents_id  == null) {
                $count++;
            }*/
            if ($user->signature_image_id  == null) {
                $count++;
            }
            
            if ($count == 0) {
                try{
                    Mail::to($user->email)->send(new AuthorizedProccessAccount($user)); 
                }catch (\Exception $e) {
                   report($e);
                }
            }
        }

        return 'ok';
    }

    public function confirmEmail($id)
    {
        $user = User::find($id);
        $user->email_verified_at = date('Y-m-d H:i:s');
        $user->save();

        return 'ok';
    }
    
}
