<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Address;
use App\Models\UserApp;
use App\Models\Order;
use App\Models\OrderContact;
use App\Models\UsersCredit;
use App\Models\Package;
use Illuminate\Support\Facades\Mail;
use App\Mail\AuthorizedAccount;

use Illuminate\Http\Request;
use Documents;
use Images;
class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customer = User::where('usuarios_id','!=',null)->orderBy('id','desc')->get();
        foreach ($customer as $key => $value) {
            if($value->created_at != null)
            {
                $value->created = $value->created_at->format('d-m-Y H:i:s');
            }   
            
            $value->statusbtn = ($value->access)?('<button class="btn btn-success">Autorizado</button>'):('<button class="btn btn-warning">No autorizado</button>');
        }
        return response()->json($customer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Responsea
     */
    public function store(Request $request)
    {
        $user = new User(array(
            'email' => $request->email,
            'name' => $request->name,
            'lastname' => $request->lastname,
            'password' => bcrypt($request->password),
            'phone' => $request->phone,
            'access' => 0,
        ));

        if ($request->file('rfc')) {
            $user->rfc_documents_id = Documents::save($request->file('rfc'));
        }

        if ($request->file('curp')) {
            $user->curp_documents_id = Documents::save($request->file('curp'));
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


        $userapp = new UserApp();
        $userapp->nombre = $request->name.' '.$request->lastname;
        $userapp->email = $request->email;
        $userapp->estado_id = $request->state_id;
        $userapp->ciudad_id = $request->town_id;
        $userapp->num_ext = $request->num_ext;
        $userapp->num_int = $request->num_int;
        $userapp->colonia = $request->neighborhood;
        $userapp->cp = $request->zipcode;
        $userapp->domicilio = $request->street;
        $userapp->tipo = 'usuario';
        $userapp->password = md5($request->password);
        $userapp->save();

        $user->usuarios_id = $userapp->id;
        $user->save();
        

        return response()->json($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user= User::where('id', $id)->first();
        $user->rfcDoc = Documents::getUrl($user->rfc_documents_id);
        $user->curpDoc = Documents::getUrl($user->curp_documents_id);
        $user->inefrontDoc = Documents::getUrl($user->inefront_documents_id);
        $user->inebackDoc = Documents::getUrl($user->ineback_documents_id);
        $user->imageUrl = Images::getUrl($user->image_id);

        $user->signatureUrl = Images::getUrl($user->signature_image_id);

        if($user->address != null){
            $user->street = $user->address->street;
            $user->num_ext = $user->address->num_ext;
            $user->num_int = $user->address->num_int;
            $user->neighborhood = $user->address->neighborhood;
            $user->zipcode = $user->address->zipcode;
            $user->town_id = $user->address->town_id;
            $user->state_id = $user->address->state_id;
        }
        else{
            $user->street = null;
            $user->num_ext = null;
            $user->num_int = null;
            $user->neighborhood = null;
            $user->zipcode = null;
            $user->town_id = null;
            $user->state_id = null;
        }
        


        //contratos que realizo el usuario y espera la firma de otro
        $contractsdata = Order::select('orders.*','contracts.title as contrac_name','users.name as user_name')
                                ->leftJoin('contracts', 'orders.contracts_id', '=', 'contracts.id')
                                ->leftJoin('users', 'orders.user_id', '=', 'users.id')
                                ->join('order_contacts', 'orders.id', '=', 'order_contacts.order_id')
                                ->where('orders.user_id',$id)
                               
                                ->get();
        $contracts = [];
        foreach ($contractsdata as $key => $value) {
            $count = OrderContact::where('order_id',$value->id)->where('signature_user_contra_id',null)->count();
           
            if ($count > 0) {
                $value->created = $value->created_at->format('d-m-Y H:i:s');
                
                $value->btnpdf = '<a class="btn btn-danger" href="'.Documents::getUrl($value->documents_id).'" target="_blank"><i class="fas fa-file-pdf"></i></a>';

                if($value->contracts_id == 0){
                    $value->contrac_name = 'Libre';
                }
                $value->status = 'Esperando firma';
               
                array_push($contracts,$value);
            }
        }

         //por firmar
        $contractsdosdata = Order::select('orders.*','contracts.title as contrac_name','users.name as user_name')
            ->leftJoin('contracts', 'orders.contracts_id', '=', 'contracts.id')
            ->leftJoin('users', 'orders.user_id', '=', 'users.id')
            ->join('order_contacts', 'orders.id', '=', 'order_contacts.order_id')
            ->where('order_contacts.user_id',$id)
            ->get();
        
        foreach ($contractsdosdata as $key => $value) {
            $count = OrderContact::where('order_id',$value->id)->where('signature_user_contra_id',null)->count();

            if ($count > 0) {
                $value->created = $value->created_at->format('d-m-Y H:i:s');
                
                $value->btnpdf = '<a class="btn btn-danger" href="'.Documents::getUrl($value->documents_id).'" target="_blank"><i class="fas fa-file-pdf"></i></a>';

                if($value->contracts_id == 0){
                    $value->contrac_name = 'Libre';
                }
                $value->status = 'Pedientes por firmar';
                array_push($contracts,$value);
            }
        }

        $contractcomplete = Order::select('orders.*','contracts.title as contrac_name','users.name as user_name')
                                ->leftJoin('contracts', 'orders.contracts_id', '=', 'contracts.id')
                                ->leftJoin('users', 'orders.user_id', '=', 'users.id')
                                ->join('order_contacts', 'orders.id', '=', 'order_contacts.order_id')
                                ->where('orders.user_id',$id)
                                ->get();
        
        $contractcompletedos = Order::select('orders.*','contracts.title as contrac_name','users.name as user_name')
                                ->leftJoin('contracts', 'orders.contracts_id', '=', 'contracts.id')
                                ->leftJoin('users', 'orders.user_id', '=', 'users.id')
                                ->join('order_contacts', 'orders.id', '=', 'order_contacts.order_id')
                                ->where('order_contacts.user_id',$id)
                                ->get();
        $contractcompleteimage = Order::select('orders.*','users.name as user_name')
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
                $value->created = $value->created_at->format('d-m-Y H:i:s');
                
                $value->btnpdf = '<a class="btn btn-danger" href="'.Documents::getUrl($value->documents_id).'" target="_blank"><i class="fas fa-file-pdf"></i></a>';

                if($value->contracts_id == 0){
                    $value->contrac_name = 'Libre';
                }
                $value->status = 'Firmados';
                array_push($contracts,$value);
            }
        }

        $user->contracts = $contracts;

        $credits = UsersCredit::where('user_id',$id)->orderBy('id','desc')->get();
        foreach ($credits as $key => $value) {
            if ($value->status == 'pagado') {
                $value->status = 'Pagado';
            }
            elseif ($value->status == 'pediente_pago') {
                $value->status = 'Pendiente de pago';
            }
            $pack = Package::find($value->package_id);
            if ($pack) {
                $value->package = $pack->name;
            }
            $value->created = $value->created_at->format('d-m-Y H:i:s');

            $value->payment_method = ucfirst($value->payment_method);
            $value->total = '$ '.number_format($value->total,2);
        }
        $user->credits = $credits;

        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $user->name = $request->name;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->phone = $request->phone;

        if(isset($request->password)){
            $user->password = bcrypt($request->password);
        }
        
        

        $address = Address::where('user_id', $id)->first();
        $address->street = $request->street;
        $address->num_ext = $request->num_ext;
        $address->num_int = $request->num_int;
        $address->neighborhood = $request->neighborhood;
        $address->zipcode = $request->zipcode;
        $address->state_id = $request->state_id;
        $address->town_id = $request->town_id;
        $address->save();

        if ($request->file('rfc')) {
            if ($user->rfc_documents_id != null) {
                Documents::delete($user->rfc_documents_id);
            }
            $user->rfc_documents_id = Documents::save($request->file('rfc'));
        }

        if ($request->file('curp')) {
            if ($user->curp_documents_id != null) {
                Documents::delete($user->curp_documents_id);
            }
            $user->curp_documents_id = Documents::save($request->file('curp'));
        }

        if ($request->file('inefront')) {
            if ($user->inefront_documents_id != null) {
                Documents::delete($user->inefront_documents_id);
            }
            $user->inefront_documents_id = Documents::save($request->file('inefront'));
        }

        if ($request->file('ineback')) {
            if ($user->ineback_documents_id != null) {
                Documents::delete($user->ineback_documents_id);
            }
            $user->ineback_documents_id = Documents::save($request->file('ineback'));
        }

        if ($request->file('image')) {
            if ($user->image_id != null) {
                Images::delete($user->image_id);
            }
            $user->image_id = Images::save($request->file('image'));
        }
        $user->save();


        $check = UserApp::find($user->usuarios_id);
        if ($check) {
            $userapp = UserApp::find($user->usuarios_id);
            $userapp->nombre = $request->name.' '.$request->lastname;
            $userapp->email = $request->email;
            $userapp->estado_id = $request->state_id;
            $userapp->ciudad_id = $request->town_id;
            $userapp->num_ext = $request->num_ext;
            $userapp->num_int = $request->num_int;
            $userapp->colonia = $request->neighborhood;
            $userapp->cp = $request->zipcode;
            $userapp->domicilio = $request->street;
            if(isset($request->password)){
                $userapp->password = md5($request->password);
            }
            $userapp->save();

        }
        else{
            $userapp = new UserApp();
            $userapp->nombre = $request->name.' '.$request->lastname;
            $userapp->email = $request->email;
            $userapp->estado_id = $request->state_id;
            $userapp->ciudad_id = $request->town_id;
            $userapp->num_ext = $request->num_ext;
            $userapp->num_int = $request->num_int;
            $userapp->colonia = $request->neighborhood;
            $userapp->cp = $request->zipcode;
            $userapp->domicilio = $request->street;
            $userapp->tipo = 'usuario';

            if(isset($request->password)){
                $userapp->password = md5($request->password);
            }
            else{
                $userapp->password = md5($request->email);
            }

            $userapp->save();
            
            
            
            $user->usuarios_id = $userapp->id;
            $user->save();
        }

        return response()->json($user->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($this->_deleteUser($id)){
            return response()->json(['msg'=>'Usuario con ID '.$id.' eliminado.']);
        }
        else{
            return response()->json(['msg'=>'Ocurrio un error al eliminar.'],500);
        }
    }

    public function destroyMultiple(Request $request)
    {
        foreach ($request->ids as $key => $value) {
            $status=$this->_deleteUser($value);
            if(!$status)
                break;
        }

        if ($status) {
            return response()->json(['msg'=>'Usuarios eliminados.']);
        }
        else{
            return response()->json(['msg'=>'Ocurrio un error al eliminar.'],500);
        }
    }

    private function _deleteUser($user_id)
    {
        $user = User::find($user_id);
        $check = UserApp::find($user->usuarios_id);
        if ($check) {
            $check->delete();
        }
        if ($user->delete()) {
            return true;
        }
        else{
            return false;
        }
    }

    public function authorizeCustomer(Request $request)
    {
        foreach ($request->ids as $key => $value) {
            $user = User::find($value);
            $user->access = 1;
            $user->email_verified_at = date('Y-m-d H:i:s');
            $user->save();

            try{
                Mail::to($user->email)->send(new AuthorizedAccount($user)); 
            }catch (\Exception $e) {
               report($e);
            }
        }
        return response()->json(['msg'=>'Usuarios autorizados.']);
    }
    public function addCredits(Request $request)
    {
        
        $pack = Package::find($request->package_id);
        if ($pack) {

            $row = new UsersCredit();
            $row->user_id = $request->user_id;
            $row->quantity = $pack->credits;
            $row->creditos = $pack->credits;
            $row->total = $pack->price;
            $row->package_id = $request->package_id;
            $row->payment_intent_id = null;
            $row->payment_method = 'manual';
            $row->status = 'pagado';
            $row->expires_on = date('Y-m-d',strtotime('+365 day'));
            $row->save();

            $user = User::find($request->user_id);
            $user->credits += $pack->credits;
            if ($request->package_id == 1 || $request->package_id == '1') {
                $user->credits += 1;
            }
            $user->save();
            return $row;
        }

    }

    public function userscredits()
    {
        $rows = UsersCredit::orderBy('id','desc')->get();
        foreach ($rows as $key => $value) {
            if ($value->status == 'pagado') {
                $value->status = 'Pagado';
            }
            elseif ($value->status == 'pediente_pago') {
                $value->status = 'Pendiente de pago';
            }
            $pack = Package::find($value->package_id);
            if ($pack) {
                $value->package = $pack->name;
            }
            $value->created = $value->created_at->format('d-m-Y H:i:s');

            $value->payment_method = ucfirst($value->payment_method);
            $value->total = '$ '.number_format($value->total,2);
            $user = User::find($value->user_id);
            if ($user) {
                $value->customer = $user->name;
            }
        }
        return response()->json($rows);
    }

    public function setPayCredit(Request $request)
    {
        $rows = UsersCredit::orderBy('id','desc')->get();
        foreach ($rows as $key => $value) {
            if ($value->status != 'pagado') {
                $value->status = 'pagado';
                $value->save();
                $pack = Package::find($value->package_id);
                if ($pack) {
        
                    $user = User::find($value->user_id);
                    $user->credits += $pack->credits;
                    if ($value->package_id == 1 || $value->package_id == '1') {
                        $user->credits += 1;
                    }
                    $user->save();
                }
            }
           
            
        }
        return response()->json(['msg'=>'Registros modificados']);
    }
}
