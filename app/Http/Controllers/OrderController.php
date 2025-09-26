<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderContact;
use App\Models\Address;
use App\Models\User; 
use App\Models\Contract;

use Carbon\Carbon;
use Documents;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        ini_set('memory_limit',-1);
        $orders = Order::with('user')->get();
        
        foreach($orders as $order){
            $order->total = '$'.$order->total;
            $order->subtotal = '$'.$order->subtotal;
            $order->created = $order->created_at->format('d-m-Y H:i:s');
        }

        $ordersToDay   = Order::whereDate('created_at', Carbon::today())->count(); 
        $ordersToMonth = Order::whereDate('created_at', '>', Carbon::now()->subDays(30))->count();
        $totalToDay    = Order::whereDate('created_at', Carbon::today())->sum('total');
        $totalToMonth  = Order::whereDate('created_at', '>', Carbon::now()->subDays(30))->sum('total'); 

        return response()->json(['orders' => $orders, 'totals' => [ 'totalToDay' => $totalToDay, 'totalToMonth'=> $totalToMonth, 'ordersToDay'=> $ordersToDay, 'ordersToMonth'=> $ordersToMonth]]);

        
    }
     public function indexGenerated()
     {
         ini_set('memory_limit',-1);
         $orders = Order::select('id','contracts_id','documents_id','user_id','created_at','stamp','certificate')->get();
        
        foreach($orders as $order){
            $contract = Contract::find($order->contracts_id);
            if ($contract) {
                $order->contractbtn = $contract->title;

                if (Documents::getUrl($order->documents_id)) {
                   $order->contractbtn = $order->contractbtn.'<br><a class="btn btn-info" target="_blank" href="'.Documents::getUrl($order->documents_id).'">Documento</a>';
                }
            }
            $user = User::find($order->user_id);
            if ($user) {
                $order->user_name = $user->name;
                if ($order->signature_user_id != null) {
                    $order->user_name = $order->user_name.'<br><br><b>Fecha y hora de firma:</b> '.$order->created_at->format('d-m-Y H:i:s');
                }

            }
            /*$usercontra = User::find($order->user_contra_id);
            if ($usercontra) {
                $order->user_contra_name = $usercontra->name;
                if ($order->signature_user_contra_id != null) {
                    $order->user_contra_name = $order->user_contra_name.'<br><br><b>Fecha y hora de firma:</b> '.$order->updated_at->format('d-m-Y H:i:s');
                }
            }
            $order->created = $order->created_at->format('d-m-Y H:i:s');
            */
            $contras_id = OrderContact::where('order_id',$order->id)->get()->pluck('user_id');
            $order->user_contra_name = User::whereIn('id', $contras_id)->pluck('name')->implode('<br>');
        }
        return response()->json($orders);
     }


    /**
     * Display the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = [];
        $details = [];

        $order = Order::where('id', $id)->with('town', 'state')->first();
        
        if($order){
            $user = User::where('id', $order->user_id)->with('address')->first();
            $details = OrderDetail::where('order_id', $order->id)->with('product')->get();
        }

        return  response()->json(['order' => $order, 'user' => $user, 'details' => $details]);
    }

    public function setStatus(Request $request, $order_id)
    {
        $order = Order::find($order_id);
        if($order)
        {
            if($order->status != $request->status)
            {
                $order->status = $request->status;
                $order->save();
            }
            return response()->json(['msg'=>'Pedido actualizado correctamente'],200);
        }
        else
            return response()->json(['msg'=>'Ocurrio un error al actualizar el pedido'],500);

    }
}
