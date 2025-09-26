<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\State;

class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $states = State::select('id', 'name')->get();
		return response()->json($states);
    }
}
