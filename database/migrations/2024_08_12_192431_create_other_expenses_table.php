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
        Schema::create('other_expenses', function (Blueprint $table) {
            $table->id();
            $table->integer('count');
            $table->string('name');
            $table->unsignedBigInteger('unit_id');
            $table->integer('total');
            $table->string('box_date')->nullable();
            $table->unsignedBigInteger('current_user')->nullable();
            $table->timestamps();

            $table->foreign('unit_id')->references('unit_id')->on('unit_measures');
            $table->foreign('current_user')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('other_expenses');
    }
};
