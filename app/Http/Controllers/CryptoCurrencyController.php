<?php

namespace App\Http\Controllers;

use App\Http\Resources\CryptoCurrencyResource;
use App\Models\CryptoCurrency;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use Illuminate\Foundation\Application;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Davaxi\Sparkline;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class CryptoCurrencyController extends Controller
{
    private $baseUrl = 'https://sandbox-api.coinmarketcap.com';
    // private $baseUrl = 'https://pro-api.coinmarketcap.com';

    public function index()
    {
        // $perPage = 10;

        // $client = new Client();
        // $url = $this->baseUrl . '/v1/cryptocurrency/listings/latest';

        // $parameters = [
        //     'start' => '1',
        //     'limit' => '2',
        //     'sort' => 'market_cap',
        //     'cryptocurrency_type' => 'all',
        // ];

        // $headers = [
        //     'Accept' => '*/*',
        //     'X-CMC_PRO_API_KEY' =>  'b54bcf4d-1bca-4e8e-9a24-22ff2c3d462c',
        //     // 'X-CMC_PRO_API_KEY' =>  env('COIN_MARKET_CAP_API', 'b54bcf4d-1bca-4e8e-9a24-22ff2c3d462c'),
        // ];

        // $qs = http_build_query($parameters);
        // $request = "{$url}?{$qs}";

        // $res = $client->request('GET', $request, [
        //     'headers' => $headers
        // ]);

        // $responseData = json_decode($res->getBody()->getContents(), 1);

        // $responseCurrenciesData = $responseData['data'];
        // //saving to database
        // // if ($responseCurrenciesData) {
        // //     dump($responseCurrenciesData);
        // //     $this->store($responseCurrenciesData);
        // // }

        // // $sparkline = new Sparkline();
        // // $sparkline->setData(array(2, 4, 5, 6, 10, 7, 8, 5, 7, 7, 11, 8, 6, 9, 11, 9, 13, 14, 12, 16));
        // // $img = $sparkline->display();
        // // dump($img);

        // $filteredResponse = collect($responseCurrenciesData)->map(function ($item) {
        //     return [
        //         'id' => $item['id'],
        //         'name' => $item['name'],
        //         'symbol' => $item['symbol'],
        //         'slug' => $item['slug'],
        //         'price' => round($item['quote']['USD']['price'], 2),
        //         'percent_change_1h' => round($item['quote']['USD']['percent_change_1h'], 2),
        //         'percent_change_24h' => round($item['quote']['USD']['percent_change_24h'], 2),
        //         'percent_change_7d' => round($item['quote']['USD']['percent_change_7d'], 2),
        //         'market_cap' => round($item['quote']['USD']['market_cap'], 0),
        //         'volume_change_24h' => number_format($item['quote']['USD']['volume_24h'], 0, '.', ','),
        //         'volume_change_24h_btc' => number_format($item['quote']['BTC']['volume_24h'], 0, '.', ','),
        //         'circular_supply' => number_format($item['circulating_supply'], 0, '.', ','),
        //         // Add more fields as needed
        //     ];
        // });
        if (!Cache::has('crypto_fetch_last_executed') || now()->diffInSeconds(Cache::get('crypto_fetch_last_executed')) >= 1) {
            Artisan::call('crypto:fetch');
            Cache::put('crypto_fetch_last_executed', now(), 1);
        }
        $cryptoCurrencies = CryptoCurrency::orderBy('market_cap', 'desc')->paginate(20);

        // $perPage = 2;
        // $currentPage = request()->get('page', 1);
        // $paginationData = new LengthAwarePaginator(
        //     $filteredResponse,
        //     $filteredResponse->count(),
        //     $perPage,
        //     $currentPage,
        //     ['path' => request()->url()]
        // );


        // dump($paginationData);

        return Inertia::render('CryptoCurrency/Index', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'cryptoCurrencies' => $cryptoCurrencies
            // 'cryptoCurrenciesData' => $filteredResponse,
            // 'paginationData' => $paginationData,
        ]);
    }



    private function fetchCryptoCurrencyMetadata()
    {
    }

    // public function show(string $cryptoCurrencyName)
    // {
    //     $cryptoCurrency = CryptoCurrency::where('name', $cryptoCurrencyName)->first();

    //     if ($cryptoCurrency->metadata) {
    //         $metadata = $cryptoCurrency->metadata;
    //     }

    //     return Inertia::render('CryptoCurrency/Show', [
    //         'cryptoCurrency' => $cryptoCurrency
    //     ]);
    // }

    public function show()
    {

        $slug = Route::current()->parameter('cryptoCurrency');
        dump($slug);
        $cryptoCurrency = CryptoCurrency::where('slug', $slug)->first();
        dump($cryptoCurrency);

        if (isset($cryptoCurrency->metadata)) {
            dump('imu is db');
            $cryptoCurrencyMetadata = $cryptoCurrency->metadata;
        } else {
            dump('kvieciu api');
            Artisan::call('cryptoMetadata:fetch ' . $cryptoCurrency->currency_id);
            $cryptoCurrency = CryptoCurrency::where('slug', $slug)->first();
            $cryptoCurrencyMetadata = $cryptoCurrency->metadata;
        }
        if (!isset($cryptoCurrencyMetadata->coinGeckoCoins)) {
            dump('fetch coingecko');
            $exitCode = Artisan::call('coinGeckoCoins:fetch');
            if ($exitCode === 0) {
                $symbol = $cryptoCurrencyMetadata->coinGeckoCoins->coin_id;
                // Continue with the rest of your code here
            } else {
                dump('An error occurred while fetching coinGeckoCoins');
            }
        } else {
            $symbol = $cryptoCurrencyMetadata->coinGeckoCoins->coin_id;
        }

        return Inertia::render(
            'CryptoCurrency/Show',
            [
                'canLogin' => Route::has('login'),
                'canRegister' => Route::has('register'),
                'cryptoCurrency' => $cryptoCurrency,
                'symbol' => $symbol ?? null,
            ]
        );
    }
}
