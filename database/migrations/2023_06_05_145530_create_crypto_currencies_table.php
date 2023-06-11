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
            $table->unsignedMediumInteger('id')->primary();
            $table->string('name');
            $table->string('symbol');
            $table->string('slug')->index();
            $table->float('circulating_supply', 25, 2);
            $table->float('total_supply', 30, 2);
            $table->float('price', 20, 2);
            $table->float('volume_24h', 20, 2);
            $table->float('volume_change_24h', 20, 2);
            $table->float('percent_change_1h', 20, 2);
            $table->float('percent_change_24h', 20, 2);
            $table->float('percent_change_7d', 20, 2);
            $table->unsignedMediumInteger('market_rank');
            $table->float('market_cap', 20, 2);
            $table->float('market_cap_dominance', 6, 2);
            $table->string('coin_gecko_id')->default('')->nullable();
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
