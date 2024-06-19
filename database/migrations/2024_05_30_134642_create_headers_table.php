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
        Schema::create('headers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mozo_id');
            $table->boolean('state')->default(true);
            $table->boolean('state_doc')->default(true);
            $table->unsignedBigInteger('note_sale')->nullable();
            $table->unsignedBigInteger('note_id')->nullable();
            
            $table->foreign('note_sale')->references('id')->on('details');
            $table->foreign('note_id')->references('id')->on('details');
            $table->foreign('mozo_id')->references('id')->on('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('headers');
    }
};
