<?php

namespace Database\Factories;

use App\Models\AdAccount;
use App\Models\TopupBalance;
use Illuminate\Database\Eloquent\Factories\Factory;

class TopupBalanceFactory extends Factory
{
    protected $model = TopupBalance::class;

    public function definition(): array
    {
        $amount = $this->faker->randomFloat(2, 500, 10000);
        $bonus = $amount >= 5000 ? $amount * 0.10 : ($amount >= 2000 ? $amount * 0.05 : 0);

        return [
            'ad_account_id' => AdAccount::factory(),
            'amount' => $amount,
            'bonus' => $bonus,
            'total_amount' => $amount + $bonus,
            'status' => $this->faker->randomElement(['completed', 'completed', 'completed', 'pending']),
            'payment_method' => $this->faker->randomElement(['Bank Transfer', 'Credit Card', 'E-Wallet', 'PayPal']),
            'transaction_id' => 'TXN-' . $this->faker->uuid(),
            'topup_date' => $this->faker->dateTimeBetween('-60 days', 'now'),
        ];
    }
}
