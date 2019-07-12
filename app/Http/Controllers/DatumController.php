<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DatumController extends Controller
{
    public function saveData(Request $request){
    	return $request->all();
    }
}
