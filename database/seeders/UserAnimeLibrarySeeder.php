<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\UserAnimeLibraryStatus;
use App\Models\Anime;
use App\Models\User;
use App\Models\UserAnimeLibrary;
use Illuminate\Database\Seeder;

class UserAnimeLibrarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $animes = Anime::all();

        if ($users->isEmpty() || $animes->isEmpty()) {
            return;
        }

        $user = $users->first();

        foreach ($animes->take(5) as $anime) {
            UserAnimeLibrary::query()->updateOrCreate(
                [
                    'user_id' => $user->id,
                    'anime_id' => $anime->id,
                ],
                [
                    'status' => UserAnimeLibraryStatus::Watching,
                ]
            );
        }
    }
}
