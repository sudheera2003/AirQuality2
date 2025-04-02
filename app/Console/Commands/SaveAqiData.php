<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AqiHistories;
use App\Models\Sensor;
use Illuminate\Support\Facades\Http;

class SaveAqiData extends Command {
    protected $signature = 'aqi:save';
    protected $description = 'Save AQI data from sensors every hour';

    public function handle() {
        $sensors = Sensor::all();

        foreach ($sensors as $sensor) {
            $latestAqi = $sensor->aqi;

            AqiHistories::create([
                'sensor_id' => $sensor->id,
                'aqi_value' => $latestAqi
            ]);
        }

        $this->info('AQI data saved successfully.');
    }
}

