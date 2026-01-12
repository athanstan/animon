<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Visibility;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AnimeList>
 */
class AnimeListFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'user_id' => User::factory(),
            'visibility' => fake()->randomElement(Visibility::cases()),
        ];
    }
}
