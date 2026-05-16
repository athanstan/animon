<?php

declare(strict_types=1);

namespace App\Actions;

use App\Collections\Jikan\EpisodeCollection;
use App\Models\Episode;

final class SyncEpisodesFromJikan
{
    /**
     * Upsert episodes from Jikan API data into the local database.
     */
    public function execute(int $animeId, EpisodeCollection $episodes): void
    {
        if ($episodes->isEmpty()) {
            return;
        }

        $records = $episodes->map(fn ($episode) => $episode->toDatabase($animeId))->all();

        Episode::upsert(
            $records,
            uniqueBy: ['anime_id', 'number'],
            update: [
                'title',
                'title_japanese',
                'title_romanji',
                'aired',
                'score',
                'filler',
                'recap',
                'url',
                'forum_url',
                'duration',
                'synopsis',
            ],
        );
    }
}
