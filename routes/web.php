<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(['middleware' => ['auth']], function () {

    Route::prefix('service')->group(function () {
        Route::get('{type}/{number}', [App\Http\Controllers\Config\ServiceController::class, 'service']);
    });

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/binnacles', [App\Http\Controllers\Binnacle\BinnacleController::class, 'index'])->name('binnacles');

    Route::prefix('accounts')->group(function () {
        Route::get('/', [App\Http\Controllers\Config\AccountsController::class, 'index'])->name('accounts');
        Route::get('/columns', [App\Http\Controllers\Config\AccountsController::class, 'columns']);
        Route::get('/records', [App\Http\Controllers\Config\AccountsController::class, 'records']);
        Route::post('/', [App\Http\Controllers\Config\AccountsController::class, 'store']);
        Route::get('/record/{id}', [App\Http\Controllers\Config\AccountsController::class, 'record']);
        Route::delete('/{id}', [App\Http\Controllers\Config\AccountsController::class, 'destroy']);
    });

    Route::prefix('persons')->group(function () {
        Route::get('/', [App\Http\Controllers\Config\PersonsController::class, 'index'])->name('persons');
        Route::get('/columns', [App\Http\Controllers\Config\PersonsController::class, 'columns']);
        Route::get('/records', [App\Http\Controllers\Config\PersonsController::class, 'records']);
        Route::get('/record/{id}', [App\Http\Controllers\Config\PersonsController::class, 'record']);
        Route::get('/tables', [App\Http\Controllers\Config\PersonsController::class, 'tables']);
        Route::post('/', [App\Http\Controllers\Config\PersonsController::class, 'store']);
        Route::delete('/{id}', [App\Http\Controllers\Config\PersonsController::class, 'destroy']);
        Route::get('/enabled/{type}/{id}', [App\Http\Controllers\Config\PersonsController::class, 'enabled']);
    });

    Route::get('/costs', [App\Http\Controllers\Config\CostsController::class, 'index'])->name('costs');

});