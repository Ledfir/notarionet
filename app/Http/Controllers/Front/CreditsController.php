<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use App\Models\User;
use App\Models\UsersCredit;
use App\Mail\NewCredits;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class CreditsController extends Controller
{
    protected $stripe_secret_key;

    public function __construct()
    {
        $this->stripe_secret_key = env('STRIPE_MODE') === 'live' 
            ? env('STRIPE_LIVE_SECRET_KEY') 
            : env('STRIPE_TEST_SECRET_KEY');
    }

    public function stripeInstallments(Request $request)
    {
       //se usa el paquete de stripe
       Stripe::setApiKey($this->stripe_secret_key);
       $intent = PaymentIntent::create([
           'payment_method' => $request->payment_method_id,
           'amount' => intval($request->total * 100),
           'currency' => 'MXN',
           'payment_method_options' => [
               'card' => [
                   'installments' => [
                       'enabled' => false
                   ]
               ]
           ],
       ]);

       return response()->json(['intent_id' => $intent->id]);
    }
    public function saveOrder(Request $request)
    {
        $userdata = Auth::user();

        Stripe::setApiKey($this->stripe_secret_key);
        try {
            $intent = PaymentIntent::retrieve($request->payment_intent_id);
            //si el total es diferente al que se tiene registrado en paymentIntent lo actualiza
            if($intent->amount != intval($request->total * 100)){
                PaymentIntent::update($request->payment_intent_id, ['amount' => intval($request->total * 100)]);
                $intent = PaymentIntent::retrieve($request->payment_intent_id);
            }
            //se confirma el pago
            $intent->confirm();

            if($intent->status == 'succeeded'){

                $row = new UsersCredit();
                $row->user_id = $userdata->id;
                $row->quantity = $request->quantity;
                $row->creditos = $request->quantity;
                $row->total = $request->total;
                $row->package_id = $request->package_id;
                $row->payment_intent_id = $request->payment_intent_id;
                $row->payment_method = 'tarjeta';
                $row->status = 'pagado';
                $row->expires_on = date('Y-m-d',strtotime('+365 day'));
                $row->save();

                $user = User::find($userdata->id);
                $user->credits += $request->quantity;
                if ($request->package_id == 1 || $request->package_id == '1') {
                    $user->credits += 1;
                }
                $user->save();

                $inputs = [
                    'quantity'=>$row->quantity,
                    'username'=>$user->name,
                    'id'=>$row->id
                ];
                
                try{
                    Mail::to($user->email)->send(new NewCredits( $inputs));
                }catch (\Exception $e) {
                    //report($e);
                }
                return response()->json(['type' => 'success' ,'data' =>$row]);
            }
            else{
                return ['type' => 'error', 'message' => "No hay suficientes fondos en su tarjeta, por favor utilice otra tarjeta o contacte a su banca para aprobar la transacción"];
            }
        } catch (\Exception $e){
            report($e);
            return ['type' => 'error' , 'message' => $e->getMessage()];
        }
    }

    public function saveOrderDeposit(Request $request)
    {
        $userdata = Auth::user();

        $row = new UsersCredit();
        $row->user_id = $userdata->id;
        $row->quantity = $request->quantity;
        $row->creditos = $request->quantity;
        $row->total = $request->total;
        $row->package_id = $request->package_id;
        $row->payment_intent_id = $request->payment_intent_id;
        $row->payment_method = 'deposito';
        $row->status = 'pediente_pago';
        $row->expires_on = date('Y-m-d',strtotime('+365 day'));
        $row->save();

        $user = User::find($userdata->id);
        /*$user->credits += $request->quantity;
        if ($request->package_id == 1 || $request->package_id == '1') {
            $user->credits += 1;
        }
        $user->save();*/

        $inputs = [
            'quantity'=>$row->quantity,
            'username'=>$user->name,

        ];
        
        try{
            Mail::to($user->email)->send(new NewCredits( $inputs));
        }catch (\Exception $e) {
            //report($e);
        }
        return response()->json($row);

    }
}
