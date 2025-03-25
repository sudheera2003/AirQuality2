<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SensorStatus extends Model
{
    protected $table = 'sensor_statuses';
    protected $fillable = [
        'status',
    ];
}
