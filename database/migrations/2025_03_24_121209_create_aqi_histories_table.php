<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('aqi_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sensor_id')->constrained()->onDelete('cascade'); // Foreign key to sensors table
            $table->integer('aqi_value');
            $table->timestamp('recorded_at')->useCurrent(); // Ensure this column exists
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('aqi_histories');
    }
};

