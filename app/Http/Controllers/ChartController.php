<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Charts\Humidity;
use App\Charts\Temperature;
use App\Datum;
class ChartController extends Controller
{
    
    public function index() {
   
      
        $charts = [];
       $charts['humidity'] = $this->createChart('humidity', ['Strut B', 'Strut D']);
       $charts['temperature'] = $this->createChart('temperature', ['Strut B', 'Strut D']);

        $current = ['humidity' => Datum::latest()->where('name','humidity')->first(), 'temperature' => Datum::latest()->where('name','temperature')->first()] ;
    	return view('charts', compact('charts','current'));
    }



    public function createChart(String $dataName, Array $devices, $groupBy = "H"){
        $chart = new Temperature;
        $labels = collect([]);
        $data = Datum::where('name', $dataName)->get()->groupBy(function($reg) use ($groupBy){
            return date($groupBy ,strtotime($reg->created_at));
        });

        // Generate labels
        $hourlyData = collect([]);
        foreach ($data as $key => $value) {
            $labels->push($value->first()->created_at->format('j-d g:00a'));
        }
        $chart->labels($labels);

        // Generate chart data for each device
        $allDeviceData = [];
        foreach ($devices as $device) {
            $dataByTimePeriod = collect([]);
            foreach ($data as $key => $value) {
                $dataByTimePeriod->push($value->where('device', $device)->avg('value'));
            }
            $allDeviceData[] = $dataByTimePeriod;
            $chart->dataset($device, 'line', $dataByTimePeriod)->color('#6dbed6')->options([]);
        }

        return $chart;


    }

}
