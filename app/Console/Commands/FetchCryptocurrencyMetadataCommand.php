<?php

namespace App\Console\Commands;

use App\Models\CryptoCurrencyMetadata;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class FetchCryptocurrencyMetadataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cryptoMetadata:fetch {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetching cryptocurrency metadata by id from coinmarketcap.com';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $baseUrl = env('COIN_MARKET_CAP_URL', 'b54bcf4d-1bca-4e8e-9a24-22ff2c3d462c');
        $id = $this->argument('id');
        try {
            $client = new Client();
            $url = $baseUrl . '/v2/cryptocurrency/info';
            $parameters = [
                'id' => $id,
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


            if ($res->getStatusCode() === 200) {
                $cryptocurrency = json_decode($res->getBody()->getContents(), true);

                $responseCurrenciesData = $cryptocurrency['data'];

                //saving to database
                $this->save($responseCurrenciesData);
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
            try {
                CryptoCurrencyMetadata::updateOrCreate(
                    [
                        'currency_id' => $cryptoCurrency['id']
                    ],
                    [
                        'currency_id' => $cryptoCurrency['id'],
                        'name' => $cryptoCurrency['name'],
                        'symbol' => $cryptoCurrency['symbol'],
                        'slug' => $cryptoCurrency['slug'],
                        'description' => $cryptoCurrency['description'],
                        'date_added' => Carbon::parse($cryptoCurrency['date_added'])->toDateTimeString(),
                        'date_launched' => Carbon::parse($cryptoCurrency['date_launched'])->toDateTimeString(),
                        'tags' => json_encode($cryptoCurrency['tags']),
                        'category' => $cryptoCurrency['category'],
                        'logo_url' => $cryptoCurrency['logo'],
                        'urls' => json_encode($cryptoCurrency['urls']),
                    ]
                );
            } catch (\Exception $e) {
                dump($e->getMessage());
            }
        }
    }
}
