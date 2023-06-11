<?php

namespace App\Http\Controllers;

use App\Models\CryptoCurrency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Inertia\Inertia;

class CryptoCurrencyController extends Controller
{

    public function index()
    {
        $cryptoCurrencyCount = CryptoCurrency::count();
        if ($cryptoCurrencyCount == 0) {
            Artisan::call('crypto:fetch 1 all');
        } else {
            Artisan::call('crypto:fetch 1 100');
        }

        $cryptoCurrencies = CryptoCurrency::orderBy('market_cap', 'desc')
            ->paginate(100);

        return Inertia::render('CryptoCurrency/Index', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'cryptoCurrencies' => $cryptoCurrencies
        ]);
    }


    public function search(Request $request)
    {
        $search = $request->query('query');

        $findCryptocurrencies = CryptoCurrency::where(function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('symbol', 'like', '%' . $search . '%');
        })
            ->orderBy('market_cap', 'desc')
            ->limit(10)
            ->get();

        //Return the results as JSON
        return json_encode($findCryptocurrencies);
    }

    public function show()
    {
        $slug = Route::current()->parameter('cryptoCurrency');
        $cryptoCurrency = CryptoCurrency::where('slug', $slug)->first();

        if (!$cryptoCurrency) {
            return redirect()->route('index');
        }

        if (!isset($cryptoCurrency->metadata)) {
            $exitCode = Artisan::call('cryptoMetadata:fetch ' . $cryptoCurrency->id);
            if ($exitCode === 0) {
                $cryptoCurrency = CryptoCurrency::where('id', $cryptoCurrency->id)->first();
                //need to fetch metadata
                $cryptoCurrency->metadata;
            } else {
                return redirect()->route('index');
            }
        }
        $symbol = $cryptoCurrency->coinGeckoCoins->coin_id;

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
