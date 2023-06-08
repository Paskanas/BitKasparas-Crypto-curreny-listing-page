<?php

namespace App\Console\Commands;

use App\Models\CryptoCurrency;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class FetchCryptocurrencyDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crypto:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetching crytocurrencies data from coinmarketcap.com';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $baseUrl = 'https://sandbox-api.coinmarketcap.com';
            $baseUrl = env('COIN_MARKET_CAP_URL', 'b54bcf4d-1bca-4e8e-9a24-22ff2c3d462c');
            $client = new Client();
            $url = $baseUrl . '/v1/cryptocurrency/listings/latest';
            $parameters = [
                'start' => '1',
                // 'limit' => '2',
                'sort' => 'market_cap',
                'cryptocurrency_type' => 'all',
            ];

            $headers = [
                'Accept' => '*/*',
                // 'X-CMC_PRO_API_KEY' =>  'b54bcf4d-1bca-4e8e-9a24-22ff2c3d462c',
                'X-CMC_PRO_API_KEY' =>  env('COIN_MARKET_CAP_API', 'b54bcf4d-1bca-4e8e-9a24-22ff2c3d462c'),
            ];

            $qs = http_build_query($parameters);
            $request = "{$url}?{$qs}";

            $res = $client->request('GET', $request, [
                'headers' => $headers
            ]);


            if ($res->getStatusCode() === 200) {
                $cryptocurrencies = json_decode($res->getBody()->getContents(), true);
                dump($cryptocurrencies);
                // Process and save the data

                // $filteredResponse = new CryptoCurrencyResource($responseData);
                // $filteredResponse = collect($responseData)->map(function ($item) {
                //     dump('item');
                //     dump($item);
                //     return $item;
                //     // return [
                //     //     'name' => $item['name'],
                //     //     // Add more fields as needed
                //     // ];
                // });
                // dump($filteredResponse);
                // $responseStatus = $responseData['status'];
                $responseCurrenciesData = $cryptocurrencies['data'];

                // dump($responseCurrenciesData);
                $this->save($responseCurrenciesData);
                dump('saved');
                $this->info('Cryptocurrency data fetched and saved successfully.');
            } else {
                $this->error('Failed to fetch cryptocurrency data. API responded with status code: ' . $res->getStatusCode());
                return;
            }
        } catch (\Exception $e) {
            $this->error('An error occurred while fetching cryptocurrency data: ' . $e->getMessage());
        }
    }

    private function save($cryptoCurrencies)
    {
        foreach ($cryptoCurrencies as $cryptoCurrency) {

            $logo_url = '';

            try {
                CryptoCurrency::updateOrCreate(
                    [
                        'currency_id' => $cryptoCurrency['id']
                    ],
                    [
                        'currency_id' => $cryptoCurrency['id'],
                        'name' => $cryptoCurrency['name'],
                        'symbol' => $cryptoCurrency['symbol'],
                        'slug' => $cryptoCurrency['slug'],
                        'price' => number_format($cryptoCurrency['quote']['USD']['price'], 2, '.', ','),
                        'circulating_supply' => number_format($cryptoCurrency['circulating_supply'], 2, '.', ','),
                        'total_supply' => number_format($cryptoCurrency['total_supply'], 2, '.', ','),
                        'volume_24h' => $cryptoCurrency['quote']['USD']['volume_24h'],
                        'volume_change_24h' => $cryptoCurrency['quote']['USD']['volume_change_24h'],
                        'percent_change_1h' => $cryptoCurrency['quote']['USD']['percent_change_1h'],
                        'percent_change_24h' => $cryptoCurrency['quote']['USD']['percent_change_24h'],
                        'percent_change_7d' => $cryptoCurrency['quote']['USD']['percent_change_7d'],
                        'market_cap' => $cryptoCurrency['quote']['USD']['market_cap'],
                        'market_cap_dominance' => $cryptoCurrency['quote']['USD']['market_cap_dominance'],
                    ]
                );
            } catch (\Exception $e) {
                dump($e->getMessage());
            }
        }
    }
}
