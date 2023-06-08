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
        Schema::create('crypto_currencies', function (Blueprint $table) {
            // $table->id();
            $table->unsignedMediumInteger('currency_id')->primary();
            $table->string('name');
            $table->string('symbol');
            $table->string('logo_url')->default('');
            $table->string('slug');
            $table->decimal('circulating_supply', 25, 7);
            $table->decimal('total_supply', 25, 7);
            // platform need different table if need token_address differ
            // tags need different table if need
            $table->decimal('price', 18, 7);
            $table->decimal('volume_24h', 18, 7);
            $table->decimal('volume_change_24h', 18, 7);
            $table->decimal('percent_change_1h', 18, 7);
            $table->decimal('percent_change_24h', 18, 7);
            $table->decimal('percent_change_7d', 18, 7);
            $table->decimal('market_cap', 25, 7);
            $table->decimal('market_cap_dominance', 18, 7);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crypto_currencies');
    }
};
