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
        Schema::create('crypto_currency_metadata', function (Blueprint $table) {
            $table->unsignedMediumInteger('currency_id');
            $table->foreign('currency_id')->references('id')->on('crypto_currencies')->onDelete('cascade');
            $table->string('name');
            $table->string('symbol');
            $table->string('slug');
            $table->string('description', 2048);
            $table->date('date_added');
            $table->date('date_launched');
            $table->string('tags', 2048);
            $table->string('category');
            $table->string('logo_url');
            $table->string('urls', 2048); //ar nereiks atskiros lentos?
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crypto_currency_metadata');
    }
};
