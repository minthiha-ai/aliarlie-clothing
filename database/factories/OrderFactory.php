<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'order_code' => 'ALIAR-'.str_pad((string) $this->faker->numberBetween(0, 999), 3, '0', STR_PAD_LEFT).'-'
                .str_pad((string) $this->faker->numberBetween(0, 999), 3, '0', STR_PAD_LEFT),
            'payment_id' => null,
            'payment_proof_photo' => null,
            'payment_method' => $this->faker->randomElement(['cod', 'online_payment']),
            'total_amount' => 0,
            'status' => $this->faker->randomElement(['pending', 'paid', 'shipped', 'cancelled']),
        ];
    }
}
