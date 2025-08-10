<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ExchangeRate;

class ExchangeRateFactory extends Factory
{
    protected $model = ExchangeRate::class;

    public function definition()
    {
        $currencies = [
            'USD/TWD', 'JPY/TWD', 'EUR/TWD', 'GBP/TWD', 'AUD/TWD'
        ];
        return [
            'currency' => $this->faker->unique()->randomElement($currencies),
            'rate' => $this->faker->randomFloat(4, 0.001, 100),
            'updated_at' => $this->faker->dateTimeBetween('-1 day', 'now'),
        ];
    }
}
