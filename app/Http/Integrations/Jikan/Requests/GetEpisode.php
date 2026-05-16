<?php

declare(strict_types=1);

namespace App\Http\Integrations\Jikan\Requests;

use App\DataTransferObjects\Jikan\EpisodeDTO;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

final class GetEpisode extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $animeMalId,
        private readonly int $episodeNumber,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/anime/{$this->animeMalId}/episodes/{$this->episodeNumber}";
    }

    public function createDtoFromResponse(Response $response): EpisodeDTO
    {
        /** @var array<string, mixed> $data */
        $data = $response->json('data') ?? [];

        return EpisodeDTO::fromResponse($data);
    }
}
