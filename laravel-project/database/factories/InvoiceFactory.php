<?php

namespace Database\Factories;

use App\Models\AdAccount;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        $amount = $this->faker->randomFloat(2, 500, 10000);
        $tax = $amount * 0.10; // 10% tax

        return [
            'ad_account_id' => AdAccount::factory(),
            'invoice_number' => 'INV/' . date('Y') . '/' . date('m') . '/' . str_pad($this->faker->unique()->numberBetween(1, 999), 4, '0', STR_PAD_LEFT),
            'amount' => $amount,
            'tax' => $tax,
            'total_amount' => $amount + $tax,
            'period_start' => $this->faker->dateTimeBetween('-60 days', '-30 days'),
            'period_end' => $this->faker->dateTimeBetween('-29 days', 'now'),
            'status' => $this->faker->randomElement(['paid', 'pending', 'overdue']),
            'notes' => $this->faker->sentence(),
            'due_date' => $this->faker->dateTimeBetween('now', '+30 days'),
        ];
    }
}
