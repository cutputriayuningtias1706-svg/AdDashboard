<?php

namespace Database\Factories;

use App\Models\Campaign;
use App\Models\AdSpendingRecord;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdSpendingRecordFactory extends Factory
{
    protected $model = AdSpendingRecord::class;

    public function definition(): array
    {
        return [
            'campaign_id' => Campaign::factory(),
            'record_date' => $this->faker->dateTimeBetween('-30 days', 'today'),
            'impressions' => $this->faker->numberBetween(1000, 100000),
            'clicks' => $this->faker->numberBetween(10, 5000),
            'conversions' => $this->faker->numberBetween(1, 500),
            'spend' => $this->faker->randomFloat(2, 10, 1000),
        ];
    }
}
