<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Stock;

class StockFactory extends Factory
{
    protected $model = Stock::class;

    public function definition()
    {
        return [
            'symbol' => $this->faker->unique()->lexify('????.TW'),
            'name' => $this->faker->company . '股份有限公司',
            'price' => $this->faker->randomFloat(2, 50, 1000),
            'change' => $this->faker->randomFloat(2, -10, 10),
            'updated_at' => $this->faker->dateTimeBetween('-1 day', 'now'),
        ];
    }
}
