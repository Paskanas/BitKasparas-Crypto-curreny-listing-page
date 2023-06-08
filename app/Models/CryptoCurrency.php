<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CryptoCurrency extends Model
{
    use HasFactory;

    protected $fillable = [
        'currency_id',
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
    ];

    public function metadata()
    {
        return $this->hasOne(CryptoCurrencyMetadata::class, 'currency_id', 'currency_id');
    }
}
