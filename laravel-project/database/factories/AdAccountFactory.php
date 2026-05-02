<?php

namespace Database\Factories;

use App\Models\AdAccount;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdAccountFactory extends Factory
{
    protected $model = AdAccount::class;

    public function definition(): array
    {
        $platforms = ['google', 'meta', 'tiktok'];
        $platform = $this->faker->randomElement($platforms);

        return [
            'platform' => $platform,
            'account_name' => $this->faker->company() . ' - ' . ucfirst($platform) . ' Ads',
            'account_id' => strtoupper($platform) . '-' . $this->faker->uuid(),
            'status' => $this->faker->randomElement(['active', 'active', 'active', 'paused']),
            'balance' => $this->faker->randomFloat(2, 1000, 50000),
            'description' => $this->faker->sentence(),
        ];
    }
}
