<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Datum;

class DatumController extends Controller
{
	 protected $dates = ['created_at'];

	public function index(){
    	return Datum::all();
    }

    public function saveData(Request $request){

 
		$d = new Datum;
		$d->name='temperature';
		$d->value=$request->temperature;
		$d->device = $request->device;
		$d->save();

		$d = new Datum;
		$d->name='humidity';
		$d->value=$request->humidity;
		$d->device = $request->device;
		$d->save();

		return "Success";

    }
}
