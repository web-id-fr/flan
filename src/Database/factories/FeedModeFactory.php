<?php

namespace WebId\Flan\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use WebId\Flan\Models\FeedMode;

class FeedModeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FeedMode::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
        ];
    }
}
