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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("detail_id");
            $table->unsignedBigInteger("payment_id");
            $table->decimal("mountain",9,2);
            $table->string("reference");
            $table->timestamps();

            $table->foreign("detail_id")->references("id")->on("details");
            $table->foreign("payment_id")->references("id")->on("method_payments");

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
