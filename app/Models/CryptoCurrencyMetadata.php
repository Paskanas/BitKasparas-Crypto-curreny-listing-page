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
        'urls'
    ];

    public function  cryptoCurrency()
    {
        return $this->belongsTo(CryptoCurrency::class, 'currency_id', 'id');
    }
}
