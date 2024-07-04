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
        Schema::create('details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("client_id");
            $table->date('issue_date');
            $table->decimal("total_price", 9,2);
            $table->string('box_date')->nullable();
            $table->unsignedBigInteger('hostess_id');
            $table->unsignedBigInteger('current_user')->nullable();
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('customers');
            $table->foreign('current_user')->references('id')->on('users');
            $table->foreign('hostess_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('details');
    }
};
