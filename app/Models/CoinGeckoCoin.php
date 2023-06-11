<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoinGeckoCoin extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'coin_id',
        'symbol',
        'name',
        'created_at',
        'updated_at'
    ];
}
