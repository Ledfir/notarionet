<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserApp;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Contract;
use App\Models\Document;
use App\Models\State;
use App\Models\Town;
use App\Models\OrderContact;

use App\Mail\ContractContraMail;
use App\Mail\ContractMail;
use App\Mail\ContractComplete;
use App\Mail\ContractUpdateMail;

use App\Models\Image;


use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Http;

use Documents;
use Images;
class AmhaController extends Controller
{
    public function store(Request $request) {
        
        ini_set('memory_limit',1);
        $userdata = User::find(196);
        
      
        $order = new Order();
        $order->no_signature_creator = 1;
        $order->type_contracts_id = 6;
        $order->total = 0;
        $order->subtotal = 0;
        $order->contracts_id = 777777777;
        $order->user_id = $userdata->id;
        $order->signature_user_id = $userdata->signature_image_id;
        $order->status = 'pendiente';
        $order->stamp = Str::random(20);
        $order->certificate = Str::random(20);
        $order->date_signature_user = date('Y-m-d H:i:s');
        $order->body = null;// $request->contenido;
        $order->company_amha = $request->empresa;
        
        $order->save();

        $users_ids = [];
        foreach ($request->usuarios as $key => $value) {
            $user_aux = null;
            $checkuser = User::where('email',$value['email'])->first();
            if ($checkuser) {
                $user_aux = $checkuser;
                array_push($users_ids,$user_aux->id);
            }
            else{   
                $user = new User();
                $user->email = $value['email'];
                $user->name = $value['nombre'];
                $user->password = bcrypt($value['email']);
                $user->phone = $value['telefono'];
                
                $user->access = 1;
                $user->from_web = 1;
                
                //$user->curp = $value['curp'];
                //$user->clave_ine = $value['rfc'];
                //$user->business_name = $value['razon_social'];
                $user->email_verified_at = date('Y-m-d H:i:s');
                $user->save();


                $userapp = new UserApp();
                $userapp->nombre = $user->name;
                $userapp->email = $request->email;
                $userapp->tipo = 'usuario';
                $userapp->password = md5($value['email']);
                $userapp->save();

                $user->usuarios_id = $userapp->id;
                $user->status = 'completado';
                $user->save();
                $user_aux = $user;
                array_push($users_ids,$user->id);
            }

            $row_contra = new OrderContact();
            $row_contra->user_id = $user_aux->id;
            $row_contra->order_id = $order->id;
            $row_contra->save();
            
        }
        $users_contras = User::whereIn('id',$users_ids)->get();
        
        if (Images::getUrl($userdata->image_id_logo)) {
            $userdata->imageUrl = Images::getUrl($userdata->image_id_logo);
        }
        else{
            $userdata->imageUrl = 'https://notarionet.com/public/images/logo.png';
        }

        $contract = [
            'title'=>$request->titulo,
            'title_url'=>str_replace(' ','-',$request->titulo),
            'contracts_id'=>$request->contracts_id,
            'body'=>$order->body,
            'stamp'=>$order->stamp,
            'certificate'=>$order->certificate,
            'date_signature_user'=>$order->date_signature_user,
            //'date_signature_user_contra'=>$order->date_signature_user_contra,
            'created'=>$order->created_at->format('d-m-Y H:i:s'),
            'clauses'=>[],
            'header_format'=>$order->header_format,
            'medio_format'=>$order->medio_format,
            'inferior_format'=>$order->inferior_format,
        ];
        $url_code = "https://notarionet.com/#/contrato/".$order->id;

        $inputs = [
            'order_id'=>$order->id,
            'user'=>$userdata,
            'users_contras'=>$users_contras,
            'contract'=>$contract,
            'images'=>[],
            'qr' => QrCode::format('png')->size(150)->margin(0)->generate($url_code),
            'cancel'=> 0,
            'base64'=>$request->base64
        ];
        
        $pdf = $this->createPDF($inputs);

    
        
        if($pdf != 'error'){
            
            try{
                //Mail::to($inputs['user']['email'])->send(new ContractMail( $inputs, $pdf));
                //Mail::to('richardsustam23@gmail.com')->send(new ContractMail( $inputs, $pdf));
                foreach ($users_contras as $key => $value) {
                    $inputs['user_contra'] = $value;
                    Mail::to($value->email)->send(new ContractContraMail( $inputs,$pdf));
                    //Mail::to('richardsustam23@gmail.com')->send(new ContractContraMail( $inputs,$pdf));
                }
            }catch (\Exception $e) {
                    //report($e);
                    \Log::error( 'Error en enviar correo AMHA: '.json_encode($e) );
            }
            
            $data = Order::find($order->id);
            $data->documentUrl = Documents::getUrl($data->documents_id);
            $data->created = $data->created_at->format('d-m-Y H:i:s');
            
            $response_data = [
                'status'=>'Documento creado correctamente',
                'id'=>$data->id,

            ];
            return response()->json($response_data);
        }
        else{
            return response()->json(['msg'=>'Ocurrio un error al guardar'],500);
        }

    }

    public function createPDF($inputs)
    {
        ini_set('memory_limit', '-1');

        //$pdf = PDF::loadView('plantillas.contrato',['inputs' => $inputs]);
        //$content = $pdf->download()->getOriginalContent();
        
        // Crear el archivo y almacenarlo en el storage
        $content = base64_decode($inputs['base64']);
        Storage::disk('public')->put('docs/contrato-'.$inputs['order_id'].'.pdf',$content);

        //Crear el registro del documento y guardar el id en el contrato
        $doc = new Document(array(
            "path"=>'docs/contrato-'.$inputs['order_id'].'.pdf',
            "disk"=>'public',
            "key"=>uniqid()
        ));

        $doc->save();

        //guardamos y obtenemos el ID de multilateral
        $multilateral = $this->saveSeguridata($inputs['order_id'],sizeof($inputs['users_contras']));
        if ($multilateral != 'error') {
            $updateorder = Order::find($inputs['order_id']);
            $updateorder->documents_id = $doc->id;

            $updateorder->multilateralId = $multilateral['multilateralId'];
            $updateorder->save();

            return $content; 
        }
        else{
            return 'error';
        }
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
            \Log::error( 'Guarda en seguridata AMHA: '.json_encode($response) );
            if (isset($response['multilateralId'])) {
                return($response);
            }
            else{
                $conts_del = OrderContact::where('order_id',$order_id)->delete();
                $orderdel = Order::find($order_id);
                $orderdel->delete();
                \Log::error( 'Error al guarda en seguridata AMHA: ID:'.$order_id );
                return 'error';
            }
           
            
        }
    }


    public function show($id){
        $order = Order::find($id);
        if ($order) {
            $checksignatures = OrderContact::where('order_id',$id)->where('date_signature_user',null)->count();
            
            
            if ($checksignatures == 0) {
                $url = Documents::getUrl($order->documents_id);
                $reponse = [
                    'msg'=>'Documento finalizado',
                    'pdf'=>$url,
                    'nom'=>'https://notarionet.com/storage/app/public/docs/contrato-nom'.$id.'.nom',
                    'nom_pdf'=>'https://notarionet.com/storage/app/public/docs/contrato-nom-data'.$id.'.pdf',
                ];
                
                return response()->json($reponse);
            }   
            else{
                return response()->json(['msg'=>'El documento aun no ha sido firmado por completo'],500);
            }
        }
        else{
            return response()->json(['msg'=>'No se encontro documento con ID proporcionado'],500);
        }
    }

}
