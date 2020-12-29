<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Datum;
use App\Device;

class DatumController extends Controller
{
	 protected $dates = ['created_at'];

	public function index(){
    	return Datum::all();
    }

    public function saveData(Request $request){

 		$device = Device::where('hardware_name',$request->device)->first();
		$d = new Datum;
		$d->name='temperature';
		$d->value=$request->temperature + $device->temp_offset;
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
