<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TestController extends Controller
{
    /**
     * 手動觸發 Scrapy 爬蟲執行。
     */
    public function triggerCrawl(Request $request)
    {
        $type = $request->input('type'); # 例如: "stocks", "exchange-rates", "news"

        if (!in_array($type, ['stocks', 'exchange-rates', 'news'])) {
            return response()->json(['error' => 'Invalid crawl type.'], 400);
        }

        $scrapyHost = env('SCRAPY_HOST', 'localhost');
        $scrapyPort = env('SCRAPY_PORT', '6800');
        $url = "http://{$scrapyHost}:{$scrapyPort}/schedule.json";

        # 根據 type 決定要啟動的 spider 名稱
        $spiderName = '';
        if ($type === 'stocks') {
            $spiderName = 'tw_stock_spider'; # 假設爬台股的 spider 叫 'tw_stock_spider'
        } elseif ($type === 'exchange-rates') {
            $spiderName = 'exchange_rate_spider';
        } elseif ($type === 'news') {
            $spiderName = 'financial_news_spider';
        }

        try {
            $response = Http::post($url, [
                'project' => 'scrapy_project', # Scrapy 專案名稱
                'spider' => $spiderName,
            ]);

            if ($response->successful()) {
                $jobId = $response->json('jobid');
                Log::info("Scrapy crawl triggered for type: {$type}, job ID: {$jobId}");
                return response()->json(['status' => 'started', 'job_id' => $jobId]);
            } else {
                Log::error("Failed to trigger Scrapy crawl for type: {$type}. Response: " . $response->body());
                return response()->json(['status' => 'failed', 'message' => 'Failed to trigger crawl.', 'details' => $response->json()], $response->status());
            }
        } catch (\Exception $e) {
            Log::error("Exception when triggering Scrapy crawl: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'An error occurred.'], 500);
        }
    }
}
