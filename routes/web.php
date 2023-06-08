<?php

use App\Http\Controllers\CryptoCurrencyController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });
// Route::get('/', function () {
//     return Inertia::render('CryptoCurrency/Index', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });
Route::get('/', [CryptoCurrencyController::class, 'index'])->name('index');

Route::prefix('currency')->group(function () {
    Route::get('{cryptoCurrency}', [CryptoCurrencyController::class, 'show'])->name('cryptoCurrency.show');
});

// Handle pagination
// Route::get('/cryptocurrencies/page/{page}', [CryptocurrencyController::class, 'index'])->name('cryptocurrencies.pagination');

// Handle search
// Route::get('/cryptocurrencies/search', [CryptocurrencyController::class, 'search'])->name('cryptocurrencies.search');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
