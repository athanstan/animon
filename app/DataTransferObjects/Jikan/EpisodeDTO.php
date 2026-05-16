<?php

declare(strict_types=1);

namespace App\DataTransferObjects\Jikan;

use Carbon\Carbon;
use Throwable;

final readonly class EpisodeDTO
{
    public function __construct(
        public int $malId,
        public ?string $url,
        public string $title,
        public ?string $titleJapanese,
        public ?string $titleRomanji,
        public ?Carbon $aired,
        public ?float $score,
        public bool $filler,
        public bool $recap,
        public ?string $forumUrl,
        public ?string $duration,
        public ?string $synopsis,
    ) {}

    /**
     * Map API response to DTO.
     *
     * @param  array<string, mixed>  $data
     */
    public static function fromResponse(array $data): self
    {
        return new self(
            malId: (int) $data['mal_id'],
            url: $data['url'] ?? null,
            title: isset($data['title']) ? (string) $data['title'] : '',
            titleJapanese: $data['title_japanese'] ?? null,
            titleRomanji: $data['title_romanji'] ?? null,
            aired: self::parseAired($data['aired'] ?? null),
            score: isset($data['score']) ? (float) $data['score'] : null,
            filler: (bool) ($data['filler'] ?? false),
            recap: (bool) ($data['recap'] ?? false),
            forumUrl: $data['forum_url'] ?? null,
            duration: self::normalizeOptionalString($data['duration'] ?? null),
            synopsis: isset($data['synopsis']) ? (is_string($data['synopsis']) ? $data['synopsis'] : null) : null,
        );
    }

    /**
     * Format the score for display (1.00 - 5.00 scale).
     */
    public function getFormattedScore(): string
    {
        return $this->score ? number_format($this->score, 2) : 'N/A';
    }

    /**
     * Get formatted aired date.
     */
    public function getFormattedAiredDate(): string
    {
        return $this->aired?->format('M d, Y') ?? 'TBA';
    }

    /**
     * Check if this episode has aired.
     */
    public function hasAired(): bool
    {
        return $this->aired !== null && $this->aired->isPast();
    }

    /**
     * Get display title (prefer romanji, fallback to main title).
     */
    public function getDisplayTitle(): string
    {
        return $this->titleRomanji ?? $this->title;
    }

    /**
     * Convert to array for database insertion.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(int $animeId): array
    {
        return [
            'anime_id' => $animeId,
            'number' => $this->malId,
            'title' => $this->title,
            'title_japanese' => $this->titleJapanese,
            'title_romanji' => $this->titleRomanji,
            'aired' => $this->aired?->toDateTimeString(),
            'score' => $this->score,
            'filler' => $this->filler,
            'recap' => $this->recap,
            'url' => $this->url,
            'forum_url' => $this->forumUrl,
            'duration' => $this->duration,
            'synopsis' => $this->synopsis,
        ];
    }

    private static function parseAired(mixed $aired): ?Carbon
    {
        if ($aired === null || $aired === '') {
            return null;
        }

        try {
            return Carbon::parse($aired);
        } catch (Throwable) {
            return null;
        }
    }

    private static function normalizeOptionalString(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        return is_string($value) ? $value : (string) $value;
    }
}
