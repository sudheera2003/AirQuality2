<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AqiHistories;
use App\Models\Sensor;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AqiHistoriesController extends Controller
{
    public function index()
    {
        $sensors = Sensor::all();
        return view('historical', compact('sensors'));
    }

    public function getDays($sensorId, $month)
    {
        $days = AqiHistories::where('sensor_id', $sensorId)
            ->whereMonth('created_at', $month)
            ->selectRaw('DAY(created_at) as day')
            ->distinct()
            ->pluck('day');

        return response()->json(['days' => $days]);
    }

    public function getData($sensorId, $month, $day)
    {
        $aqiRecords = AqiHistories::where('sensor_id', $sensorId)
            ->whereMonth('created_at', $month)
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
            ->where('recorded_at', '>=', now()->subDay())
            ->orderBy('recorded_at')
            ->get();

        return response()->json($historicalData);
    }

    public function history(Sensor $sensor, Request $request)
    {
        try {
            $request->validate([
                'period' => 'sometimes|in:day,week,month'
            ]);
            
            $period = $request->input('period', 'day');
            
            // Verify sensor exists
            if (!$sensor->exists) {
                return response()->json(['error' => 'Sensor not found'], 404);
            }
            
            $query = AqiHistories::where('sensor_id', $sensor->id);
            
            switch ($period) {
                case 'day':
                    $data = $query->select(
                            DB::raw('HOUR(recorded_at) as hour'),
                            DB::raw('ROUND(AVG(aqi_value), 2) as avg_aqi')
                        )
                        ->where('recorded_at', '>=', now()->subDay())
                        ->groupBy('hour')
                        ->orderBy('hour')
                        ->get();
                    break;
                    
                case 'week':
                    $data = $query->select(
                            DB::raw('DATE(recorded_at) as date'),
                            DB::raw('ROUND(AVG(aqi_value), 2) as avg_aqi')
                        )
                        ->where('recorded_at', '>=', now()->subWeek())
                        ->groupBy('date')
                        ->orderBy('date')
                        ->get();
                    break;
                    
                case 'month':
                    $data = $query->select(
                            DB::raw('WEEK(recorded_at, 1) as week'),
                            DB::raw('ROUND(AVG(aqi_value), 2) as avg_aqi')
                        )
                        ->where('recorded_at', '>=', now()->subMonth())
                        ->groupBy('week')
                        ->orderBy('week')
                        ->get();
                    break;
                    
                default:
                    return response()->json(['error' => 'Invalid period specified'], 400);
            }
            
            return response()->json($data);
            
        } catch (\Exception $e) {
            Log::error("Failed to fetch sensor history", [
                'sensor_id' => $sensor->id ?? null,
                'period' => $period ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Failed to fetch historical data',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
