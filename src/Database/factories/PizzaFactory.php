<?php

namespace WebId\Flan\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use WebId\Flan\Models\Pizza;

class PizzaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Pizza::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'price' => $this->faker->randomFloat(2, 10, 20),
            'active' => 1,
            'gss' => $this->faker->randomElement([1, 2, 3]),
            'created_at' => now(),
        ];
    }
}
