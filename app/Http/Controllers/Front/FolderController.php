<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Folder;
use App\Models\FoldersOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class FolderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userdata = Auth::user();

        $rows = Folder::where('users_id',$userdata->id)->get();
        return $rows;
    }
    public function moveDoc(Request $request)
    {
       
        foreach ($request->check_signatured as $key => $value) {
            $check = FoldersOrder::where('orders_id',$value)->where('users_id',Auth::user()->id)->first();
            if ($check) {
                $fold = FoldersOrder::find($check->id);
                $fold->folders_id = $request->folders_id;
                $fold->save();
            }
            else{
                $fold = new FoldersOrder();
                $fold->folders_id = $request->folders_id;
                $fold->orders_id = $value;
                $fold->users_id = Auth::user()->id;
                $fold->save();
            }
            
        }
        
        return 'ok';
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $row = new Folder();
        $row->users_id = $request->users_id;
        $row->name = $request->name;
        $row->save();
        return $row;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Folder  $folder
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Folder  $folder
     * @return \Illuminate\Http\Response
     */
    public function edit(Folder $folder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Folder  $folder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Folder $folder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Folder  $folder
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $fold = Folder::find($id);
        $ros = FoldersOrder::where('folders_id',$id)->delete();
        if ($fold->delete()) {
            return 'ok';
        }
    }
}
