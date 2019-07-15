<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Charts\Humidity;
use App\Charts\Temperature;
use App\Datum;
use App\Device;

class ChartController extends Controller
{
    
    public function index($timePeriod = "H") {
    

    $devices = Datum::distinct('device')->pluck('device')->toArray();
     sort($devices);
       $charts = [];
       $charts['humidity'] = $this->createChart('humidity', $devices, $timePeriod);
       $charts['temperature'] = $this->createChart('temperature', $devices, $timePeriod);

        $current = ['humidity' => Datum::latest()->where('name','humidity')->first(), 'temperature' => Datum::latest()->where('name','temperature')->first()] ;
    	return view('charts', compact('charts','current', 'deviceNames'));
    }



    public function createChart(String $dataName, Array $devices, $groupBy = "H"){
        $deviceNames =  Device::all()->keyBy('hardware_name');

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
            $chart->dataset($deviceNames[$device]->alias ?? $device, 'line', $dataByTimePeriod)->color("#" . $this->stringToColorCode($device))->options([]);
        }

        return $chart;


    }

   private function stringToColorCode($str) {
      $code = dechex(crc32($str));
      $code = substr($code, 0, 6);
      return $code;
    }

}
