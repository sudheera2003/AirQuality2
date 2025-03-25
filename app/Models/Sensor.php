<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    use HasFactory;

    protected $table = 'sensors'; // Ensure this matches your database table name

    protected $fillable = [
        'name', 'lat', 'lng', 'aqi'
    ];
}
