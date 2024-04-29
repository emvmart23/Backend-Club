<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->boolean('present')->default(false);
            $table->boolean('absent')->default(false);
            $table->boolean('late')->default(false);
            $table->boolean('state')->default(false);
            $table->dateTime('date_now');
            $table->dateTime('date_box')->default(new \DateTime);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
