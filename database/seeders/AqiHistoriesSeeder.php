<?php

namespace Database\Seeders;

use App\Models\AqiHistories;
use App\Models\Sensor;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AqiHistoriesSeeder extends Seeder
{
    public function run(): void
    {
        $sensors = Sensor::all();

        if ($sensors->isEmpty()) {
            $this->command->error('No sensors found! Please seed sensors first.');
            return;
        }

        $this->command->info('Generating complete AQI history data...');

        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();

        $progressBar = $this->command->getOutput()->createProgressBar(
            $startDate->diffInHours($endDate) * $sensors->count()
        );

        foreach ($sensors as $sensor) {
            $currentDate = $startDate->copy();

            while ($currentDate->lt($endDate)) {
                // Ensure don't create duplicate records
                if (!AqiHistories::where('sensor_id', $sensor->id)
                    ->where('recorded_at', $currentDate)
                    ->exists()) {

                    $hour = $currentDate->hour;
                    $baseAqi = $this->getBaseAqiForHour($hour);
                    $weekendFactor = $currentDate->isWeekend() ? 1.15 : 1.0;
                    $randomVariation = rand(-5, 5);

                    AqiHistories::create([
                        'sensor_id' => $sensor->id,
                        'aqi_value' => max(10, min(150, round($baseAqi * $weekendFactor + $randomVariation))),
                        'recorded_at' => $currentDate,
                        'created_at' => $currentDate,
                        'updated_at' => $currentDate
                    ]);
                }

                $currentDate->addHour();
                $progressBar->advance();
            }
        }

        $progressBar->finish();
        $this->command->info("\nSuccessfully generated complete AQI history data!");
    }

    private function getBaseAqiForHour(int $hour): int
    {
        $dailyPattern = [
            0 => 35,
            1 => 32,
            2 => 30,
            3 => 28,
            4 => 30,
            5 => 40,
            6 => 60,
            7 => 75,
            8 => 85,
            9 => 80,
            10 => 75,
            11 => 70,
            12 => 65,
            13 => 70,
            14 => 75,
            15 => 80,
            16 => 85,
            17 => 90,
            18 => 85,
            19 => 75,
            20 => 60,
            21 => 50,
            22 => 45,
            23 => 40
        ];

        return $dailyPattern[$hour] ?? 50;
    }
}
