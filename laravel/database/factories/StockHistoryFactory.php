<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\StockHistory;
use App\Models\Stock;
use Illuminate\Support\Carbon;

class StockHistoryFactory extends Factory
{
    protected $model = StockHistory::class;

    public function definition()
    {
        return [
            'stock_id' => Stock::factory(),
            'date' => $this->faker->unique()->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'price' => $this->faker->randomFloat(2, 50, 1000),
        ];
    }
}
