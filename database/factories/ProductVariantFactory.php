<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Stock;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    public function configure(): static
    {
        return $this->afterCreating(function ($variant): void {
            Stock::factory()->create([
                'product_variant_id' => $variant->id,
            ]);
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $price = $this->faker->randomFloat(2, 10, 200);

        return [
            'product_id' => Product::factory(),
            'sku' => $this->faker->unique()->bothify('SKU-####'),
            'size' => $this->faker->randomElement(['XS', 'S', 'M', 'L', 'XL']),
            'color' => $this->faker->safeColorName(),
            'price' => $price,
            'discount_price' => $this->faker->boolean(25)
                ? $this->faker->randomFloat(2, 5, $price)
                : null,
            'is_active' => $this->faker->boolean(90),
        ];
    }
}
