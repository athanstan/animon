<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Anime;
use App\Models\Episode;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Episode>
 */
final class EpisodeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'anime_id' => Anime::factory(),
            'number' => fake()->numberBetween(1, 9999),
            'title' => fake()->sentence(3),
            'title_japanese' => fake()->optional()->word(),
            'title_romanji' => fake()->optional()->words(3, true),
            'aired' => fake()->optional()->dateTimeBetween('-2 years', 'now'),
            'score' => fake()->optional()->randomFloat(2, 1.0, 5.0),
            'filler' => fake()->boolean(10),
            'recap' => fake()->boolean(5),
            'url' => fake()->optional()->url(),
            'forum_url' => fake()->optional()->url(),
            'duration' => fake()->optional()->randomElement(['24m', '23 min', null]),
            'synopsis' => fake()->optional()->sentence(8),
        ];
    }

    public function filler(): static
    {
        return $this->state(fn (array $attributes) => ['filler' => true]);
    }

    public function recap(): static
    {
        return $this->state(fn (array $attributes) => ['recap' => true]);
    }

    public function upcoming(): static
    {
        return $this->state(fn (array $attributes) => [
            'aired' => fake()->dateTimeBetween('+1 day', '+1 year'),
        ]);
    }
}
