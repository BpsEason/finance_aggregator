<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\ExchangeRate;

class ExchangeRateApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        ExchangeRate::factory()->create(['currency' => 'USD/TWD', 'rate' => 30.500]);
        ExchangeRate::factory()->create(['currency' => 'JPY/TWD', 'rate' => 0.2050]);
    }

    /**
     * 測試取得所有匯率列表。
     */
    public function test_get_exchange_rates_list(): void
    {
        $response = $this->getJson('/api/exchange-rates');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     '*' => ['currency', 'rate', 'updated_at']
                 ])
                 ->assertJsonCount(2);
    }
}
