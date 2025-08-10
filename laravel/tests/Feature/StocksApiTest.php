<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Stock;
use App\Models\StockHistory;

class StocksApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        # 為測試建立一些假資料
        $stock = Stock::factory()->create([
            'symbol' => '2330.TW',
            'name' => '台積電',
            'price' => 620.50,
            'change' => -3.00,
        ]);
        StockHistory::factory()->create([
            'stock_id' => $stock->id,
            'date' => now()->subDays(1)->toDateString(),
            'price' => 623.50,
        ]);
        StockHistory::factory()->create([
            'stock_id' => $stock->id,
            'date' => now()->toDateString(),
            'price' => 620.50,
        ]);

        Stock::factory()->create([
            'symbol' => '0050.TW',
            'name' => '元大台灣50',
            'price' => 130.00,
            'change' => 1.20,
        ]);
    }

    /**
     * 測試取得所有股票列表。
     */
    public function test_get_stocks_list(): void
    {
        $response = $this->getJson('/api/stocks');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     '*' => ['symbol', 'name', 'price', 'change', 'updated_at']
                 ])
                 ->assertJsonCount(2); # 檢查是否有兩筆股票資料
    }

    /**
     * 測試取得單一股票詳細資料。
     */
    public function test_get_single_stock_detail(): void
    {
        $response = $this->getJson('/api/stocks/2330.TW');

        $response->assertStatus(200)
                 ->assertJson([
                     'symbol' => '2330.TW',
                     'name' => '台積電',
                 ])
                 ->assertJsonStructure([
                     'symbol',
                     'name',
                     'price',
                     'change',
                     'updated_at',
                     'history' => [
                         '*' => ['date', 'price']
                     ]
                 ]);
        $this->assertCount(2, $response->json('history')); # 檢查是否有歷史資料
    }

    /**
     * 測試取得不存在的股票。
     */
    public function test_get_non_existent_stock(): void
    {
        $response = $this->getJson('/api/stocks/9999.TW');
        $response->assertStatus(404);
    }
}
