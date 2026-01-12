<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Anime;
use App\Models\AnimeList;
use App\Models\User;
use Illuminate\Database\Seeder;

class AnimeListSeeder extends Seeder
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

        foreach ($users as $user) {
            AnimeList::factory()
                ->count(3)
                ->create(['user_id' => $user->id])
                ->each(function (AnimeList $list) use ($animes) {
                    $selectedAnimes = $animes->random(min(5, $animes->count()));
                    foreach ($selectedAnimes as $index => $anime) {
                        $list->animes()->attach($anime->id, ['order' => $index]);
                    }
                });
        }
    }
}
