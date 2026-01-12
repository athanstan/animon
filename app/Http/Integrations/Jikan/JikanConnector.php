<?php

namespace App\Http\Integrations\Jikan;

use App\Exceptions\Integrations\Jikan\JikanException;
use App\Exceptions\Integrations\Jikan\NotFoundException;
use App\Exceptions\Integrations\Jikan\RateLimitException;
use App\Exceptions\Integrations\Jikan\UnauthorizedException;
use Illuminate\Support\Facades\Cache;
use Saloon\Http\Connector;
use Saloon\Http\Response;
use Saloon\RateLimitPlugin\Limit;
use Saloon\RateLimitPlugin\Traits\HasRateLimits;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;
use Throwable;
use Saloon\RateLimitPlugin\Contracts\RateLimitStore;
use Saloon\RateLimitPlugin\Stores\LaravelCacheStore;

class JikanConnector extends Connector
{
    use AcceptsJson;
    use AlwaysThrowOnErrors;
    use HasRateLimits;

    protected function resolveLimits(): array
    {
        return [
            Limit::allow(3)->everySeconds(1),
            Limit::allow(60)->everyMinute(),
        ];
    }

    protected function resolveRateLimitStore(): RateLimitStore
    {
        return new LaravelCacheStore(Cache::store(config('cache.default')));
    }

    /**
     * The Base URL of the API
     */
    public function resolveBaseUrl(): string
    {
        return 'https://api.jikan.moe/v4';
    }

    /**
     * Default headers for every request
     */
    protected function defaultHeaders(): array
    {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * Default HTTP client options
     */
    protected function defaultConfig(): array
    {
        return [];
    }

    /**
     * Map Saloon exceptions to custom Jikan exceptions
     */
    public function getRequestException(Response $response, ?Throwable $senderException): ?Throwable
    {
        return match ($response->status()) {
            404 => new NotFoundException('Resource not found', $response->status(), $senderException),
            403 => new UnauthorizedException('Unauthorized access', $response->status(), $senderException),
            429 => new RateLimitException('Rate limit exceeded', $response->status(), $senderException),
            default => new JikanException(
                message: 'Jikan API request failed: ' . $response->body(),
                code: $response->status(),
                previous: $senderException
            ),
        };
    }
}
