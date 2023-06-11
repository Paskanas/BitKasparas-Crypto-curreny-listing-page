<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CryptoCurrency extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'symbol',
        'logo_url',
        'slug',
        'circulating_supply',
        'total_supply',
        'price',
        'volume_24h',
        'volume_change_24h',
        'percent_change_1h',
        'percent_change_24h',
        'percent_change_7d',
        'market_cap',
        'market_cap_dominance',
        'market_rank',
        'coin_gecko_id',
    ];

    public function metadata()
    {
        return $this->hasOne(CryptoCurrencyMetadata::class, 'currency_id', 'id');
    }

    public function coinGeckoCoins()
    {
        return $this->hasOne(CoinGeckoCoin::class, 'symbol', 'symbol');
    }
}
