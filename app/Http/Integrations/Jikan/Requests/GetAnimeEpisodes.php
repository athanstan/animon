<?php

declare(strict_types=1);

namespace App\Http\Integrations\Jikan\Requests;

use App\Collections\Jikan\EpisodeCollection;
use App\DataTransferObjects\Jikan\EpisodeDTO;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

final class GetAnimeEpisodes extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $animeId,
        private readonly int $page = 1,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/anime/{$this->animeId}/episodes";
    }

    protected function defaultQuery(): array
    {
        return [
            'page' => $this->page,
        ];
    }

    /**
     * Map the response to a Collection of EpisodeDTO objects.
     */
    public function createDtoFromResponse(Response $response): EpisodeCollection
    {
        $episodes = $response->collect('data')
            ->map(fn(array $episode): EpisodeDTO => EpisodeDTO::fromResponse($episode));

        return EpisodeCollection::make($episodes)->ensure(EpisodeDTO::class);
    }

    /**
     * Get pagination info from response.
     */
    public function getPagination(Response $response): array
    {
        return $response->json('pagination') ?? [
            'last_visible_page' => 1,
            'has_next_page' => false,
        ];
    }
}
