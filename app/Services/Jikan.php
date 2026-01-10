<?php

declare(strict_types=1);

namespace App\Services;

use App\Collections\Jikan\AnimeCollection;
use App\Collections\Jikan\EpisodeCollection;
use App\DataTransferObjects\Jikan\AnimeDTO;
use App\Enums\JikanAnimeType;
use App\Enums\JikanRating;
use App\Enums\TopAnimeFilter;
use App\Exceptions\Integrations\Jikan\JikanException;
use App\Exceptions\Integrations\Jikan\NotFoundException;
use App\Exceptions\Integrations\Jikan\RateLimitException;
use App\Exceptions\Integrations\Jikan\UnauthorizedException;
use App\Http\Integrations\Jikan\JikanConnector;
use App\Http\Integrations\Jikan\Requests\GetAnime;
use App\Http\Integrations\Jikan\Requests\GetAnimeEpisodes;
use App\Http\Integrations\Jikan\Requests\GetAnimeList;
use App\Http\Integrations\Jikan\Requests\GetTopAnime;
use App\Interfaces\JikanInterface;

final readonly class Jikan implements JikanInterface
{
    public function __construct(
        private JikanConnector $connector
    ) {}

    public function getAnimeById(
        int $id
    ): AnimeDTO {
        $request = new GetAnime($id);
        return $this->connector->send($request)->dtoOrFail();
    }

    public function getAnime(
        int $page = 1,
        int $limit = 25,
        bool $sfw = true
    ): AnimeCollection {
        $request = new GetAnimeList(
            page: $page,
            limit: $limit,
            sfw: $sfw
        );

        $response = $this->connector->send($request);

        return $request->createDtoFromResponse($response);
    }

    public function getTopAnime(
        int $page = 1,
        int $limit = 25,
        ?JikanAnimeType $type = null,
        ?TopAnimeFilter $filter = null,
        ?JikanRating $rating = null,
        bool $sfw = true
    ): AnimeCollection {
        $request = new GetTopAnime(
            page: $page,
            limit: $limit,
            type: $type,
            filter: $filter,
            rating: $rating,
            sfw: $sfw
        );

        $response = $this->connector->send($request);

        return $request->createDtoFromResponse($response);
    }

    public function getAnimeEpisodes(
        int $animeId,
        int $page = 1
    ): EpisodeCollection {
        $request = new GetAnimeEpisodes(
            animeId: $animeId,
            page: $page
        );

        $response = $this->connector->send($request);

        return $request->createDtoFromResponse($response);
    }

    public function getAnimeEpisodesPagination(
        int $animeId
    ): array {
        $request = new GetAnimeEpisodes(
            animeId: $animeId,
            page: 1
        );

        $response = $this->connector->send($request);

        return $request->getPagination($response);
    }
}
