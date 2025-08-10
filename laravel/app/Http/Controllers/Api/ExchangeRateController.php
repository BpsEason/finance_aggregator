<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExchangeRate;
use Illuminate\Http\Request;

class ExchangeRateController extends Controller
{
    /**
     * 取得所有匯率列表。
     */
    public function index()
    {
        return response()->json(ExchangeRate::all());
    }

    # 依據專案目標，這裡通常不開放 create, store, update, destroy 等操作
    # 資料由爬蟲負責寫入
}
