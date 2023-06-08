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
        Schema::create('coin_gecko_coins', function (Blueprint $table) {
            $table->id();
            $table->string('coin_id');
            $table->string('symbol');
            $table->string('symbol')->references('symbol')->on('crypto_currency_metadata')->onDelete('cascade');
            $table->string('name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coin_gecko_coins');
    }
};
