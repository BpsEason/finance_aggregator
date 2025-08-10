<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TriggerCrawlApiTest extends TestCase
{
    /**
     * 測試觸發有效的股票爬蟲。
     */
    public function test_trigger_valid_stocks_crawl(): void
    {
        # 模擬對 Scrapyweb 的 HTTP POST 請求成功
        Http::fake([
            'scrapy:6800/schedule.json' => Http::response(['status' => 'ok', 'jobid' => 'mock_job_id_stocks'], 200),
        ]);

        $response = $this->postJson('/api/tests/trigger-crawl', ['type' => 'stocks']);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'started',
                     'job_id' => 'mock_job_id_stocks',
                 ]);

        Http::assertSent(function ($request) {
            return $request->url() == 'http://scrapy:6800/schedule.json' &&
                   $request->method() == 'POST' &&
                   $request['project'] == 'scrapy_project' &&
                   $request['spider'] == 'tw_stock_spider';
        });
    }

    /**
     * 測試觸發有效的匯率爬蟲。
     */
    public function test_trigger_valid_exchange_rates_crawl(): void
    {
        Http::fake([
            'scrapy:6800/schedule.json' => Http::response(['status' => 'ok', 'jobid' => 'mock_job_id_rates'], 200),
        ]);

        $response = $this->postJson('/api/tests/trigger-crawl', ['type' => 'exchange-rates']);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'started',
                     'job_id' => 'mock_job_id_rates',
                 ]);

        Http::assertSent(function ($request) {
            return $request->url() == 'http://scrapy:6800/schedule.json' &&
                   $request['spider'] == 'exchange_rate_spider';
        });
    }

    /**
     * 測試觸發有效的金融新聞爬蟲。
     */
    public function test_trigger_valid_news_crawl(): void
    {
        Http::fake([
            'scrapy:6800/schedule.json' => Http::response(['status' => 'ok', 'jobid' => 'mock_job_id_news'], 200),
        ]);

        $response = $this->postJson('/api/tests/trigger-crawl', ['type' => 'news']);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'started',
                     'job_id' => 'mock_job_id_news',
                 ]);

        Http::assertSent(function ($request) {
            return $request->url() == 'http://scrapy:6800/schedule.json' &&
                   $request['spider'] == 'financial_news_spider';
        });
    }

    /**
     * 測試觸發無效的爬蟲類型。
     */
    public function test_trigger_invalid_crawl_type(): void
    {
        $response = $this->postJson('/api/tests/trigger-crawl', ['type' => 'invalid_type']);

        $response->assertStatus(400)
                 ->assertJson(['error' => 'Invalid crawl type.']);

        Http::assertNotSent(function ($request) {
            return $request->url() == 'http://scrapy:6800/schedule.json';
        });
    }

    /**
     * 測試 Scrapyweb 服務失敗的情況。
     */
    public function test_trigger_crawl_scrapyweb_failure(): void
    {
        Http::fake([
            'scrapy:6800/schedule.json' => Http::response(['error' => 'Scrapyweb internal error'], 500),
        ]);

        $response = $this->postJson('/api/tests/trigger-crawl', ['type' => 'stocks']);

        $response->assertStatus(500)
                 ->assertJson(['status' => 'failed', 'message' => 'Failed to trigger crawl.']);
    }

    /**
     * 測試網路連線錯誤到 Scrapyweb 的情況。
     */
    public function test_trigger_crawl_network_error(): void
    {
        Http::fake([
            'scrapy:6800/schedule.json' => Http::timeout(1)->networkError(), # 模擬網路錯誤
        ]);

        $response = $this->postJson('/api/tests/trigger-crawl', ['type' => 'stocks']);

        $response->assertStatus(500)
                 ->assertJson(['status' => 'error', 'message' => 'An error occurred.']);
    }
}
