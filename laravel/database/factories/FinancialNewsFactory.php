<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\FinancialNews;
use Illuminate\Support\Str;

class FinancialNewsFactory extends Factory
{
    protected $model = FinancialNews::class;

    public function definition()
    {
        $title = $this->faker->sentence(5);
        return [
            'title' => $title,
            'summary' => $this->faker->paragraph(3),
            'url' => $this->faker->unique()->url,
            'published_at' => $this->faker->dateTimeBetween('-1 week', 'now'),
        ];
    }
}
