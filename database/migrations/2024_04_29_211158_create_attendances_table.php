<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * aumenta el campo nombre y usuario y elimina los demas campos
     */
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->boolean('present')->default(false);
            $table->date('date');
            $table->date('box_date');
            $table->unsignedBigInteger('box_id');
            $table->timestamps();
            
            $table->unique(['user_id', 'date']);
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('box_id')->references('id')->on('boxes');
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