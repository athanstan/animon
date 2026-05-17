<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\UserAnimeLibraryStatus;
use App\Models\Anime;
use App\Models\User;
use App\Models\UserAnimeLibrary;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserAnimeLibrary>
 */
final class UserAnimeLibraryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'anime_id' => Anime::factory(),
            'status' => fake()->randomElement(UserAnimeLibraryStatus::cases()),
        ];
    }

    public function watching(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => UserAnimeLibraryStatus::Watching,
        ]);
    }

    public function planToWatch(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => UserAnimeLibraryStatus::PlanToWatch,
        ]);
    }
}
