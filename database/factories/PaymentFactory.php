<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'payment_logo' => 'payments/'.$this->faker->uuid.'.png',
            'payment_type' => $this->faker->randomElement(['cod', 'online_payment']),
            'name' => $this->faker->company(),
            'number' => $this->faker->numerify('#### #### #### ####'),
            'status' => $this->faker->boolean(85) ? 'active' : 'inactive',
        ];
    }
}
