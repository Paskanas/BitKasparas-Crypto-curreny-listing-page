<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CryptoCurrencyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // dump($request);
        // return parent::toArray($request);
        // dump($this);
        // return [
        //     'id' => $this->map->id,
        // ];
        // return [
        //     'status' => $this->status,
        //     'data' => [
        //         'name' => $this->data->name,
        // 'symbol' => $this->data['symbol'],
        // 'circular_supply' => $this->data['circulating_supply'],
        // 'total_supply' => $this->data['total_supply'],
        // 'quote' => [
        //     'USD' => [
        //         'price' => $this->quote->USD->price,
        //         'volume_24h' => $this->quote->USD->volume_24h,
        //         'volume_change_24h' => $this->quote->USD->volume_change_24h,
        //         'percent_change_1h' => $this->quote->USD->percent_change_1h,
        //         'percent_change_24h' => $this->quote->USD->percent_change_24h,
        //         'market_cap' => $this->quote->USD->market_cap,
        //         'market_cap_dominance' => $this->quote->USD->market_cap_dominance,
        //     ]
        // ]
        //     ]
        // ];
        dump($this->filteredDataFields());
        return [
            'status' => $this->status,
            'data' => $this->filteredDataFields()
        ];
    }

    private function filteredDataFields(): array
    {
        return $this->data->map(function ($item) {
            return [
                'name' => $item['name'],
                'symbol' => $item['symbol'],
                'volume_change_24h' => $item['quote']['USD']['volume_change_24h'],
            ];
        });
    }
}
