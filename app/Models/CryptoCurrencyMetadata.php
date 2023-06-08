<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CryptoCurrencyMetadata extends Model
{
    use HasFactory;

    protected $fillable = [
        'currency_id',
        'name',
        'symbol',
        'slug',
        'description',
        'date_added',
        'date_launched',
        'tags',
        'category',
        'logo_url',
        'urls' //ar nereiks atskiros lentos?
    ];

    public function  cryptoCurrency()
    {
        return $this->belongsTo(CryptoCurrency::class, 'currency_id', 'currency_id');
    }

    public function coinGeckoCoins()
    {
        return $this->hasOne(CoinGeckoCoin::class, 'symbol', 'symbol');
    }
}
