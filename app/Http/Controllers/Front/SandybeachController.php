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

class SandybeachController extends Controller
{
    public function store(Request $request) {
        
    
        $userdata = User::find(232);
        $contractdata = Contract::find(25);
        $body_format =  $contractdata->body_format;
        $body_format = str_replace("[SB_FECHA_CONTRATO]", $request->fecha, $body_format);
        $body_format = str_replace("[SB_NOMBRE_COMPRADOR]", $request->usuario['nombre'], $body_format);
        $body_format = str_replace("[SB_DOCIMILIO_CORREO]", $request->usuario['domicilio'].', '.$request->usuario['email'], $body_format);
        $body_format = str_replace("[SB_PROPIEDAD]", $request->propiedad, $body_format);
        $body_format = str_replace("[SB_GARANTIA]", $request->garantia, $body_format);
        $body_format = str_replace("[SB_PRECIO_TOTAL]", $request->precio_total, $body_format);
        $body_format = str_replace("[SB_CUENTA_ESCROW]", $request->cuenta_escrow, $body_format);
        
        $order = new Order();
        $order->no_signature_creator = 0;
        $order->type_contracts_id = 7;
        $order->total = 0;
        $order->subtotal = 0;
        $order->contracts_id = 25;
        $order->user_id = $userdata->id;
        $order->signature_user_id = $userdata->signature_image_id;
        $order->status = 'pendiente';
        $order->stamp = Str::random(20);
        $order->certificate = Str::random(20);
        $order->date_signature_user = date('Y-m-d H:i:s');
        $order->body = $body_format;// $request->contenido;
        $order->stripe_link = $request->stripe_link;

        
        $order->save();

        $users_ids = [];
        $user_aux = null;
        $checkuser = User::where('email',$request->usuario['email'])->first();
        if ($checkuser) {
            $user_aux = $checkuser;
            array_push($users_ids,$user_aux->id);
        }
        else{   
            $user = new User();
            $user->email = $request->usuario['email'];
            $user->name = $request->usuario['nombre'];
            $user->password = bcrypt($request->usuario['email']);
            //$user->phone = $request->usuario['telefono'];
                
            $user->access = 1;
            $user->from_web = 1;
            $user->email_verified_at = date('Y-m-d H:i:s');
            $user->passport_number = $request->usuario['numero_pasaporte'];
            $user->credit_card = $request->usuario['tarjeta_credito'];
            $user->save();


            $userapp = new UserApp();
            $userapp->nombre = $user->name;
            $userapp->email = $request->email;
            $userapp->tipo = 'usuario';
            $userapp->password = md5($request->usuario['email']);
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
            
        
        $users_contras = User::whereIn('id',$users_ids)->get();
        
        if (Images::getUrl($userdata->image_id_logo)) {
            $userdata->imageUrl = Images::getUrl($userdata->image_id_logo);
        }
        else{
            $userdata->imageUrl = 'https://notarionet.com/public/images/logo.png';
        }

        $contract = [
            'title'=>'CONTRATO DE PROMEDA DE COMPRAVENTA DE INMUEBLE',
            'title_url'=>str_replace(' ','-','CONTRATO DE PROMEDA DE COMPRAVENTA DE INMUEBLE '),
            'contracts_id'=>25,
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
                Mail::to($inputs['user']['email'])->send(new ContractMail( $inputs, $pdf));
                //Mail::to('richardsustam23@gmail.com')->send(new ContractMail( $inputs, $pdf));
                foreach ($users_contras as $key => $value) {
                    $inputs['user_contra'] = $value;
                    //Mail::to($value->email)->send(new ContractContraMail( $inputs,$pdf));
                    Mail::to('richardsustam23@gmail.com')->send(new ContractContraMail( $inputs,$pdf));
                }
            }catch (\Exception $e) {
                    //report($e);
                    \Log::error( 'Error en enviar correo AMHA: '.json_encode($e) );
            }
            
            $data = Order::find($order->id);
            $data->documentUrl = Documents::getUrl($data->documents_id);
            $data->created = $data->created_at->format('d-m-Y H:i:s');
            
            $response_data = [
                'status'=>'Document created successfully',
                'id'=>$data->id,

            ];
            return response()->json($response_data);
        }
        else{
            return response()->json(['msg'=>'An error occurred while saving'],500);
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
        $multilateral = $this->saveSeguridata($inputs['order_id'],sizeof($inputs['users_contras']) + 1);
        if ($multilateral != 'error') {
            $updateorder = Order::find($inputs['order_id']);
            $updateorder->documents_id = $doc->id;

            $updateorder->multilateralId = $multilateral['multilateralId'];
            $updateorder->save();

             //enviamos la firma y obtenemos el hash desde java

            $hassh = [];

            $hassh = $this->getHash($inputs['order_id'],$multilateral['multilateralId'],'true');
                
                
                    
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
                $orderdel = Order::find($order_id);
                $orderdel->delete();
                \Log::error( 'Error al guarda en seguridata AMHA: ID:'.$order_id );
                return 'error';
            }
           
            
        }
        else{
            \Log::error( 'Error al guarda en seguridata no hay login en seguridata: ID:'.$order_id );
            
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
        else{
            \Log::error( 'Error al guarda en seguridata no hay login en seguridata: ID:'.$order_id );
            
            $orderdel = Order::find($order_id);
            $orderdel->delete();
            return 'error';
            
        }
    }
    public function show($id){
        $order = Order::find($id);
        if ($order) {
            $checksignatures = OrderContact::where('order_id',$id)->where('date_signature_user',null)->count();
            
            
            if ($checksignatures == 0) {
                $url = Documents::getUrl($order->documents_id);
                $reponse = [
                    'id'=>$id,
                    'msg'=>'Signed contract',
                    'pdf'=>$url,
                    'nom'=>'https://notarionet.com/storage/app/public/docs/contrato-nom'.$id.'.nom',
                    'nom_pdf'=>'https://notarionet.com/storage/app/public/docs/contrato-nom-data'.$id.'.pdf',
                ];
                
                return response()->json($reponse);
            }   
            else{
                
                $tok = Str::random(60);
                

                $contra = OrderContact::where('order_id',$id)->first();
                if($contra){
                    $us = User::find($contra->user_id);
                    $us->remember_token = $tok;
                    $us->password = bcrypt($tok);
                    $us->save();
                }
                

                return response()->json(['msg'=>'The document has not yet been fully signed','signature_url'=>'https://notarionet.com/#/login?token='.$tok.'&email='.$us->email.'&id='.$order->id,],500);
            }
        }
        else{
            return response()->json(['msg'=>'No document found with provided ID'],500);
        }
    }

    public function update(Request $request){
        $order = Order::find($request->id);
        if ($order) {
            $order->stripe_link = $request->stripe_link;
            $order->save();
            return response()->json(['msg'=>'Document updated successfully']);
        }
        else{
            return response()->json(['msg'=>'No document found with provided ID'],500);
        }
        
    }
    public function storeMexicanindivualPlayapalmeras(Request $request){
      
        ini_set('memory_limit',-1);
        $userdata = User::find(232);
        $contractdata = Contract::find(30);
        
        $body_format =  $contractdata->body_format;
    
        
        $body_format = str_replace("[day]", $request->day, $body_format);
        $body_format = str_replace("[month]", $request->month, $body_format);
        $body_format = str_replace("[year]", $request->year, $body_format);

        $body_format = str_replace("[name]", $request->customer['name'], $body_format);
        $body_format = str_replace("[birthdate]", $request->customer['birthdate'], $body_format);
        $body_format = str_replace("[phone]", $request->customer['phone'], $body_format);
        $body_format = str_replace("[email]", $request->customer['email'], $body_format);
        $body_format = str_replace("[address]", $request->customer['address'], $body_format);
        $body_format = str_replace("[nacionality]", $request->customer['nacionality'], $body_format);
        $body_format = str_replace("[id_number]", $request->customer['id_number'], $body_format);
        $body_format = str_replace("[marital_status]", $request->customer['marital_status'], $body_format);

        $body_format = str_replace("[property]", $request->property, $body_format);
        $body_format = str_replace("[square_meters]", $request->square_meters, $body_format);
        $body_format = str_replace("[total_price]", $request->total_price, $body_format);
        
        
        
        $order = new Order();
        $order->no_signature_creator = 0;
        $order->type_contracts_id = 7;
        $order->total = 0;
        $order->subtotal = 0;
        $order->contracts_id = 30;
        $order->user_id = $userdata->id;
        $order->signature_user_id = $userdata->signature_image_id;
        $order->status = 'pendiente';
        $order->stamp = Str::random(20);
        $order->certificate = Str::random(20);
        $order->date_signature_user = date('Y-m-d H:i:s');
        $order->body = $body_format;// $request->contenido;
        $order->stripe_link = $request->payment_url;
        $order->type_sandybeach = 'A';
        $order->lots = json_encode($request->lots);
        $order->save();
        
        $users_ids = [];
        $user_aux = null;
        $checkuser = User::where('email',$request->customer['email'])->first();
        if ($checkuser) {
            $user_aux = $checkuser;
            array_push($users_ids,$user_aux->id);
        }
        else{   
            $user = new User();
            $user->email = $request->customer['email'];
            $user->name = $request->customer['name'];
            $user->password = bcrypt($request->customer['email']);
            //$user->phone = $request->usuario['telefono'];
                
            $user->access = 1;
            $user->from_web = 1;
            $user->email_verified_at = date('Y-m-d H:i:s');
            $user->passport_number = $request->customer['id_number'];
           // $user->credit_card = $request->customer['tarjeta_credito'];
            $user->save();


            $userapp = new UserApp();
            $userapp->nombre = $user->name;
            $userapp->email = $user->email;
            $userapp->tipo = 'usuario';
            $userapp->password = md5($user->email);
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
            
        
        $users_contras = User::whereIn('id',$users_ids)->get();
        
        if (Images::getUrl($userdata->image_id_logo)) {
            $userdata->imageUrl = Images::getUrl($userdata->image_id_logo);
        }
        else{
            $userdata->imageUrl = 'https://notarionet.com/public/images/logo.png';
        }

        $contract = [
            'title'=>'PROMISSORY AGREEMENT',
            'title_url'=>str_replace(' ','-','PROMISSORY AGREEMENT '),
            'contracts_id'=>30,
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
            /*
            //try{
                //Mail::to($inputs['user']['email'])->send(new ContractMail( $inputs, $pdf));
                Mail::to('richardsustam23@gmail.com')->send(new ContractMail( $inputs, $pdf));
                foreach ($users_contras as $key => $value) {
                    $inputs['user_contra'] = $value;
                    //Mail::to($value->email)->send(new ContractContraMail( $inputs,$pdf));
                    Mail::to('richardsustam23@gmail.com')->send(new ContractContraMail( $inputs,$pdf));
                }
            //}catch (\Exception $e) {
                    //report($e);
                   // \Log::error( 'Error en enviar correo AMHA: '.json_encode($e) );
            //}
            */
            $data = Order::find($order->id);
            $data->documentUrl = Documents::getUrl($data->documents_id);
            $data->created = $data->created_at->format('d-m-Y H:i:s');
            
            $tok = Str::random(60);
            $contra = OrderContact::where('order_id',$order->id)->first();
            if($contra){
                $us = User::find($contra->user_id);
                $us->remember_token = $tok;
                $us->password = bcrypt($tok);
                $us->save();

                $response_data = [
                    'status'=>'Document created successfully',
                    'id'=>$data->id,
                    'signature_url'=>'https://notarionet.com/#/login?token='.$tok.'&email='.$us->email.'&id='.$order->id,
                    'payment_url'=>$request->payment_url,
    
                ];
                return response()->json($response_data);
            }
                
           
            
        }
        else{
            return response()->json(['msg'=>'An error occurred while saving'],500);
        }

    }
    public function storeMexicanindivualSterligndev(Request $request){
        ini_set('memory_limit',-1);
        $userdata = User::find(232);
        $contractdata = Contract::find(31);
        
        $body_format =  $contractdata->body_format;
    
        
        $body_format = str_replace("[day]", $request->day, $body_format);
        $body_format = str_replace("[month]", $request->month, $body_format);
        $body_format = str_replace("[year]", $request->year, $body_format);

        $body_format = str_replace("[name]", $request->customer['name'], $body_format);
        $body_format = str_replace("[birthdate]", $request->customer['birthdate'], $body_format);
        $body_format = str_replace("[phone]", $request->customer['phone'], $body_format);
        $body_format = str_replace("[email]", $request->customer['email'], $body_format);
        $body_format = str_replace("[address]", $request->customer['address'], $body_format);
        $body_format = str_replace("[nacionality]", $request->customer['nacionality'], $body_format);
        $body_format = str_replace("[id_number]", $request->customer['id_number'], $body_format);
        $body_format = str_replace("[marital_status]", $request->customer['marital_status'], $body_format);

        $body_format = str_replace("[property]", $request->property, $body_format);
        $body_format = str_replace("[square_meters]", $request->square_meters, $body_format);
        $body_format = str_replace("[total_price]", $request->total_price, $body_format);
        
        
        
        $order = new Order();
        $order->no_signature_creator = 0;
        $order->type_contracts_id = 7;
        $order->total = 0;
        $order->subtotal = 0;
        $order->contracts_id = 31;
        $order->user_id = $userdata->id;
        $order->signature_user_id = $userdata->signature_image_id;
        $order->status = 'pendiente';
        $order->stamp = Str::random(20);
        $order->certificate = Str::random(20);
        $order->date_signature_user = date('Y-m-d H:i:s');
        $order->body = $body_format;// $request->contenido;
        $order->stripe_link = $request->payment_url;
        $order->type_sandybeach = 'B';
        $order->lots = json_encode($request->lots);
        $order->save();

        $users_ids = [];
        $user_aux = null;
        $checkuser = User::where('email',$request->customer['email'])->first();
        if ($checkuser) {
            $user_aux = $checkuser;
            array_push($users_ids,$user_aux->id);
        }
        else{   
            $user = new User();
            $user->email = $request->customer['email'];
            $user->name = $request->customer['name'];
            $user->password = bcrypt($request->customer['email']);
            //$user->phone = $request->usuario['telefono'];
                
            $user->access = 1;
            $user->from_web = 1;
            $user->email_verified_at = date('Y-m-d H:i:s');
            $user->passport_number = $request->customer['id_number'];
           // $user->credit_card = $request->customer['tarjeta_credito'];
            $user->save();


            $userapp = new UserApp();
            $userapp->nombre = $user->name;
            $userapp->email = $user->email;
            $userapp->tipo = 'usuario';
            $userapp->password = md5($user->email);
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
            
        
        $users_contras = User::whereIn('id',$users_ids)->get();
        
        if (Images::getUrl($userdata->image_id_logo)) {
            $userdata->imageUrl = Images::getUrl($userdata->image_id_logo);
        }
        else{
            $userdata->imageUrl = 'https://notarionet.com/public/images/logo.png';
        }

        $contract = [
            'title'=>'PROMISSORY AGREEMENT',
            'title_url'=>str_replace(' ','-','PROMISSORY AGREEMENT '),
            'contracts_id'=>31,
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
            /*
            //try{
                //Mail::to($inputs['user']['email'])->send(new ContractMail( $inputs, $pdf));
                Mail::to('richardsustam23@gmail.com')->send(new ContractMail( $inputs, $pdf));
                foreach ($users_contras as $key => $value) {
                    $inputs['user_contra'] = $value;
                    //Mail::to($value->email)->send(new ContractContraMail( $inputs,$pdf));
                    Mail::to('richardsustam23@gmail.com')->send(new ContractContraMail( $inputs,$pdf));
                }
            //}catch (\Exception $e) {
                    //report($e);
                   // \Log::error( 'Error en enviar correo AMHA: '.json_encode($e) );
            //}
            */
            $data = Order::find($order->id);
            $data->documentUrl = Documents::getUrl($data->documents_id);
            $data->created = $data->created_at->format('d-m-Y H:i:s');
            
            $tok = Str::random(60);
            $contra = OrderContact::where('order_id',$order->id)->first();
            if($contra){
                $us = User::find($contra->user_id);
                $us->remember_token = $tok;
                $us->password = bcrypt($tok);
                $us->save();

                $response_data = [
                    'status'=>'Document created successfully',
                    'id'=>$data->id,
                    'signature_url'=>'https://notarionet.com/#/login?token='.$tok.'&email='.$us->email.'&id='.$order->id,
                    'payment_url'=>$request->payment_url,
    
                ];
                return response()->json($response_data);
            }
                
           
            
        }
        else{
            return response()->json(['msg'=>'An error occurred while saving'],500);
        }
    }
    public function storeMexicanindivualResell(Request $request){
        ini_set('memory_limit',-1);
        $userdata = User::find(232);
        $contractdata = Contract::find(32);
        
        $body_format =  $contractdata->body_format;
    
        
        $body_format = str_replace("[day]", $request->day, $body_format);
        $body_format = str_replace("[month]", $request->month, $body_format);
        $body_format = str_replace("[year]", $request->year, $body_format);

        $body_format = str_replace("[name]", $request->customer['name'], $body_format);
        $body_format = str_replace("[birthdate]", $request->customer['birthdate'], $body_format);
        $body_format = str_replace("[phone]", $request->customer['phone'], $body_format);
        $body_format = str_replace("[email]", $request->customer['email'], $body_format);
        $body_format = str_replace("[address]", $request->customer['address'], $body_format);
        $body_format = str_replace("[nacionality]", $request->customer['nacionality'], $body_format);
        $body_format = str_replace("[id_number]", $request->customer['id_number'], $body_format);
        $body_format = str_replace("[marital_status]", $request->customer['marital_status'], $body_format);

        $body_format = str_replace("[property]", $request->property, $body_format);
        $body_format = str_replace("[square_meters]", $request->square_meters, $body_format);
        $body_format = str_replace("[total_price]", $request->total_price, $body_format);
        
        
        
        $order = new Order();
        $order->no_signature_creator = 0;
        $order->type_contracts_id = 7;
        $order->total = 0;
        $order->subtotal = 0;
        $order->contracts_id = 32;
        $order->user_id = $userdata->id;
        $order->signature_user_id = $userdata->signature_image_id;
        $order->status = 'pendiente';
        $order->stamp = Str::random(20);
        $order->certificate = Str::random(20);
        $order->date_signature_user = date('Y-m-d H:i:s');
        $order->body = $body_format;// $request->contenido;
        $order->stripe_link = $request->payment_url;
        $order->type_sandybeach = 'C';
        $order->lots = json_encode($request->lots);
        $order->save();

        $users_ids = [];
        $user_aux = null;
        $checkuser = User::where('email',$request->customer['email'])->first();
        if ($checkuser) {
            $user_aux = $checkuser;
            array_push($users_ids,$user_aux->id);
        }
        else{   
            $user = new User();
            $user->email = $request->customer['email'];
            $user->name = $request->customer['name'];
            $user->password = bcrypt($request->customer['email']);
            //$user->phone = $request->usuario['telefono'];
                
            $user->access = 1;
            $user->from_web = 1;
            $user->email_verified_at = date('Y-m-d H:i:s');
            $user->passport_number = $request->customer['id_number'];
           // $user->credit_card = $request->customer['tarjeta_credito'];
            $user->save();


            $userapp = new UserApp();
            $userapp->nombre = $user->name;
            $userapp->email = $user->email;
            $userapp->tipo = 'usuario';
            $userapp->password = md5($user->email);
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
            
        
        $users_contras = User::whereIn('id',$users_ids)->get();
        
        if (Images::getUrl($userdata->image_id_logo)) {
            $userdata->imageUrl = Images::getUrl($userdata->image_id_logo);
        }
        else{
            $userdata->imageUrl = 'https://notarionet.com/public/images/logo.png';
        }

        $contract = [
            'title'=>'PROMISSORY AGREEMENT',
            'title_url'=>str_replace(' ','-','PROMISSORY AGREEMENT '),
            'contracts_id'=>32,
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
            /*
            //try{
                //Mail::to($inputs['user']['email'])->send(new ContractMail( $inputs, $pdf));
                Mail::to('richardsustam23@gmail.com')->send(new ContractMail( $inputs, $pdf));
                foreach ($users_contras as $key => $value) {
                    $inputs['user_contra'] = $value;
                    //Mail::to($value->email)->send(new ContractContraMail( $inputs,$pdf));
                    Mail::to('richardsustam23@gmail.com')->send(new ContractContraMail( $inputs,$pdf));
                }
            //}catch (\Exception $e) {
                    //report($e);
                   // \Log::error( 'Error en enviar correo AMHA: '.json_encode($e) );
            //}
            */
            $data = Order::find($order->id);
            $data->documentUrl = Documents::getUrl($data->documents_id);
            $data->created = $data->created_at->format('d-m-Y H:i:s');
            
            $tok = Str::random(60);
            $contra = OrderContact::where('order_id',$order->id)->first();
            if($contra){
                $us = User::find($contra->user_id);
                $us->remember_token = $tok;
                $us->password = bcrypt($tok);
                $us->save();

                $response_data = [
                    'status'=>'Document created successfully',
                    'id'=>$data->id,
                    'signature_url'=>'https://notarionet.com/#/login?token='.$tok.'&email='.$us->email.'&id='.$order->id,
                    'payment_url'=>$request->payment_url,
    
                ];
                return response()->json($response_data);
            }
                
           
            
        }
        else{
            return response()->json(['msg'=>'An error occurred while saving'],500);
        }
    }
    public function storeCorporationPlayapalmeras(Request $request){
        

        ini_set('memory_limit',-1);
        $userdata = User::find(232);
        $contractdata = Contract::find(33);
        
        $body_format =  $contractdata->body_format;
    
        
        $body_format = str_replace("[day]", $request->day, $body_format);
        $body_format = str_replace("[month]", $request->month, $body_format);
        $body_format = str_replace("[year]", $request->year, $body_format);

        $body_format = str_replace("[coporate_name]", $request->customer['coporate_name'], $body_format);
        $body_format = str_replace("[deed_incorporation_number]", $request->customer['deed_incorporation_number'], $body_format);
        $body_format = str_replace("[date_incorporation]", $request->customer['date_incorporation'], $body_format);
        $body_format = str_replace("[name_notary_public]", $request->customer['name_notary_public'], $body_format);
        $body_format = str_replace("[number_notary_public]", $request->customer['number_notary_public'], $body_format);
        $body_format = str_replace("[state_notary_public]", $request->customer['state_notary_public'], $body_format);
        $body_format = str_replace("[mercantil_folio]", $request->customer['mercantil_folio'], $body_format);
        $body_format = str_replace("[name_legal_representative]", $request->customer['name_legal_representative'], $body_format);
        $body_format = str_replace("[address]", $request->customer['address'], $body_format);
        $body_format = str_replace("[rfc]", $request->customer['rfc'], $body_format);
        $body_format = str_replace("[email]", $request->customer['email'], $body_format);

        $body_format = str_replace("[property]", $request->property, $body_format);
        $body_format = str_replace("[square_meters]", $request->square_meters, $body_format);
        $body_format = str_replace("[total_price]", $request->total_price, $body_format);
        
        
        
        $order = new Order();
        $order->no_signature_creator = 0;
        $order->type_contracts_id = 7;
        $order->total = 0;
        $order->subtotal = 0;
        $order->contracts_id = 33;
        $order->user_id = $userdata->id;
        $order->signature_user_id = $userdata->signature_image_id;
        $order->status = 'pendiente';
        $order->stamp = Str::random(20);
        $order->certificate = Str::random(20);
        $order->date_signature_user = date('Y-m-d H:i:s');
        $order->body = $body_format;// $request->contenido;
        $order->stripe_link = $request->payment_url;
        $order->type_sandybeach = 'D';
        $order->lots = json_encode($request->lots);
        $order->save();

        $users_ids = [];
        $user_aux = null;
        $checkuser = User::where('email',$request->customer['email'])->first();
        if ($checkuser) {
            $user_aux = $checkuser;
            array_push($users_ids,$user_aux->id);
        }
        else{   
            $user = new User();
            $user->email = $request->customer['email'];
            $user->name = $request->customer['coporate_name'];
            $user->password = bcrypt($request->customer['email']);
            //$user->phone = $request->usuario['telefono'];
                
            $user->access = 1;
            $user->from_web = 1;
            $user->email_verified_at = date('Y-m-d H:i:s');
            $user->business_name = $request->customer['coporate_name'];
            $user->rfc_company = $request->customer['rfc'];
           // $user->credit_card = $request->customer['tarjeta_credito'];
            $user->save();


            $userapp = new UserApp();
            $userapp->nombre = $user->name;
            $userapp->email = $user->email;
            $userapp->tipo = 'usuario';
            $userapp->password = md5($user->email);
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
            
        
        $users_contras = User::whereIn('id',$users_ids)->get();
        
        if (Images::getUrl($userdata->image_id_logo)) {
            $userdata->imageUrl = Images::getUrl($userdata->image_id_logo);
        }
        else{
            $userdata->imageUrl = 'https://notarionet.com/public/images/logo.png';
        }

        $contract = [
            'title'=>'PROMISSORY AGREEMENT',
            'title_url'=>str_replace(' ','-','PROMISSORY AGREEMENT '),
            'contracts_id'=>33,
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
            /*
            //try{
                //Mail::to($inputs['user']['email'])->send(new ContractMail( $inputs, $pdf));
                Mail::to('richardsustam23@gmail.com')->send(new ContractMail( $inputs, $pdf));
                foreach ($users_contras as $key => $value) {
                    $inputs['user_contra'] = $value;
                    //Mail::to($value->email)->send(new ContractContraMail( $inputs,$pdf));
                    Mail::to('richardsustam23@gmail.com')->send(new ContractContraMail( $inputs,$pdf));
                }
            //}catch (\Exception $e) {
                    //report($e);
                   // \Log::error( 'Error en enviar correo AMHA: '.json_encode($e) );
            //}
            */
            $data = Order::find($order->id);
            $data->documentUrl = Documents::getUrl($data->documents_id);
            $data->created = $data->created_at->format('d-m-Y H:i:s');
            
            $tok = Str::random(60);
            $contra = OrderContact::where('order_id',$order->id)->first();
            if($contra){
                $us = User::find($contra->user_id);
                $us->remember_token = $tok;
                $us->password = bcrypt($tok);
                $us->save();

                $response_data = [
                    'status'=>'Document created successfully',
                    'id'=>$data->id,
                    'signature_url'=>'https://notarionet.com/#/login?token='.$tok.'&email='.$us->email.'&id='.$order->id,
                    'payment_url'=>$request->payment_url,
    
                ];
                return response()->json($response_data);
            }
                
           
            
        }
        else{
            return response()->json(['msg'=>'An error occurred while saving'],500);
        }

    }
    public function storeCorporationSterligndev(Request $request){
        ini_set('memory_limit',-1);
        $userdata = User::find(232);
        $contractdata = Contract::find(34);
        
        $body_format =  $contractdata->body_format;
    
        
        $body_format = str_replace("[day]", $request->day, $body_format);
        $body_format = str_replace("[month]", $request->month, $body_format);
        $body_format = str_replace("[year]", $request->year, $body_format);

        $body_format = str_replace("[coporate_name]", $request->customer['coporate_name'], $body_format);
        $body_format = str_replace("[deed_incorporation_number]", $request->customer['deed_incorporation_number'], $body_format);
        $body_format = str_replace("[date_incorporation]", $request->customer['date_incorporation'], $body_format);
        $body_format = str_replace("[name_notary_public]", $request->customer['name_notary_public'], $body_format);
        $body_format = str_replace("[number_notary_public]", $request->customer['number_notary_public'], $body_format);
        $body_format = str_replace("[state_notary_public]", $request->customer['state_notary_public'], $body_format);
        $body_format = str_replace("[mercantil_folio]", $request->customer['mercantil_folio'], $body_format);
        $body_format = str_replace("[name_legal_representative]", $request->customer['name_legal_representative'], $body_format);
        $body_format = str_replace("[address]", $request->customer['address'], $body_format);
        $body_format = str_replace("[rfc]", $request->customer['rfc'], $body_format);
        $body_format = str_replace("[email]", $request->customer['email'], $body_format);

        $body_format = str_replace("[property]", $request->property, $body_format);
        $body_format = str_replace("[square_meters]", $request->square_meters, $body_format);
        $body_format = str_replace("[total_price]", $request->total_price, $body_format);
        
        
        
        $order = new Order();
        $order->no_signature_creator = 0;
        $order->type_contracts_id = 7;
        $order->total = 0;
        $order->subtotal = 0;
        $order->contracts_id = 34;
        $order->user_id = $userdata->id;
        $order->signature_user_id = $userdata->signature_image_id;
        $order->status = 'pendiente';
        $order->stamp = Str::random(20);
        $order->certificate = Str::random(20);
        $order->date_signature_user = date('Y-m-d H:i:s');
        $order->body = $body_format;// $request->contenido;
        $order->stripe_link = $request->payment_url;
        $order->type_sandybeach = 'E';
        $order->lots = json_encode($request->lots);
        $order->save();

        $users_ids = [];
        $user_aux = null;
        $checkuser = User::where('email',$request->customer['email'])->first();
        if ($checkuser) {
            $user_aux = $checkuser;
            array_push($users_ids,$user_aux->id);
        }
        else{   
            $user = new User();
            $user->email = $request->customer['email'];
            $user->name = $request->customer['coporate_name'];
            $user->password = bcrypt($request->customer['email']);
            //$user->phone = $request->usuario['telefono'];
                
            $user->access = 1;
            $user->from_web = 1;
            $user->email_verified_at = date('Y-m-d H:i:s');
            $user->business_name = $request->customer['coporate_name'];
            $user->rfc_company = $request->customer['rfc'];
           // $user->credit_card = $request->customer['tarjeta_credito'];
            $user->save();


            $userapp = new UserApp();
            $userapp->nombre = $user->name;
            $userapp->email = $user->email;
            $userapp->tipo = 'usuario';
            $userapp->password = md5($user->email);
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
            
        
        $users_contras = User::whereIn('id',$users_ids)->get();
        
        if (Images::getUrl($userdata->image_id_logo)) {
            $userdata->imageUrl = Images::getUrl($userdata->image_id_logo);
        }
        else{
            $userdata->imageUrl = 'https://notarionet.com/public/images/logo.png';
        }

        $contract = [
            'title'=>'PROMISSORY AGREEMENT',
            'title_url'=>str_replace(' ','-','PROMISSORY AGREEMENT '),
            'contracts_id'=>34,
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
            /*
            //try{
                //Mail::to($inputs['user']['email'])->send(new ContractMail( $inputs, $pdf));
                Mail::to('richardsustam23@gmail.com')->send(new ContractMail( $inputs, $pdf));
                foreach ($users_contras as $key => $value) {
                    $inputs['user_contra'] = $value;
                    //Mail::to($value->email)->send(new ContractContraMail( $inputs,$pdf));
                    Mail::to('richardsustam23@gmail.com')->send(new ContractContraMail( $inputs,$pdf));
                }
            //}catch (\Exception $e) {
                    //report($e);
                   // \Log::error( 'Error en enviar correo AMHA: '.json_encode($e) );
            //}
            */
            $data = Order::find($order->id);
            $data->documentUrl = Documents::getUrl($data->documents_id);
            $data->created = $data->created_at->format('d-m-Y H:i:s');
            
            $tok = Str::random(60);
            $contra = OrderContact::where('order_id',$order->id)->first();
            if($contra){
                $us = User::find($contra->user_id);
                $us->remember_token = $tok;
                $us->password = bcrypt($tok);
                $us->save();

                $response_data = [
                    'status'=>'Document created successfully',
                    'id'=>$data->id,
                    'signature_url'=>'https://notarionet.com/#/login?token='.$tok.'&email='.$us->email.'&id='.$order->id,
                    'payment_url'=>$request->payment_url,
    
                ];
                return response()->json($response_data);
            }
                
           
            
        }
        else{
            return response()->json(['msg'=>'An error occurred while saving'],500);
        }
    }
    public function storeCorporationResell(Request $request){
        ini_set('memory_limit',-1);
        $userdata = User::find(232);
        $contractdata = Contract::find(35);
        
        $body_format =  $contractdata->body_format;
    
        
        $body_format = str_replace("[day]", $request->day, $body_format);
        $body_format = str_replace("[month]", $request->month, $body_format);
        $body_format = str_replace("[year]", $request->year, $body_format);

        $body_format = str_replace("[coporate_name]", $request->customer['coporate_name'], $body_format);
        $body_format = str_replace("[deed_incorporation_number]", $request->customer['deed_incorporation_number'], $body_format);
        $body_format = str_replace("[date_incorporation]", $request->customer['date_incorporation'], $body_format);
        $body_format = str_replace("[name_notary_public]", $request->customer['name_notary_public'], $body_format);
        $body_format = str_replace("[number_notary_public]", $request->customer['number_notary_public'], $body_format);
        $body_format = str_replace("[state_notary_public]", $request->customer['state_notary_public'], $body_format);
        $body_format = str_replace("[mercantil_folio]", $request->customer['mercantil_folio'], $body_format);
        $body_format = str_replace("[name_legal_representative]", $request->customer['name_legal_representative'], $body_format);
        $body_format = str_replace("[address]", $request->customer['address'], $body_format);
        $body_format = str_replace("[rfc]", $request->customer['rfc'], $body_format);
        $body_format = str_replace("[email]", $request->customer['email'], $body_format);

        $body_format = str_replace("[property]", $request->property, $body_format);
        $body_format = str_replace("[square_meters]", $request->square_meters, $body_format);
        $body_format = str_replace("[total_price]", $request->total_price, $body_format);
        
        
        
        $order = new Order();
        $order->no_signature_creator = 0;
        $order->type_contracts_id = 7;
        $order->total = 0;
        $order->subtotal = 0;
        $order->contracts_id = 35;
        $order->user_id = $userdata->id;
        $order->signature_user_id = $userdata->signature_image_id;
        $order->status = 'pendiente';
        $order->stamp = Str::random(20);
        $order->certificate = Str::random(20);
        $order->date_signature_user = date('Y-m-d H:i:s');
        $order->body = $body_format;// $request->contenido;
        $order->stripe_link = $request->payment_url;
        $order->type_sandybeach = 'F';
        $order->lots = json_encode($request->lots);
        $order->save();

        $users_ids = [];
        $user_aux = null;
        $checkuser = User::where('email',$request->customer['email'])->first();
        if ($checkuser) {
            $user_aux = $checkuser;
            array_push($users_ids,$user_aux->id);
        }
        else{   
            $user = new User();
            $user->email = $request->customer['email'];
            $user->name = $request->customer['coporate_name'];
            $user->password = bcrypt($request->customer['email']);
            //$user->phone = $request->usuario['telefono'];
                
            $user->access = 1;
            $user->from_web = 1;
            $user->email_verified_at = date('Y-m-d H:i:s');
            $user->business_name = $request->customer['coporate_name'];
            $user->rfc_company = $request->customer['rfc'];
           // $user->credit_card = $request->customer['tarjeta_credito'];
            $user->save();


            $userapp = new UserApp();
            $userapp->nombre = $user->name;
            $userapp->email = $user->email;
            $userapp->tipo = 'usuario';
            $userapp->password = md5($user->email);
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
            
        
        $users_contras = User::whereIn('id',$users_ids)->get();
        
        if (Images::getUrl($userdata->image_id_logo)) {
            $userdata->imageUrl = Images::getUrl($userdata->image_id_logo);
        }
        else{
            $userdata->imageUrl = 'https://notarionet.com/public/images/logo.png';
        }

        $contract = [
            'title'=>'PROMISSORY AGREEMENT',
            'title_url'=>str_replace(' ','-','PROMISSORY AGREEMENT '),
            'contracts_id'=>35,
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
            /*
            //try{
                //Mail::to($inputs['user']['email'])->send(new ContractMail( $inputs, $pdf));
                Mail::to('richardsustam23@gmail.com')->send(new ContractMail( $inputs, $pdf));
                foreach ($users_contras as $key => $value) {
                    $inputs['user_contra'] = $value;
                    //Mail::to($value->email)->send(new ContractContraMail( $inputs,$pdf));
                    Mail::to('richardsustam23@gmail.com')->send(new ContractContraMail( $inputs,$pdf));
                }
            //}catch (\Exception $e) {
                    //report($e);
                   // \Log::error( 'Error en enviar correo AMHA: '.json_encode($e) );
            //}
            */
            $data = Order::find($order->id);
            $data->documentUrl = Documents::getUrl($data->documents_id);
            $data->created = $data->created_at->format('d-m-Y H:i:s');
            
            $tok = Str::random(60);
            $contra = OrderContact::where('order_id',$order->id)->first();
            if($contra){
                $us = User::find($contra->user_id);
                $us->remember_token = $tok;
                $us->password = bcrypt($tok);
                $us->save();

                $response_data = [
                    'status'=>'Document created successfully',
                    'id'=>$data->id,
                    'signature_url'=>'https://notarionet.com/#/login?token='.$tok.'&email='.$us->email.'&id='.$order->id,
                    'payment_url'=>$request->payment_url,
    
                ];
                return response()->json($response_data);
            }
                
           
            
        }
        else{
            return response()->json(['msg'=>'An error occurred while saving'],500);
        }
    }
    public function storeForeignerPlayapalmeras(Request $request){
        ini_set('memory_limit',-1);
        $userdata = User::find(232);
        $contractdata = Contract::find(30);
        
        $body_format =  $contractdata->body_format;
    
        
        $body_format = str_replace("[day]", $request->day, $body_format);
        $body_format = str_replace("[month]", $request->month, $body_format);
        $body_format = str_replace("[year]", $request->year, $body_format);

        $body_format = str_replace("[name]", $request->customer['name'], $body_format);
        $body_format = str_replace("[birthdate]", $request->customer['birthdate'], $body_format);
        $body_format = str_replace("[phone]", $request->customer['phone'], $body_format);
        $body_format = str_replace("[email]", $request->customer['email'], $body_format);
        $body_format = str_replace("[address]", $request->customer['address'], $body_format);
        $body_format = str_replace("[nacionality]", $request->customer['nacionality'], $body_format);
        $body_format = str_replace("[id_number]", $request->customer['id_number'], $body_format);
        $body_format = str_replace("[marital_status]", $request->customer['marital_status'], $body_format);

        $body_format = str_replace("[property]", $request->property, $body_format);
        $body_format = str_replace("[square_meters]", $request->square_meters, $body_format);
        $body_format = str_replace("[total_price]", $request->total_price, $body_format);
        
        
        
        $order = new Order();
        $order->no_signature_creator = 0;
        $order->type_contracts_id = 7;
        $order->total = 0;
        $order->subtotal = 0;
        $order->contracts_id = 30;
        $order->user_id = $userdata->id;
        $order->signature_user_id = $userdata->signature_image_id;
        $order->status = 'pendiente';
        $order->stamp = Str::random(20);
        $order->certificate = Str::random(20);
        $order->date_signature_user = date('Y-m-d H:i:s');
        $order->body = $body_format;// $request->contenido;
        $order->stripe_link = $request->payment_url;
        $order->type_sandybeach = 'G';
        $order->lots = json_encode($request->lots);
        $order->save();

        $users_ids = [];
        $user_aux = null;
        $checkuser = User::where('email',$request->customer['email'])->first();
        if ($checkuser) {
            $user_aux = $checkuser;
            array_push($users_ids,$user_aux->id);
        }
        else{   
            $user = new User();
            $user->email = $request->customer['email'];
            $user->name = $request->customer['name'];
            $user->password = bcrypt($request->customer['email']);
            //$user->phone = $request->usuario['telefono'];
                
            $user->access = 1;
            $user->from_web = 1;
            $user->email_verified_at = date('Y-m-d H:i:s');
            $user->passport_number = $request->customer['id_number'];
           // $user->credit_card = $request->customer['tarjeta_credito'];
            $user->save();


            $userapp = new UserApp();
            $userapp->nombre = $user->name;
            $userapp->email = $user->email;
            $userapp->tipo = 'usuario';
            $userapp->password = md5($user->email);
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
            
        
        $users_contras = User::whereIn('id',$users_ids)->get();
        
        if (Images::getUrl($userdata->image_id_logo)) {
            $userdata->imageUrl = Images::getUrl($userdata->image_id_logo);
        }
        else{
            $userdata->imageUrl = 'https://notarionet.com/public/images/logo.png';
        }

        $contract = [
            'title'=>'PROMISSORY AGREEMENT',
            'title_url'=>str_replace(' ','-','PROMISSORY AGREEMENT '),
            'contracts_id'=>30,
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
            /*
            //try{
                //Mail::to($inputs['user']['email'])->send(new ContractMail( $inputs, $pdf));
                Mail::to('richardsustam23@gmail.com')->send(new ContractMail( $inputs, $pdf));
                foreach ($users_contras as $key => $value) {
                    $inputs['user_contra'] = $value;
                    //Mail::to($value->email)->send(new ContractContraMail( $inputs,$pdf));
                    Mail::to('richardsustam23@gmail.com')->send(new ContractContraMail( $inputs,$pdf));
                }
            //}catch (\Exception $e) {
                    //report($e);
                   // \Log::error( 'Error en enviar correo AMHA: '.json_encode($e) );
            //}
            */
            $data = Order::find($order->id);
            $data->documentUrl = Documents::getUrl($data->documents_id);
            $data->created = $data->created_at->format('d-m-Y H:i:s');
            
            $tok = Str::random(60);
            $contra = OrderContact::where('order_id',$order->id)->first();
            if($contra){
                $us = User::find($contra->user_id);
                $us->remember_token = $tok;
                $us->password = bcrypt($tok);
                $us->save();

                $response_data = [
                    'status'=>'Document created successfully',
                    'id'=>$data->id,
                    'signature_url'=>'https://notarionet.com/#/login?token='.$tok.'&email='.$us->email.'&id='.$order->id,
                    'payment_url'=>$request->payment_url,
    
                ];
                return response()->json($response_data);
            }
                
           
            
        }
        else{
            return response()->json(['msg'=>'An error occurred while saving'],500);
        }
    }
    public function storeForeignerSterligndev(Request $request){
        ini_set('memory_limit',-1);
        $userdata = User::find(232);
        $contractdata = Contract::find(31);
        
        $body_format =  $contractdata->body_format;
    
        
        $body_format = str_replace("[day]", $request->day, $body_format);
        $body_format = str_replace("[month]", $request->month, $body_format);
        $body_format = str_replace("[year]", $request->year, $body_format);

        $body_format = str_replace("[name]", $request->customer['name'], $body_format);
        $body_format = str_replace("[birthdate]", $request->customer['birthdate'], $body_format);
        $body_format = str_replace("[phone]", $request->customer['phone'], $body_format);
        $body_format = str_replace("[email]", $request->customer['email'], $body_format);
        $body_format = str_replace("[address]", $request->customer['address'], $body_format);
        $body_format = str_replace("[nacionality]", $request->customer['nacionality'], $body_format);
        $body_format = str_replace("[id_number]", $request->customer['id_number'], $body_format);
        $body_format = str_replace("[marital_status]", $request->customer['marital_status'], $body_format);

        $body_format = str_replace("[property]", $request->property, $body_format);
        $body_format = str_replace("[square_meters]", $request->square_meters, $body_format);
        $body_format = str_replace("[total_price]", $request->total_price, $body_format);
        
        
        
        $order = new Order();
        $order->no_signature_creator = 0;
        $order->type_contracts_id = 7;
        $order->total = 0;
        $order->subtotal = 0;
        $order->contracts_id = 31;
        $order->user_id = $userdata->id;
        $order->signature_user_id = $userdata->signature_image_id;
        $order->status = 'pendiente';
        $order->stamp = Str::random(20);
        $order->certificate = Str::random(20);
        $order->date_signature_user = date('Y-m-d H:i:s');
        $order->body = $body_format;// $request->contenido;
        $order->stripe_link = $request->payment_url;
        $order->type_sandybeach = 'H';
        $order->lots = json_encode($request->lots);
        $order->save();

        $users_ids = [];
        $user_aux = null;
        $checkuser = User::where('email',$request->customer['email'])->first();
        if ($checkuser) {
            $user_aux = $checkuser;
            array_push($users_ids,$user_aux->id);
        }
        else{   
            $user = new User();
            $user->email = $request->customer['email'];
            $user->name = $request->customer['name'];
            $user->password = bcrypt($request->customer['email']);
            //$user->phone = $request->usuario['telefono'];
                
            $user->access = 1;
            $user->from_web = 1;
            $user->email_verified_at = date('Y-m-d H:i:s');
            $user->passport_number = $request->customer['id_number'];
           // $user->credit_card = $request->customer['tarjeta_credito'];
            $user->save();


            $userapp = new UserApp();
            $userapp->nombre = $user->name;
            $userapp->email = $user->email;
            $userapp->tipo = 'usuario';
            $userapp->password = md5($user->email);
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
            
        
        $users_contras = User::whereIn('id',$users_ids)->get();
        
        if (Images::getUrl($userdata->image_id_logo)) {
            $userdata->imageUrl = Images::getUrl($userdata->image_id_logo);
        }
        else{
            $userdata->imageUrl = 'https://notarionet.com/public/images/logo.png';
        }

        $contract = [
            'title'=>'PROMISSORY AGREEMENT',
            'title_url'=>str_replace(' ','-','PROMISSORY AGREEMENT '),
            'contracts_id'=>31,
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
            /*
            //try{
                //Mail::to($inputs['user']['email'])->send(new ContractMail( $inputs, $pdf));
                Mail::to('richardsustam23@gmail.com')->send(new ContractMail( $inputs, $pdf));
                foreach ($users_contras as $key => $value) {
                    $inputs['user_contra'] = $value;
                    //Mail::to($value->email)->send(new ContractContraMail( $inputs,$pdf));
                    Mail::to('richardsustam23@gmail.com')->send(new ContractContraMail( $inputs,$pdf));
                }
            //}catch (\Exception $e) {
                    //report($e);
                   // \Log::error( 'Error en enviar correo AMHA: '.json_encode($e) );
            //}
            */
            $data = Order::find($order->id);
            $data->documentUrl = Documents::getUrl($data->documents_id);
            $data->created = $data->created_at->format('d-m-Y H:i:s');
            
            $tok = Str::random(60);
            $contra = OrderContact::where('order_id',$order->id)->first();
            if($contra){
                $us = User::find($contra->user_id);
                $us->remember_token = $tok;
                $us->password = bcrypt($tok);
                $us->save();

                $response_data = [
                    'status'=>'Document created successfully',
                    'id'=>$data->id,
                    'signature_url'=>'https://notarionet.com/#/login?token='.$tok.'&email='.$us->email.'&id='.$order->id,
                    'payment_url'=>$request->payment_url,
    
                ];
                return response()->json($response_data);
            }
                
           
            
        }
        else{
            return response()->json(['msg'=>'An error occurred while saving'],500);
        }
    }
    public function storeForeignerResell(Request $request){
        ini_set('memory_limit',-1);
        $userdata = User::find(232);
        $contractdata = Contract::find(32);
        
        $body_format =  $contractdata->body_format;
    
        
        $body_format = str_replace("[day]", $request->day, $body_format);
        $body_format = str_replace("[month]", $request->month, $body_format);
        $body_format = str_replace("[year]", $request->year, $body_format);

        $body_format = str_replace("[name]", $request->customer['name'], $body_format);
        $body_format = str_replace("[birthdate]", $request->customer['birthdate'], $body_format);
        $body_format = str_replace("[phone]", $request->customer['phone'], $body_format);
        $body_format = str_replace("[email]", $request->customer['email'], $body_format);
        $body_format = str_replace("[address]", $request->customer['address'], $body_format);
        $body_format = str_replace("[nacionality]", $request->customer['nacionality'], $body_format);
        $body_format = str_replace("[id_number]", $request->customer['id_number'], $body_format);
        $body_format = str_replace("[marital_status]", $request->customer['marital_status'], $body_format);

        $body_format = str_replace("[property]", $request->property, $body_format);
        $body_format = str_replace("[square_meters]", $request->square_meters, $body_format);
        $body_format = str_replace("[total_price]", $request->total_price, $body_format);
        
        
        
        $order = new Order();
        $order->no_signature_creator = 0;
        $order->type_contracts_id = 7;
        $order->total = 0;
        $order->subtotal = 0;
        $order->contracts_id = 32;
        $order->user_id = $userdata->id;
        $order->signature_user_id = $userdata->signature_image_id;
        $order->status = 'pendiente';
        $order->stamp = Str::random(20);
        $order->certificate = Str::random(20);
        $order->date_signature_user = date('Y-m-d H:i:s');
        $order->body = $body_format;// $request->contenido;
        $order->stripe_link = $request->payment_url;
        $order->type_sandybeach = 'I';
        $order->lots = json_encode($request->lots);
        $order->save();

        $users_ids = [];
        $user_aux = null;
        $checkuser = User::where('email',$request->customer['email'])->first();
        if ($checkuser) {
            $user_aux = $checkuser;
            array_push($users_ids,$user_aux->id);
        }
        else{   
            $user = new User();
            $user->email = $request->customer['email'];
            $user->name = $request->customer['name'];
            $user->password = bcrypt($request->customer['email']);
            //$user->phone = $request->usuario['telefono'];
                
            $user->access = 1;
            $user->from_web = 1;
            $user->email_verified_at = date('Y-m-d H:i:s');
            $user->passport_number = $request->customer['id_number'];
           // $user->credit_card = $request->customer['tarjeta_credito'];
            $user->save();


            $userapp = new UserApp();
            $userapp->nombre = $user->name;
            $userapp->email = $user->email;
            $userapp->tipo = 'usuario';
            $userapp->password = md5($user->email);
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
            
        
        $users_contras = User::whereIn('id',$users_ids)->get();
        
        if (Images::getUrl($userdata->image_id_logo)) {
            $userdata->imageUrl = Images::getUrl($userdata->image_id_logo);
        }
        else{
            $userdata->imageUrl = 'https://notarionet.com/public/images/logo.png';
        }

        $contract = [
            'title'=>'PROMISSORY AGREEMENT',
            'title_url'=>str_replace(' ','-','PROMISSORY AGREEMENT '),
            'contracts_id'=>32,
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
            /*
            //try{
                //Mail::to($inputs['user']['email'])->send(new ContractMail( $inputs, $pdf));
                Mail::to('richardsustam23@gmail.com')->send(new ContractMail( $inputs, $pdf));
                foreach ($users_contras as $key => $value) {
                    $inputs['user_contra'] = $value;
                    //Mail::to($value->email)->send(new ContractContraMail( $inputs,$pdf));
                    Mail::to('richardsustam23@gmail.com')->send(new ContractContraMail( $inputs,$pdf));
                }
            //}catch (\Exception $e) {
                    //report($e);
                   // \Log::error( 'Error en enviar correo AMHA: '.json_encode($e) );
            //}
            */
            $data = Order::find($order->id);
            $data->documentUrl = Documents::getUrl($data->documents_id);
            $data->created = $data->created_at->format('d-m-Y H:i:s');
            
            $tok = Str::random(60);
            $contra = OrderContact::where('order_id',$order->id)->first();
            if($contra){
                $us = User::find($contra->user_id);
                $us->remember_token = $tok;
                $us->password = bcrypt($tok);
                $us->save();

                $response_data = [
                    'status'=>'Document created successfully',
                    'id'=>$data->id,
                    'signature_url'=>'https://notarionet.com/#/login?token='.$tok.'&email='.$us->email.'&id='.$order->id,
                    'payment_url'=>$request->payment_url,
    
                ];
                return response()->json($response_data);
            }
                
           
            
        }
        else{
            return response()->json(['msg'=>'An error occurred while saving'],500);
        }
    }
}
