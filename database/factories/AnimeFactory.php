<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\AnimeRating;
use App\Enums\AnimeSeason;
use App\Enums\AnimeStatus;
use App\Enums\AnimeType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Anime>
 */
final class AnimeFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->unique()->words(rand(2, 4), true);
        
        return [
            'mal_id' => fake()->unique()->numberBetween(1, 999999),
            'slug' => Str::slug($title),
            'title' => ucwords($title),
            'title_english' => fake()->optional()->words(rand(2, 4), true),
            'title_japanese' => fake()->optional()->word(),
            'image_url' => fake()->imageUrl(225, 318, 'anime'),
            'episodes' => fake()->numberBetween(12, 24),
            'status' => fake()->randomElement(AnimeStatus::cases()),
            'type' => fake()->randomElement(AnimeType::cases()),
            'season' => fake()->randomElement(AnimeSeason::cases()),
            'rating' => fake()->randomElement(AnimeRating::cases()),
            'score' => fake()->randomFloat(2, 5.0, 9.5),
            'synopsis' => fake()->paragraph(3),
            'year' => fake()->numberBetween(2000, 2026),
        ];
    }
}
