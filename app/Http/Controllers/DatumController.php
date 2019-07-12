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

 
		$d = new Datum;
		$d->name='temp';
		$d->value=$request->temperature;
		$d->save();

		$d = new Datum;
		$d->name='humid';
		$d->value=$request->humidity;
		$d->save();

		return "Success";

    }
}
