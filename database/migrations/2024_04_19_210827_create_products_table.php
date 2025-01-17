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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price',9,2);
            $table->unsignedBigInteger('category_id'); 
            $table->unsignedBigInteger('unit_id');
            $table->boolean('has_alcohol')->default(false);
            $table->timestamps();

            $table->foreign('category_id')->references('category_id')->on('categories');
            $table->foreign('unit_id')->references('unit_id')->on('unit_measures');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};