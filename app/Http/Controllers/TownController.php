<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Town;

class TownController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $towns = Town::select('id', 'name')->where('state_id', $id)->get();
		return response()->json($towns);
    }
}
