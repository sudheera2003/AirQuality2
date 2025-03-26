<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sensor;
use App\Models\SensorStatus;

class SensorController extends Controller
{
    public function updateAQI($sensorId)
    {
        // Fetch the sensor by its ID
        $sensor = Sensor::find($sensorId);

        if ($sensor) {
            // Generate a random AQI value (you can replace this with a real data source)
            $newAQI = rand(0, 500);

            // Update the AQI value in the database
            $sensor->aqi = $newAQI;
            $sensor->save();

            // Return the updated data as JSON response
            return response()->json([
                'id' => $sensor->id,
                'name' => $sensor->name,
                'aqi' => $sensor->aqi
            ]);
        }

        return response()->json(['error' => 'Sensor not found'], 404);
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'status_id' => 'required|exists:sensor_statuses,id', // Validate that the status_id exists in the table
        ]);

        // Check if the location with the same latitude and longitude already exists
        $existingSensor = Sensor::where('lat', $request->lat)
            ->where('lng', $request->lng)
            ->first();

        if ($existingSensor) {
            // If a sensor exists with the same lat/lng, return back with an error message
            return redirect()->back()->with('error', 'A sensor already exists at this location.')->withInput();
        }

        // Create a new sensor record if no duplicates found
        $sensor = new Sensor();
        $sensor->name = $request->name;
        $sensor->lat = $request->lat;
        $sensor->lng = $request->lng;
        $sensor->aqi = 0; // Initial AQI value
        $sensor->status_id = $request->status_id; // Store the selected status_id

        $sensor->save();

        // Redirect back to the dashboard with a success message
        return redirect()->route('dashboard')->with('success', 'Sensor added successfully!');
    }

    public function getAQI()
    {
        try {
            $sensors = Sensor::where('status_id', 1)->get(['id', 'aqi']);
            return response()->json($sensors, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    

    public function destroy(Request $request)
{
    // Find the sensor by ID
    $sensor = Sensor::find($request->id);

    if (!$sensor) {
        // If sensor does not exist, return error message
        return redirect()->back()->with('error', 'Sensor not found!');
    }

    // Delete related records in the `aqi_histories` table
    $sensor->aqiHistories()->delete();

    // Delete the sensor
    $sensor->delete();

    return redirect()->back()->with('success', 'Sensor deleted successfully!');
}


}
