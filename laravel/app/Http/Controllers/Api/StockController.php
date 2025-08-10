<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * 取得所有股票即時列表。
     */
    public function index()
    {
        return response()->json(Stock::all());
    }

    /**
     * 取得單一股票詳細資料。
     */
    public function show(string $symbol)
    {
        $stock = Stock::where('symbol', $symbol)->with('history')->firstOrFail();
        return response()->json($stock);
    }

    # 依據專案目標，這裡通常不開放 create, store, update, destroy 等操作
    # 資料由爬蟲負責寫入
}
