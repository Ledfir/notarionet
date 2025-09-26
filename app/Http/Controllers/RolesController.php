<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();
        
        foreach ($roles as $key => $value) {
            $value->users;
        }
        return response()->json($roles);
    }

    
    public function permissions()
    {
        $permissions = Permission::all();
        return response()->json($permissions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name = $request->name;
        Role::create(['name'=>$name, 'guard_name'=>'web']);

        return response()->json(['msg'=>"Rol ".$name." creado."]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::find($id);
        $role->permissions;

        return response()->json($role);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $role = Role::find($id);

        //no se permite modificar el nombre de los roles por default
        if($role->name != 'administrador' && $role->name != 'cliente'){
            $role->name = $request->name;
            $role->save();
        }

        $role->forgetCachedPermissions();
        $permissions = $request->permissions;
        $role->syncPermissions($permissions);

        return response()->json(['msg'=>"Permisos guardados."]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::find($id);
        if($role->name != 'administrador' && $role->name != 'cliente'){
            $role->forgetCachedPermissions();
            $role->delete();
            
            return response()->json(['msg'=>"Rol eliminado."]);
        }else{
            return response()->json(['msg'=>"No se pueden eliminar los Roles predeterminados"], 500);
        }
    }
}
