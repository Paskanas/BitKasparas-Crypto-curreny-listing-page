<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoinGeckoCoin extends Model
{
    use HasFactory;

    protected $fillable = [
        'coin_id',
        'symbol',
        'name',
    ];

    public function  coinMarketCap()
    {
        return $this->belongsTo(CryptoCurrencyMetadata::class, 'symbol', 'symbol');
    }
}
