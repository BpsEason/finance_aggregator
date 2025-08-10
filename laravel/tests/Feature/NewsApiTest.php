<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\FinancialNews;
use Illuminate\Support\Carbon;

class NewsApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        # 建立一些假新聞資料
        FinancialNews::factory()->create([
            'title' => '新聞標題一',
            'summary' => '這是第一則新聞的摘要。',
            'url' => 'http://example.com/news/1',
            'published_at' => Carbon::now()->subHours(2),
        ]);
        FinancialNews::factory()->create([
            'title' => '新聞標題二',
            'summary' => '這是第二則新聞的摘要。',
            'url' => 'http://example.com/news/2',
            'published_at' => Carbon::now()->subHour(),
        ]);
        FinancialNews::factory()->create([
            'title' => '新聞標題三',
            'summary' => '這是第三則新聞的摘要。',
            'url' => 'http://example.com/news/3',
            'published_at' => Carbon::now(),
        ]);
    }

    /**
     * 測試取得最新金融新聞列表。
     */
    public function test_get_financial_news_list(): void
    {
        $response = $this->getJson('/api/financial-news');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['title', 'summary', 'url', 'published_at']
                     ],
                     'links',
                     'meta'
                 ])
                 ->assertJsonCount(3, 'data'); # 檢查是否有三筆新聞資料

        # 檢查新聞是否按 published_at 降序排列
        $responseData = collect($response->json('data'));
        $this->assertTrue($responseData->first()['title'] === '新聞標題三');
        $this->assertTrue($responseData->last()['title'] === '新聞標題一');
    }

    /**
     * 測試分頁功能。
     */
    public function test_get_financial_news_pagination(): void
    {
        $response = $this->getJson('/api/financial-news?limit=2&page=1');
        $response->assertStatus(200)
                 ->assertJsonCount(2, 'data')
                 ->assertJsonPath('meta.current_page', 1)
                 ->assertJsonPath('meta.per_page', 2)
                 ->assertJsonPath('meta.total', 3);
    }
}
