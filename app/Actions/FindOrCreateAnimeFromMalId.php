<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Anime;
use Illuminate\Support\Str;

final class FindOrCreateAnimeFromMalId
{
    public function execute(int $malId, string $title): Anime
    {
        return Anime::firstOrCreate(
            ['mal_id' => $malId],
            [
                'slug' => Str::slug($title),
                'title' => $title,
            ]
        );
    }
}
