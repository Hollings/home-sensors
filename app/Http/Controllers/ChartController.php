<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Charts\Humidity;
use App\Charts\Temperature;
use App\Datum;
class ChartController extends Controller
{
    
    public function index() {
    	$chart = new Temperature;
    	$labels = collect([]);
    	foreach (Datum::where('name','humidity')->pluck('created_at') as $date){
    		$labels->push($date->toString());
    	}
    	$chart->labels($labels);
    	$chart->dataset("Humidity", 'line', Datum::where('name','humidity')->pluck('value'));
    	$chart->dataset("Temperature", 'line', Datum::where('name','temperature')->pluck('value'));

    	return view('charts', compact('chart'));
    }

}
