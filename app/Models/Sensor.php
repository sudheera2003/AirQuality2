<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    use HasFactory;

    protected $table = 'sensors'; // Ensure this matches your database table name

    protected $fillable = [
        'name', 'lat', 'lng', 'aqi', 'status_id'
    ];
    public function status()
    {
        return $this->belongsTo(SensorStatus::class, 'status_id');
    }
    public function aqiHistories()
{
    return $this->hasMany(AqiHistories::class);
}


}
