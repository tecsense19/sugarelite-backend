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
        Schema::create('user_report', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('subscription')->nullable();
            $table->string('payment_verification')->nullable();
            $table->dateTime('payment_recurring_date')->nullable();
            $table->dateTime('register_date')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_report');
    }
};
