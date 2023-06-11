<?php

namespace App\Console\Commands;

use App\Models\CoinGeckoCoin;
use App\Models\CryptoCurrency;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class FetchCryptocurrencyDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crypto:fetch {start} {quantity}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetching crytocurrencies data from coinmarketcap.com';


    private function makeRequest($start, $limit)
    {
        try {
            $baseUrl = env('COIN_MARKET_CAP_URL', 'b54bcf4d-1bca-4e8e-9a24-22ff2c3d462c');
            $client = new Client();
            $url = $baseUrl . '/v1/cryptocurrency/listings/latest';

            $parameters = [
                'start' => $start,
                'limit' => $limit,
                'sort' => 'market_cap',
                'cryptocurrency_type' => 'all',
            ];

            $headers = [
                'Accept' => '*/*',
                'X-CMC_PRO_API_KEY' =>  env('COIN_MARKET_CAP_API', 'b54bcf4d-1bca-4e8e-9a24-22ff2c3d462c'),
            ];

            $qs = http_build_query($parameters);
            $request = "{$url}?{$qs}";
            $res = $client->request('GET', $request, [
                'headers' => $headers
            ]);
        } catch (\Exception $e) {
            $this->error('An error occurred while fetching cryptocurrency data: ' . $e->getMessage());
            return;
        }
        return $res;
    }

    private function prepareDataAndSave($res)
    {
        if ($res->getStatusCode() === 200) {
            $cryptocurrencies = json_decode($res->getBody()->getContents(), true);

            $responseCurrenciesData = $cryptocurrencies['data'];

            //Saving to database
            $this->save($responseCurrenciesData);
        } else {
            $this->error('Failed to fetch cryptocurrency data. API responded with status code: ' . $res->getStatusCode());
            return;
        }
    }

    private function save($cryptoCurrencies)
    {
        foreach ($cryptoCurrencies as $cryptoCurrency) {
            $coinGeckoCoin = CoinGeckoCoin::where('symbol', $cryptoCurrency['symbol'])->first();
            if ($coinGeckoCoin) {
                try {
                    CryptoCurrency::updateOrCreate(
                        [
                            'id' => $cryptoCurrency['id']
                        ],
                        [
                            'id' => $cryptoCurrency['id'],
                            'name' => $cryptoCurrency['name'],
                            'symbol' => $cryptoCurrency['symbol'],
                            'slug' => $cryptoCurrency['slug'],
                            'price' => round($cryptoCurrency['quote']['USD']['price'], 2),
                            'circulating_supply' => round($cryptoCurrency['circulating_supply'], 2),
                            'total_supply' => round($cryptoCurrency['total_supply'], 2),
                            'volume_24h' => round($cryptoCurrency['quote']['USD']['volume_24h'], 2),
                            'volume_change_24h' => round($cryptoCurrency['quote']['USD']['volume_change_24h'], 2),
                            'percent_change_1h' => round($cryptoCurrency['quote']['USD']['percent_change_1h'], 2),
                            'percent_change_24h' => round($cryptoCurrency['quote']['USD']['percent_change_24h'], 2),
                            'percent_change_7d' => round($cryptoCurrency['quote']['USD']['percent_change_7d'], 2),
                            'market_cap' => round($cryptoCurrency['quote']['USD']['market_cap'], 2),
                            'market_rank' => $cryptoCurrency['cmc_rank'],
                            'market_cap_dominance' => round($cryptoCurrency['quote']['USD']['market_cap_dominance'], 2),
                            //add coingecko id
                            'coin_gecko_id' => $coinGeckoCoin->coin_id ?? '',
                        ]
                    );
                } catch (\Exception $e) {
                    dump($e->getMessage());
                }
            }
        }
    }

    private function fetchAndSaveData($start, $quantity)
    {
        if ($quantity === 'all') {
            $itemsPerRequest = 3000;
            Artisan::call('coinGeckoCoins:fetch');
            $res = $this->makeRequest($start, $itemsPerRequest);
        } else {
            $res = $this->makeRequest($start, $quantity);
        }

        if ($res->getStatusCode() === 200) {
            $cryptocurrencies = json_decode($res->getBody()->getContents(), true);

            $responseCurrenciesData = $cryptocurrencies['data'];
            //commiting when after all insert/update commands are finished
            DB::beginTransaction();

            //saving to database
            $this->save($responseCurrenciesData);

            if ($quantity === 'all') {
                //calculating request count
                $responseCurrenciesStatus = $cryptocurrencies['status'];
                $totalCoinsCount = $responseCurrenciesStatus['total_count'];
                $requestCountLeft = ceil($totalCoinsCount / $itemsPerRequest) - 1;
                foreach (range(1, $requestCountLeft) as $i) {
                    $start = $i * $itemsPerRequest + 1;
                    $res = $this->makeRequest($start, $itemsPerRequest);
                    $this->prepareDataAndSave($res);
                }
            }
            DB::commit();
        } else {
            DB::rollBack();
            $this->error('Failed to fetch cryptocurrency data. API responded with status code: ' . $res->getStatusCode());
            return;
        }
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $start = $this->argument('start');
        $quantity = $this->argument('quantity');

        $this->fetchAndSaveData($start, $quantity);
    }
}
