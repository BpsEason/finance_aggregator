<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StockController;
use App\Http\Controllers\Api\ExchangeRateController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\TestController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('stocks', StockController::class)->only(['index', 'show']);
Route::apiResource('exchange-rates', ExchangeRateController::class)->only(['index']);
Route::apiResource('financial-news', NewsController::class)->only(['index']);
Route::post('tests/trigger-crawl', [TestController::class, 'triggerCrawl']);
