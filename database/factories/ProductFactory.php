<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;

class ProductFactory extends Factory
{
	protected $model = Product::class;

	public function definition()
	{
		return [
			'name' => $this->faker->words(3, true),
			'description' => $this->faker->sentence(),
			'price' => $this->faker->numberBetween(100, 10000),
			'is_active' => true,
		];
	}
}

