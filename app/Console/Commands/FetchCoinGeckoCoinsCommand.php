<?php

namespace App\Console\Commands;

use App\Models\CoinGeckoCoin;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

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
                //saving to database
                $this->save($coinGeckoCoins);
            } else {
                $this->error('Failed to fetch coingecko data. API responded with status code: ' . $res->getStatusCode());
                return;
            }
        } catch (\Exception $e) {
            $this->error('An error occurred while fetching coingecko data: ' . $e->getMessage());
            return 1;
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
            CoinGeckoCoin::upsert($dataToSave, 'coin_id');
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
