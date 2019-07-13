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

        $humidityData = Datum::where('name','humidity')->get()->groupBy(function($reg){
            return date('H',strtotime($reg->created_at));
        });

        $temperatureData = Datum::where('name','temperature')->get()->groupBy(function($reg){
            return date('H',strtotime($reg->created_at));
        });

        dump($humidityData);

        $hourlyHumidityData = collect([]);
        foreach ($humidityData as $key => $value) {
            $hourlyHumidityData->push($value->avg('value'));
            $labels->push($value->first()->created_at->toString());
        }

        $hourlyTemperatureData = collect([]);
        foreach ($temperatureData as $key => $value) {
            $hourlyTemperatureData->push($value->avg('value'));
        }


    	$chart->labels($labels);


       
    	$chart->dataset("Humidity", 'line', $hourlyHumidityData)->color('#6dbed6');
    	$chart->dataset("Temperature", 'line', $hourlyTemperatureData)->color("#ffb6b6");

    	return view('charts', compact('chart'));
    }

}
