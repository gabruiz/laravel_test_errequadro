<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GifproviderController;
use App\Http\Controllers\KeywordController;

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

Route::get('/add-gifprovider',[GifproviderController::class, 'addGifprovider']);
Route::get('/add-keyword',[GifproviderController::class, 'addKeyword']);
Route::get('/providers', [GifproviderController::class, 'getProviders']);
Route::get('/provider/{identifier}/stats', [GifproviderController::class, 'getStatsById']);
Route::get('/gifs/{keyword}', [GifproviderController::class, 'getGifs']);
Route::get('/gifs/{keyword}/stats', [GifproviderController::class, 'getGifsStats']);
Route::post('/provider/{identifier}', [GifproviderController::class, 'setProviderId']);
