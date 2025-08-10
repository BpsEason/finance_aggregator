<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FinancialNews;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * 取得最新金融新聞列表。
     */
    public function index(Request $request)
    {
        $limit = $request->query('limit', 10);
        $page = $request->query('page', 1);

        $news = FinancialNews::orderBy('published_at', 'desc')
                            ->paginate($limit, ['*'], 'page', $page);

        return response()->json($news);
    }

    # 依據專案目標，這裡通常不開放 create, store, update, destroy 等操作
    # 資料由爬蟲負責寫入
}
