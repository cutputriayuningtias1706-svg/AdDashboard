<?php

namespace Database\Factories;

use App\Models\AdAccount;
use App\Models\Campaign;
use Illuminate\Database\Eloquent\Factories\Factory;

class CampaignFactory extends Factory
{
    protected $model = Campaign::class;

    public function definition(): array
    {
        $campaignNames = [
            'Spring Sale 2024',
            'Brand Awareness',
            'Product Launch',
            'Holiday Special',
            'Retargeting Campaign',
            'New User Acquisition',
            'Summer Promo',
            'Flash Sale',
            'Loyalty Program',
            'Holiday Deals',
        ];

        return [
            'ad_account_id' => AdAccount::factory(),
            'campaign_name' => $this->faker->randomElement($campaignNames) . ' ' . $this->faker->numberBetween(1, 10),
            'campaign_id' => 'CMP-' . $this->faker->uuid(),
            'budget_daily' => $this->faker->randomFloat(2, 50, 500),
            'budget_total' => $this->faker->randomFloat(2, 1000, 10000),
            'status' => $this->faker->randomElement(['active', 'active', 'active', 'paused']),
            'start_date' => $this->faker->dateTimeBetween('-30 days', 'today'),
            'end_date' => $this->faker->dateTimeBetween('+30 days', '+90 days'),
        ];
    }
}
