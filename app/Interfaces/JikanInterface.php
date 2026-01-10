<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\Collections\Jikan\AnimeCollection;
use App\Collections\Jikan\EpisodeCollection;
use App\DataTransferObjects\Jikan\AnimeDTO;
use App\Enums\JikanAnimeType;
use App\Enums\JikanRating;
use App\Enums\TopAnimeFilter;

interface JikanInterface
{
    public function getAnimeById(int $id): AnimeDTO;

    public function getAnime(
        int $page = 1,
        int $limit = 25,
        bool $sfw = true
    ): AnimeCollection;

    public function getTopAnime(
        int $page = 1,
        int $limit = 25,
        ?JikanAnimeType $type = null,
        ?TopAnimeFilter $filter = null,
        ?JikanRating $rating = null,
        bool $sfw = true
    ): AnimeCollection;

    public function getAnimeEpisodes(
        int $animeId,
        int $page = 1
    ): EpisodeCollection;

    public function getAnimeEpisodesPagination(
        int $animeId
    ): array;
}
