<?php

namespace App\Console\Commands;

use App\Models\CoinGeckoCoin;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FetchCoinGeckoCoinsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coinGeckoCoins:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // $baseUrl = 'https://sandbox-api.coinmarketcap.com';
        $baseUrl = env('COINGECKO_URL', 'https://api.coingecko.com');
        try {
            $client = new Client();
            $url = $baseUrl . '/api/v3/coins/list';
            $parameters = [
                'include_platform' => false,
            ];

            $qs = http_build_query($parameters);
            $request = "{$url}?{$qs}";

            $res = $client->request('GET', $request);


            if ($res->getStatusCode() === 200) {
                $coinGeckoCoins = json_decode($res->getBody()->getContents(), true);

                dump($coinGeckoCoins);
                // dump($responseCurrenciesData);
                $this->save($coinGeckoCoins);
                dump('saved');
                $this->info('CoinGecko coins fetched successfully.');
            } else {
                $this->error('Failed to fetch coingecko data. API responded with status code: ' . $res->getStatusCode());
                return;
            }
        } catch (\Exception $e) {
            $this->error('An error occurred while fetching coingecko data: ' . $e->getMessage());
        }
    }

    private function save($coins)
    {
        $dataToSave = [];

        foreach ($coins as $coin) {
            $dataToSave[] = [
                'coin_id' => $coin['id'],
                'name' => $coin['name'],
                'symbol' => strtoupper($coin['symbol']),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        try {
            CoinGeckoCoin::upsert($dataToSave, 'coin_id', ['name', 'symbol', 'created_at', 'updated_at']);
        } catch (\Exception $e) {
            dump($e->getMessage());
        }
    }
}
