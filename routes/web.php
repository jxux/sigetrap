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

    Route::get('/persons', [App\Http\Controllers\Config\PersonsController::class, 'index'])->name('persons');
    Route::get('/persons/columns', [App\Http\Controllers\Config\PersonsController::class, 'columns']);
    Route::get('/persons/records', [App\Http\Controllers\Config\PersonsController::class, 'records']);
    Route::get('/persons/tables', [App\Http\Controllers\Config\PersonsController::class, 'tables']);
    Route::post('/persons', [App\Http\Controllers\Config\PersonsController::class, 'store']);
	Route::delete('/persons/{person}', [App\Http\Controllers\Config\PersonsController::class, 'destroy']);
    Route::get('/persons/enabled/{type}/{person}', [App\Http\Controllers\Config\PersonsController::class, 'enabled']);

    Route::get('/accounts', [App\Http\Controllers\Config\AccountsController::class, 'index'])->name('accounts');
    Route::get('/costs', [App\Http\Controllers\Config\CostsController::class, 'index'])->name('costs');

});