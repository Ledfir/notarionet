<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Menu;

class MenusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $perms = $user->getAllPermissions();
        $menus=[];
        foreach ($perms as $key => $value) {
            $menu = Menu::where('permissions_name',$value->name)->where('parent',null)->get();
            foreach ($menu as $k => $v) {
                $v->subMenus;
                array_push($menus, $v);
            }

        }

        return response()->json($menus);

    }
}
