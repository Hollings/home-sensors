<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Datum;

class DatumController extends Controller
{
	public function index(){
    	return Datum::all();
    }

    public function saveData(Request $request){
    	return $request->all();
    }
}
