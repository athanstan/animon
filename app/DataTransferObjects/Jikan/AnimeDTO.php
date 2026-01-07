<?php

declare(strict_types=1);

namespace App\DataTransferObjects\Jikan;

final readonly class AnimeDTO
{
    public function __construct(
        public int $malId,
        public string $title,
        public ?string $titleEnglish,
        public ?string $titleJapanese,
        public ?string $imageUrl,
        public ?int $episodes,
        public ?string $status,       // AnimeStatus
        public ?string $type,         // AnimeType
        public ?string $rating,       // AnimeRating
        public ?float $score,
        public ?string $synopsis,
        public ?string $season,       // AnimeSeason
        public ?int $year,
    ) {}

    /**
     * Map API response to DTO
     */
    public static function fromResponse(array $data): self
    {
        return new self(
            malId: $data['mal_id'],
            title: $data['title'],
            titleEnglish: $data['title_english'] ?? null,
            titleJapanese: $data['title_japanese'] ?? null,
            imageUrl: $data['images']['jpg']['image_url'] ?? null,
            episodes: $data['episodes'] ?? null,
            status: $data['status'] ?? null,
            type: $data['type'] ?? null,
            rating: $data['rating'] ?? null,
            score: $data['score'] ?? null,
            synopsis: $data['synopsis'] ?? null,
            season: $data['season'] ?? null,
            year: $data['year'] ?? null,
        );
    }

    /**
     * Convert to array for database insertion
     */
    public function toDatabase(): array
    {
        return [
            'mal_id' => $this->malId,
            'title' => $this->title,
            'title_english' => $this->titleEnglish,
            'title_japanese' => $this->titleJapanese,
            'image_url' => $this->imageUrl,
            'episodes' => $this->episodes,
            'status' => $this->status,
            'type' => $this->type,
            'rating' => $this->rating,
            'score' => $this->score,
            'synopsis' => $this->synopsis,
            'season' => $this->season,
            'year' => $this->year,
        ];
    }
}
