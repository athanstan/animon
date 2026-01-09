<?php

declare(strict_types=1);

namespace App\Http\Integrations\Jikan\Requests;

use App\Collections\Jikan\AnimeCollection;
use App\DataTransferObjects\Jikan\AnimeDTO;
use App\Enums\JikanAnimeType;
use App\Enums\JikanRating;
use App\Enums\TopAnimeFilter;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

final class GetTopAnime extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $page = 1,
        private readonly int $limit = 25,
        private readonly ?JikanAnimeType $type = null,
        private readonly ?TopAnimeFilter $filter = null,
        private readonly ?JikanRating $rating = null,
        private readonly bool $sfw = true,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/top/anime';
    }

    protected function defaultQuery(): array
    {
        $query = [
            'page' => $this->page,
            'limit' => $this->limit,
            'sfw' => $this->sfw ? 'true' : 'false',
        ];

        if ($this->type !== null) {
            $query['type'] = $this->type->value;
        }

        if ($this->filter !== null) {
            $query['filter'] = $this->filter->value;
        }

        if ($this->rating !== null) {
            $query['rating'] = $this->rating->value;
        }

        return $query;
    }

    /**
     * Map the response to a Collection of AnimeDTO objects
     */
    public function createDtoFromResponse(Response $response): AnimeCollection
    {
        $animeList = $response->collect('data')
            ->map(fn(array $anime): AnimeDTO => AnimeDTO::fromResponse($anime));

        return AnimeCollection::make($animeList)->ensure(AnimeDTO::class);
    }
}
