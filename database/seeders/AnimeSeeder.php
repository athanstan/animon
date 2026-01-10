<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Anime;
use Illuminate\Database\Seeder;

final class AnimeSeeder extends Seeder
{
    public function run(): void
    {
        Anime::factory()
            ->count(50)
            ->create();
    }
}
