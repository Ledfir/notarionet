<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use Images;
class PackagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rows = Package::get();
        foreach ($rows as $key => $value) {
            $value->imagen = Images::getImg($value->image_id);
            $value->imageUrl = Images::getUrl($value->image_id);
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
        $row = new Package();
        $row->name = $request->name;
        $row->description = $request->description;
        $row->credits = $request->credits;
        $row->price = $request->price;
        if ($request->file('image')) {
            $row->image_id = Images::save($request->file('image'));
        }

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
        $row = Package::find($id);
        $row->imageUrl = Images::getUrl($row->image_id);
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
        $row = Package::find($id);
        $row->name = $request->name;
        $row->description = $request->description;
        $row->credits = $request->credits;
        $row->price = $request->price;
        if ($request->file('image')) {
            if ($row->image_id != null) {
                Images::delete($row->image_id);
            }
            $row->image_id = Images::save($request->file('image'));
        }
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
            return response()->json(['msg'=>'Registro con ID '.$id.' eliminado.']);
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
        $temp = Package::find($id);
        if ($temp->image_id != null) {
            Images::delete($temp->image_id);
        }
        if ($temp->delete()) {
            return true;
        }
        else{
            return false;
        }
    }
}
