<?php

namespace App\Http\Integrations\Jikan\Requests;

use App\DataTransferObjects\Jikan\AnimeDTO;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

final class GetAnime extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $id,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/anime/{$this->id}";
    }

    public function createDtoFromResponse(Response $response): AnimeDTO
    {
        return AnimeDTO::fromResponse($response->json('data'));
    }
}
