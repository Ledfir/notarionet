<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contract;
use App\Models\ContractClause;
use App\Models\ContractImages;
use App\Models\Category;
use Images;

class ContractsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         ini_set('memory_limit',-1);
        $rows = Contract::get();
        foreach ($rows as $key => $value) {
            $category = Category::find($value->categories_id);
            if ($category) {
                $value->category = $category->name;
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
         ini_set('memory_limit',-1);
        $row = new Contract();
        $row->categories_id = $request->categories_id;
        $row->title = $request->title;
        $row->description = $request->description;
        $row->keywords = $request->keywords;
        $row->price = $request->price;
        $row->position = $request->position;
        $row->plain_receipt = $request->plain_receipt ? 1 : 0 ;
        if($request->file('image'))
        {
            $row->images_id = Images::save($request->file('image'));
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
         ini_set('memory_limit',-1);
        $row = Contract::find($id);
        $row->imageUrl = Images::getUrl($row->images_id);

        $clauses = ContractClause::get();
        foreach ($clauses as $key => $value) {
            $value->created = $value->created_at->format('d-m-Y H:i:s');
            $value->updated = $value->updated_at->format('d-m-Y H:i:s');
            $value->detailbtn = '<button class="btn btn-info"><i class="fa fa-edit"></i> Editar</button>';

            $fields = '';
            $array_description = explode("[", $value->description);
            foreach ($array_description as $keydes => $valuedes) {
                $pos = strpos($valuedes, ']');
                if ($pos === false) {
                }
                else{
                    
                    $field = '['.substr($valuedes, 0, $pos).']';
                    if (strlen($fields) > 0) {
                        $fields = $fields.'<br>'.$field;
                    }else{
                        $fields = $field;
                    }
                    
                }
            }
            
            $value->fields = $fields;
        }
        $row->clauses = $clauses;
        $data_clauses = [];
        $clausespaginate = ContractClause::paginate(2);
        
        for ($i=1; $i <= $clausespaginate->lastPage(); $i++) { 
            $aux = [];
            $clausespaginate = ContractClause::paginate(2,['*'], 'page', $i);
            foreach ($clausespaginate as $key => $value) {
                array_push($aux,$value);
            }
            array_push($data_clauses,$aux);
        }   
        $row->clausespaginate = $data_clauses;
       

        $images = ContractImages::where('contracts_id',$id)->get();
        foreach ($images as $key => $value) {
            $value->imageUrl = Images::getUrl($value->images_id);
            $value->imagen = Images::getImg($value->images_id);
            $value->created = $value->created_at->format('d-m-Y H:i:s');
        }
        $row->images = $images;
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
         ini_set('memory_limit',-1);
        $row = Contract::find($id);
        $row->categories_id = $request->categories_id;
        $row->title = $request->title;
        $row->description = $request->description;
        $row->keywords = $request->keywords;
        $row->price = $request->price;
        $row->header_format = $request->header_format;
        $row->medio_format = $request->medio_format;
        $row->inferior_format = $request->inferior_format;
        $row->body_format = $request->body_format;
        $row->position = $request->position;
        $row->plain_receipt = $request->plain_receipt ? 1 : 0 ;
        if($request->file('image'))
        {
            if ($row->images_id != null) {
                Images::delete($row->images_id);
            }
            $row->images_id = Images::save($request->file('image'));
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
        $temp = Contract::find($id);

        if ($temp->delete()) {
            return true;
        }
        else{
            return false;
        }
    }

    public function storeClause(Request $request,$contracts_id)
    {

        if ($request->new == 0) {
            $row = ContractClause::find($request->id);
        }
        else{
            $row = new ContractClause();
        }
        $row->type = $request->type;
        $row->title = $request->title;
        $row->description = $request->description;
        $row->contracts_id = $contracts_id;
        $row->save();

        return $row;
    }

    public function destroyMultipleClause(Request $request)
    {
        foreach ($request->ids as $key => $value) {
            $status=$this->_deleteClause($value);
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
    private function _deleteClause($id)
    {
        $temp = ContractClause::find($id);

        if ($temp->delete()) {
            return true;
        }
        else{
            return false;
        }
    }
    public function storeImage(Request $request,$contracts_id)
    {
        
        if ($request->new == 0) {
            $row = ContractImages::find($request->id);
        }
        else{
            $row = new ContractImages();
        }
        
        $row->description = $request->description;
        $row->contracts_id = $contracts_id;
        $row->images_id = Images::save($request->file('image'));
        $row->save();

        return $row;
    }

    public function destroyMultipleImage(Request $request)
    {
        foreach ($request->ids as $key => $value) {
            $status=$this->_deleteImage($value);
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

    private function _deleteImage($id)
    {
        $temp = ContractImages::find($id);

        if ($temp->delete()) {
            return true;
        }
        else{
            return false;
        }
    }
}
