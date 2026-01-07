<?php

declare(strict_types=1);

namespace App\Http\Integrations\Jikan\Requests;

use App\Collections\Jikan\AnimeCollection;
use App\DataTransferObjects\Jikan\AnimeDTO;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

final class GetAnimeList extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $page = 1,
        private readonly int $limit = 25,
        private readonly bool $sfw = true,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/anime';
    }

    protected function defaultQuery(): array
    {
        return [
            'page' => $this->page,
            'limit' => $this->limit,
            'sfw' => $this->sfw ? 'true' : 'false',
            'order_by' => 'mal_id',
            'sort' => 'asc',
        ];
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
