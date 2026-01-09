<?php

declare(strict_types=1);

namespace App\Collections\Jikan;

use App\DataTransferObjects\Jikan\EpisodeDTO;
use Illuminate\Support\Collection;

/**
 * Collection of EpisodeDTO objects.
 *
 * @extends Collection<int, EpisodeDTO>
 */
final class EpisodeCollection extends Collection
{
    /**
     * Get only filler episodes.
     */
    public function fillers(): self
    {
        return $this->filter(fn (EpisodeDTO $episode): bool => $episode->filler);
    }

    /**
     * Get only recap episodes.
     */
    public function recaps(): self
    {
        return $this->filter(fn (EpisodeDTO $episode): bool => $episode->recap);
    }

    /**
     * Get only canon episodes (non-filler, non-recap).
     */
    public function canon(): self
    {
        return $this->filter(fn (EpisodeDTO $episode): bool => ! $episode->filler && ! $episode->recap);
    }

    /**
     * Get episodes that have aired.
     */
    public function aired(): self
    {
        return $this->filter(fn (EpisodeDTO $episode): bool => $episode->hasAired());
    }

    /**
     * Get episodes that haven't aired yet.
     */
    public function upcoming(): self
    {
        return $this->filter(fn (EpisodeDTO $episode): bool => ! $episode->hasAired());
    }

    /**
     * Get the next episode to air (first upcoming with a scheduled date).
     */
    public function nextAiring(): ?EpisodeDTO
    {
        return $this
            ->filter(fn (EpisodeDTO $episode): bool => $episode->aired !== null && $episode->aired->isFuture())
            ->sortBy(fn (EpisodeDTO $episode) => $episode->aired)
            ->first();
    }

    /**
     * Get the latest aired episode.
     */
    public function latestAired(): ?EpisodeDTO
    {
        return $this
            ->aired()
            ->sortByDesc(fn (EpisodeDTO $episode) => $episode->aired)
            ->first();
    }
}
