<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class CryptoCurrencyMetadataController extends Controller
{
    //
    private $baseUrl = 'https://sandbox-api.coinmarketcap.com';
    // private $baseUrl = env('COIN_MARKET_CAP_URL', 'b54bcf4d-1bca-4e8e-9a24-22ff2c3d462c');

    public function store()
    {
        $client = new Client();
        $url = $this->baseUrl . '/v2/cryptocurrency/info';

        $parameters = [
            'id' => '1',
        ];

        $headers = [
            'Accept' => '*/*',
            'X-CMC_PRO_API_KEY' =>  'b54bcf4d-1bca-4e8e-9a24-22ff2c3d462c',
            // 'X-CMC_PRO_API_KEY' =>  env('COIN_MARKET_CAP_API', 'b54bcf4d-1bca-4e8e-9a24-22ff2c3d462c'),
        ];

        $qs = http_build_query($parameters);

        $request = "{$url}?{$qs}";

        $res = $client->request('GET', $request, [
            'headers' => $headers
        ]);

        // dump($res->getStatusCode());

        // dump($res->getBody()->getContents());
        $responseData = json_decode($res->getBody()->getContents(), 1);

        $responseCurrenciesData = $responseData['data'];
    }
}
