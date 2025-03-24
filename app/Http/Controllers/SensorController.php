<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sensor;

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
        $sensor->save();

        // Redirect back to the dashboard with a success message
        return redirect()->route('dashboard')->with('success', 'Sensor added successfully');
    }

    public function getAQI($sensorId)
    {
        $sensor = Sensor::find($sensorId); // Retrieve the sensor from the database by its ID

        if ($sensor) {
            return response()->json(['aqi' => $sensor->aqi]); // Return the AQI value
        }

        return response()->json(['error' => 'Sensor not found'], 404); // Return error if sensor not found
    }
}
