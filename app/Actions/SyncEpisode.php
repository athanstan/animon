<?php

declare(strict_types=1);

namespace App\Actions;

use App\Interfaces\JikanInterface;
use App\Models\Anime;
use App\Models\Episode;

final readonly class SyncEpisode
{
    public function __construct(
        private JikanInterface $jikan,
    ) {}

    /**
     * Persist full episode detail from Jikan when missing. Skips HTTP when synopsis already stored.
     */
    public function execute(Anime $anime, int $episodeNumber): Episode
    {
        $existing = Episode::query()
            ->where('anime_id', $anime->id)
            ->where('number', $episodeNumber)
            ->first();

        if ($existing !== null && $existing->synopsis !== null) {
            return $existing;
        }

        $dto = $this->jikan->getEpisode($anime->mal_id, $episodeNumber);

        $attributes = $dto->toDatabase($anime->id);

        return Episode::updateOrCreate(
            [
                'anime_id' => $anime->id,
                'number' => $attributes['number'],
            ],
            $attributes
        );
    }
}
