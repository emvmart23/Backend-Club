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
        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');
            $table->string('name');
            $table->unsignedBigInteger('hostess_id');
            $table->decimal('price',9,2);
            $table->integer('count');
            $table->decimal('total_price',9,2);
            $table->unsignedBigInteger('header_id');

            $table->foreign('hostess_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
