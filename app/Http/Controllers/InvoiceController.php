<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\State;
use App\Models\Town;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rows = Invoice::orderBy('id', 'desc')->get();
        foreach ($rows as $key => $value) {
            $value->created = $value->created_at->format('d-m-Y H:i:s');
            $state = State::find($value->states_id);
            if ($state) {
                $value->state = $state->name;
            }
            $town = Town::find($value->towns_id);
            if ($town) {
                $value->town = $town->name;
            }
        }
        return $rows; 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $row = new Invoice();
        $row->date = $request->date;
        $row->email = $request->email;
        $row->business_name = $request->business_name;
        $row->rfc = $request->rfc;
        $row->address = $request->address;
        $row->states_id = $request->states_id;
        $row->towns_id = $request->towns_id;
        $row->zip_code = $request->zip_code;
        $row->save();

        return $row;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Categoria  $row
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $row = Invoice::find($id);
        return $row;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Categoria  $row
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $row = Invoice::find($id);
        $row->date = $request->date;
        $row->email = $request->email;
        $row->business_name = $request->business_name;
        $row->rfc = $request->rfc;
        $row->address = $request->address;
        $row->states_id = $request->states_id;
        $row->towns_id = $request->towns_id;
        $row->zip_code = $request->zip_code;
        $row->save();

        return $row;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Categoria  $row
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($this->_delete($id)){
            return response()->json(['msg'=>'registro con ID '.$id.' eliminado.']);
        }
        else{
            return response()->json(['msg'=>'Ocurrio un error al eliminar.'],500);
        }
    }

    public function destroyMultiple(Request $request)
    {
        foreach ($request->ids as $key => $value) {
            $status=$this->_delete($value);
            if(!$status)
                break;
        }

        if ($status) {
            return response()->json(['msg'=>'Registros eliminados.']);
        }
        else{
            return response()->json(['msg'=>'Ocurrio un error al eliminar.'],500);
        }
    }

    private function _delete($id)
    {
        $temp = Invoice::find($id);

        if ($temp->delete()) {
            return true;
        }
        else{
            return false;
        }
    }
}
