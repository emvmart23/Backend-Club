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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('user')->unique();
            $table->string('name');
            $table->string('password');
            $table->decimal('salary',9,2);
            $table->integer('profit_margin');
            $table->unsignedBigInteger("role_id");
            $table->boolean("is_active")->default(true);
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('role_id')->references('role_id')->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
