<?php

declare(strict_types=1);

namespace App\DataTransferObjects\Jikan;

use Carbon\Carbon;

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
    ) {}

    /**
     * Map API response to DTO.
     */
    public static function fromResponse(array $data): self
    {
        return new self(
            malId: $data['mal_id'],
            url: $data['url'] ?? null,
            title: $data['title'],
            titleJapanese: $data['title_japanese'] ?? null,
            titleRomanji: $data['title_romanji'] ?? null,
            aired: isset($data['aired']) ? Carbon::parse($data['aired']) : null,
            score: $data['score'] ?? null,
            filler: $data['filler'] ?? false,
            recap: $data['recap'] ?? false,
            forumUrl: $data['forum_url'] ?? null,
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
     * @return array{anime_id: int, number: int, title: string, title_japanese: ?string, title_romanji: ?string, aired: ?string, score: ?float, filler: bool, recap: bool, url: ?string, forum_url: ?string}
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
        ];
    }
}
