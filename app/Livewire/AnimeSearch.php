<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Anime;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;

final class AnimeSearch extends Component
{
    public string $query = '';

    public bool $showResults = false;

    public function updatedQuery(): void
    {
        $this->showResults = strlen($this->query) >= 2;
    }

    #[Computed]
    public function results(): array
    {
        if (strlen($this->query) < 2) {
            return [];
        }

        return Anime::query()
            ->where('title', 'ilike', "%{$this->query}%")
            ->orWhere('title_english', 'ilike', "%{$this->query}%")
            ->orWhere('title_japanese', 'ilike', "%{$this->query}%")
            ->orderBy('score', 'desc')
            ->limit(10)
            ->get(['slug', 'title', 'image_url', 'score', 'episodes'])
            ->toArray();
    }

    public function closeResults(): void
    {
        $this->showResults = false;
    }

    public function render(): View
    {
        return view('livewire.anime-search');
    }
}
