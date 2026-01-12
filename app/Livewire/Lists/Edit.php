<?php

declare(strict_types=1);

namespace App\Livewire\Lists;

use App\Models\Anime;
use App\Models\AnimeList;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

final class Edit extends Component
{
    public AnimeList $list;

    public string $title = '';

    public string $description = '';

    public string $visibility = '';

    /** @var array<int> */
    public array $selectedAnimeIds = [];

    public function mount(AnimeList $list): void
    {
        // Authorization check
        if ($list->user_id !== Auth::id()) {
            abort(403);
        }

        $this->list = $list;
        $this->title = $list->title;
        $this->description = $list->description ?? '';
        $this->visibility = $list->visibility->value;
        $this->selectedAnimeIds = $list->animes->pluck('id')->toArray();
    }

    /**
     * Get the initial anime data for Alpine.js hydration.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getInitialAnimeData(): array
    {
        return $this->list->animes
            ->map(fn(Anime $anime) => [
                'id' => $anime->id,
                'title' => $anime->title,
                'score' => $anime->score,
                'episodes' => $anime->episodes,
                'image_url' => $anime->image_url,
                'year' => $anime->year,
                'type' => $anime->type?->value,
            ])
            ->toArray();
    }

    /**
     * Search for anime - this is the only roundtrip for the picker.
     *
     * @return array<int, array<string, mixed>>
     */
    public function searchAnime(string $query, array $excludeIds = []): array
    {
        if (strlen($query) < 2) {
            return [];
        }

        return Anime::query()
            ->whereNotIn('id', $excludeIds)
            ->where(function ($q) use ($query) {
                $q->where('title', 'ilike', "%{$query}%")
                    ->orWhere('title_english', 'ilike', "%{$query}%")
                    ->orWhere('title_japanese', 'ilike', "%{$query}%");
            })
            ->orderBy('score', 'desc')
            ->limit(10)
            ->get(['id', 'title', 'score', 'episodes', 'image_url', 'year', 'type'])
            ->toArray();
    }

    public function save(): void
    {
        $validated = $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'visibility' => ['required', 'string', 'in:public,friends,private'],
            'selectedAnimeIds' => ['array'],
            'selectedAnimeIds.*' => ['integer', 'exists:animes,id'],
        ]);

        $this->list->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'visibility' => $validated['visibility'],
        ]);

        // Sync anime with order preserved
        $syncData = [];
        foreach ($validated['selectedAnimeIds'] as $order => $animeId) {
            $syncData[$animeId] = ['order' => $order];
        }
        $this->list->animes()->sync($syncData);

        $this->dispatch('list-saved');
    }

    public function render(): View
    {
        return view('livewire.lists.edit')->layout('components.layouts.guest', [
            'title' => 'Edit List - animon.gg',
        ]);
    }
}
