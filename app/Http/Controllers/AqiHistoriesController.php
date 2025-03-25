<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AqiHistories;
use App\Models\Sensor;
use Carbon\Carbon;
use DB;

class AqiHistoriesController extends Controller {
    public function index() {
        $sensors = Sensor::all();
        return view('historical', compact('sensors'));
    }

    public function getDays($sensorId, $month) {
        $days = AqiHistories::where('sensor_id', $sensorId)
            ->whereMonth('created_at', $month) // Changed recorded_at to created_at
            ->selectRaw('DAY(created_at) as day')
            ->distinct()
            ->pluck('day');

        return response()->json(['days' => $days]);
    }

    public function getData($sensorId, $month, $day) {
        $aqiRecords = AqiHistories::where('sensor_id', $sensorId)
            ->whereMonth('created_at', $month) // Changed recorded_at to created_at
            ->whereDay('created_at', $day)
            ->orderBy('created_at')
            ->get(['created_at', 'aqi_value'])
            ->map(function ($record) {
                return [
                    'time' => Carbon::parse($record->created_at)->format('H:i'),
                    'aqi' => $record->aqi_value
                ];
            });

        return response()->json(['aqi' => $aqiRecords]);
    }
    public function getHistoricalAQI($sensorId)
{
    $historicalData = \App\Models\AqiHistories::where('sensor_id', $sensorId)
        ->where('recorded_at', '>=', now()->subDay()) // Get data from the last 24 hours
        ->orderBy('recorded_at')
        ->get();

    return response()->json($historicalData);
}

}
