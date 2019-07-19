<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Charts\Humidity;
use App\Charts\Temperature;
use App\Datum;
use App\Device;
use Carbon\Carbon;
class ChartController extends Controller
{
    
    public function index($timePeriod = "h", $previousDays = 7) {
    
    if ($timePeriod == "h") {
        $timePeriod = "j-d H";
    }elseif ($timePeriod == "m") {
        $timePeriod = "j-d H:i";
    }elseif ($timePeriod == "d") {
        $timePeriod = "j-d";
    }

    $devices = Datum::distinct('device')->pluck('device')->toArray();
     sort($devices);
       $charts = [];
       $charts['humidity'] = $this->createChart('humidity', $devices, $timePeriod, $previousDays)->options( 
            ['yAxis'=>
                ['scale'=>true],
            ]
        );
       $charts['temperature'] = $this->createChart('temperature', $devices, $timePeriod, $previousDays)->options( 
            ['yAxis'=>
                ['scale'=>true]
            ]
        );;

        $current = ['humidity' => Datum::latest()->where('name','humidity')->first(), 'temperature' => Datum::latest()->where('name','temperature')->first()] ;
    	return view('charts', compact('charts','current', 'deviceNames'));
    }



    public function createChart(String $dataName, Array $devices, $groupBy = "j-d%20g", $previousDays = 10){
        $deviceNames =  Device::all()->keyBy('hardware_name');

        $chart = new Temperature;
        $labels = collect([]);

        $data = Datum::where('name', $dataName)->where( 'created_at', '>', Carbon::now()->subDays($previousDays))->get()->groupBy(function($date) use ($groupBy){
           return Carbon::parse($date->created_at)->format($groupBy);
        });

        // dump($data->count());
        // if ($data->count() > 500) {
        //     $i = 0;
        //     foreach ($data as $key => $value) {
        //         $i++;
        //        if ($i % 2 == 0 || $i % 3 == 0 || $i % 4 == 0) {
        //            unset($data[$key]);
        //        }
        //     }
        // }
        // dump($data->count());


        // Generate labels
        $hourlyData = collect([]);
        foreach ($data as $key => $value) {
            $labels->push($value->first()->created_at->format('j-d g:ia'));
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
