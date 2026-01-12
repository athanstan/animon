<?php

declare(strict_types=1);

namespace App\Livewire\Lists;

use App\Enums\Visibility;
use App\Models\Anime;
use App\Models\AnimeList;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

final class Create extends Component
{
    public string $title = '';

    public string $description = '';

    public string $visibility = 'private';

    public string $searchQuery = '';

    /** @var array<int> */
    public array $selectedAnimeIds = [];

    public function mount(): void
    {
        $this->visibility = Visibility::PRIVATE->value;
    }

    /**
     * Search for anime - this is the only roundtrip for the picker.
     *
     * @return array<int, array<string, mixed>>
     */
    public function searchAnime(string $query): array
    {
        if (strlen($query) < 2) {
            return [];
        }

        return Anime::query()
            ->where('title', 'ilike', "%{$query}%")
            ->orWhere('title_english', 'ilike', "%{$query}%")
            ->orWhere('title_japanese', 'ilike', "%{$query}%")
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

        $list = AnimeList::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'visibility' => $validated['visibility'],
            'user_id' => Auth::id(),
        ]);

        // Sync anime with order preserved
        if (! empty($validated['selectedAnimeIds'])) {
            $syncData = [];
            foreach ($validated['selectedAnimeIds'] as $order => $animeId) {
                $syncData[$animeId] = ['order' => $order];
            }
            $list->animes()->sync($syncData);
        }

        session()->flash('list_created', $list->title);

        $this->redirect(route('lists.edit', $list->slug), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.lists.create')->layout('components.layouts.guest', [
            'title' => 'Create New List - animon.gg',
        ]);
    }
}
