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
        public array $images,
        public ?int $episodes,
        public ?string $status,
        public ?string $type,
        public ?string $rating,
        public ?float $score,
        public ?int $scoredBy,
        public ?int $rank,
        public ?int $popularity,
        public ?int $members,
        public ?int $favorites,
        public ?string $synopsis,
        public ?string $background,
        public ?string $season,
        public ?int $year,
        public array $broadcast,
        public ?string $duration,
        public ?string $source,
        public ?string $airedString,
        public array $genres,
        public array $themes,
        public array $studios,
        public array $producers,
        public ?array $trailer,
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
            images: $data['images'] ?? [],
            episodes: $data['episodes'] ?? null,
            status: $data['status'] ?? null,
            type: $data['type'] ?? null,
            rating: $data['rating'] ?? null,
            score: $data['score'] ?? null,
            scoredBy: $data['scored_by'] ?? null,
            rank: $data['rank'] ?? null,
            popularity: $data['popularity'] ?? null,
            members: $data['members'] ?? null,
            favorites: $data['favorites'] ?? null,
            synopsis: $data['synopsis'] ?? null,
            background: $data['background'] ?? null,
            season: $data['season'] ?? null,
            year: $data['year'] ?? null,
            broadcast: $data['broadcast'] ?? [],
            duration: $data['duration'] ?? null,
            source: $data['source'] ?? null,
            airedString: $data['aired']['string'] ?? null,
            genres: $data['genres'] ?? [],
            themes: $data['themes'] ?? [],
            studios: $data['studios'] ?? [],
            producers: $data['producers'] ?? [],
            trailer: $data['trailer'] ?? null,
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

    /**
     * Get the large poster image URL
     */
    public function getLargeImageUrl(): ?string
    {
        return $this->images['jpg']['large_image_url'] ?? $this->imageUrl;
    }

    /**
     * Format the score for display
     */
    public function getFormattedScore(): string
    {
        return $this->score ? number_format($this->score, 2) : 'N/A';
    }

    /**
     * Get genre names as array
     */
    public function getGenreNames(): array
    {
        return array_map(fn(array $genre) => $genre['name'], $this->genres);
    }

    /**
     * Get studio names as array
     */
    public function getStudioNames(): array
    {
        return array_map(fn(array $studio) => $studio['name'], $this->studios);
    }

    /**
     * Get theme names as array
     */
    public function getThemeNames(): array
    {
        return array_map(fn(array $theme) => $theme['name'], $this->themes);
    }

    /**
     * Get YouTube trailer embed URL
     */
    public function getTrailerEmbedUrl(): ?string
    {
        return $this->trailer['embed_url'] ?? null;
    }

    /**
     * Format members count for display
     */
    public function getFormattedMembers(): string
    {
        if (!$this->members) {
            return '0';
        }

        if ($this->members >= 1000000) {
            return number_format($this->members / 1000000, 1) . 'M';
        }

        if ($this->members >= 1000) {
            return number_format($this->members / 1000, 1) . 'K';
        }

        return number_format($this->members);
    }

    /**
     * Format favorites count for display
     */
    public function getFormattedFavorites(): string
    {
        if (!$this->favorites) {
            return '0';
        }

        if ($this->favorites >= 1000000) {
            return number_format($this->favorites / 1000000, 1) . 'M';
        }

        if ($this->favorites >= 1000) {
            return number_format($this->favorites / 1000, 1) . 'K';
        }

        return number_format($this->favorites);
    }
}
