{{--
    Create a new anime list.
--}}
<div class="max-w-4xl mx-auto px-4 py-8" x-data="animePicker()" x-cloak>
    <div class="mb-8">
        <h1 class="text-4xl font-black text-text-primary mb-2">
            Create New List
        </h1>
        <p class="text-text-secondary">
            Create a custom list to organize and showcase your favorite anime.
        </p>
    </div>

    <div class="bg-surface-secondary border-2 border-border-brutal rounded-lg p-6 shadow-brutal">
        <form wire:submit="save" class="space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                {{-- Left Column: Name and Visibility --}}
                <div class="space-y-6">
                    <flux:input wire:model="title" label="List Name" type="text" placeholder="e.g. Personal Top 10"
                        required autofocus />

                    <flux:select wire:model="visibility" label="Who can view?">
                        <flux:select.option value="public">Public</flux:select.option>
                        <flux:select.option value="friends">Friends Only</flux:select.option>
                        <flux:select.option value="private">Private</flux:select.option>
                    </flux:select>
                </div>

                {{-- Right Column: Description --}}
                <div>
                    <flux:textarea wire:model="description" label="Description" placeholder="Describe your list..."
                        rows="6" />
                </div>
            </div>

            {{-- Anime Search & Selection --}}
            <div class="space-y-4">
                <label class="block text-sm font-medium text-text-primary">
                    Add Anime to List
                </label>

                {{-- Search Input --}}
                <div class="relative">
                    <flux:icon.magnifying-glass
                        class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-text-secondary pointer-events-none z-10"
                        aria-hidden="true" />
                    <input type="search" x-model="query" x-on:input.debounce.300ms="search()"
                        x-on:focus="if (results.length > 0) showResults = true"
                        x-on:keydown.escape="showResults = false" placeholder="Search anime to add..."
                        class="w-full pl-9 pr-4 py-2 rounded-lg border-2 border-border-brutal bg-surface-primary text-sm font-medium placeholder:text-text-secondary/50 focus:outline-none focus:ring-2 focus:ring-kawaii-pink focus:border-kawaii-pink transition-all"
                        aria-label="Search anime to add" aria-autocomplete="list" aria-controls="anime-picker-results"
                        x-bind:aria-expanded="showResults.toString()" />

                    {{-- Loading Indicator --}}
                    <div x-show="isSearching" class="absolute right-3 top-1/2 -translate-y-1/2">
                        <div class="animate-spin h-4 w-4 border-2 border-kawaii-pink border-t-transparent rounded-full">
                        </div>
                    </div>

                    {{-- Results Dropdown --}}
                    <div x-show="showResults && results.length > 0" x-on:click.away="showResults = false"
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 -translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 -translate-y-1" id="anime-picker-results" role="listbox"
                        class="absolute top-full left-0 mt-2 w-full max-w-md rounded-lg border-2 border-border-brutal bg-surface-secondary shadow-lg overflow-hidden z-50">
                        <div class="max-h-[200px] overflow-y-auto scrollbar-kawaii">
                            <template x-for="anime in results" :key="anime.id">
                                <button type="button" x-on:click="addAnime(anime)"
                                    x-bind:disabled="isSelected(anime.id)" role="option"
                                    class="w-full flex items-center justify-between gap-3 px-3 py-2 hover:bg-surface-primary transition-colors border-b border-border-brutal/20 last:border-b-0 text-left disabled:opacity-50 disabled:cursor-not-allowed">
                                    <div class="flex-1 min-w-0">
                                        <span class="block font-semibold text-sm text-text-primary truncate"
                                            x-text="anime.title"></span>
                                        <span class="text-xs text-text-secondary mt-0.5">
                                            <template x-if="anime.score">
                                                <span class="inline-flex items-center gap-1">
                                                    <flux:icon.star class="w-3 h-3 text-kawaii-coral"
                                                        aria-hidden="true" />
                                                    <span x-text="anime.score?.toFixed(1)"></span>
                                                </span>
                                            </template>
                                            <template x-if="anime.score && anime.episodes"><span> · </span></template>
                                            <template x-if="anime.episodes">
                                                <span x-text="anime.episodes + ' eps'"></span>
                                            </template>
                                            <template x-if="(anime.score || anime.episodes) && anime.year"><span> ·
                                                </span></template>
                                            <template x-if="anime.year">
                                                <span x-text="anime.year"></span>
                                            </template>
                                        </span>
                                    </div>
                                    <span x-show="isSelected(anime.id)"
                                        class="shrink-0 text-xs font-medium text-kawaii-mint">
                                        Added
                                    </span>
                                    <flux:icon.plus x-show="!isSelected(anime.id)"
                                        class="shrink-0 w-4 h-4 text-kawaii-pink" aria-hidden="true" />
                                </button>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Selected Anime List --}}
                <div x-show="selectedAnime.length > 0" class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-text-secondary">
                            <span x-text="selectedAnime.length"></span> anime selected
                        </span>
                        <button type="button" x-on:click="clearAll()"
                            class="text-xs font-medium text-kawaii-coral hover:underline">
                            Clear all
                        </button>
                    </div>

                    <ul
                        class="border-2 border-border-brutal rounded-lg divide-y divide-border-brutal/30 bg-surface-primary overflow-hidden">
                        <template x-for="(anime, index) in selectedAnime" :key="anime.id">
                            <li class="flex items-center gap-3 px-3 py-2.5">
                                {{-- Order Number --}}
                                <span
                                    class="shrink-0 w-6 h-6 flex items-center justify-center rounded-full bg-kawaii-pink/10 text-kawaii-pink text-xs font-bold"
                                    x-text="index + 1"></span>

                                {{-- Anime Image --}}
                                <div
                                    class="shrink-0 w-10 h-14 rounded border border-border-brutal/30 overflow-hidden bg-surface-secondary">
                                    <template x-if="anime.image_url">
                                        <img x-bind:src="anime.image_url" x-bind:alt="anime.title"
                                            class="w-full h-full object-cover" loading="lazy" />
                                    </template>
                                    <template x-if="!anime.image_url">
                                        <div
                                            class="w-full h-full flex items-center justify-center bg-gradient-to-br from-kawaii-pink/10 to-kawaii-lavender/10">
                                            <flux:icon.photo class="w-4 h-4 text-text-secondary/30"
                                                aria-hidden="true" />
                                        </div>
                                    </template>
                                </div>

                                {{-- Anime Info --}}
                                <div class="flex-1 min-w-0">
                                    <span class="block text-sm font-semibold text-text-primary truncate"
                                        x-text="anime.title"></span>
                                    <span class="block text-xs text-text-secondary mt-0.5">
                                        <template x-if="anime.type">
                                            <span x-text="anime.type"></span>
                                        </template>
                                        <template x-if="anime.type && anime.episodes">
                                            <span> · </span>
                                        </template>
                                        <template x-if="anime.episodes">
                                            <span x-text="anime.episodes + ' eps'"></span>
                                        </template>
                                        <template x-if="(anime.type || anime.episodes) && anime.year">
                                            <span> · </span>
                                        </template>
                                        <template x-if="anime.year">
                                            <span x-text="anime.year"></span>
                                        </template>
                                        <template x-if="(anime.type || anime.episodes || anime.year) && anime.score">
                                            <span> · </span>
                                        </template>
                                        <template x-if="anime.score">
                                            <span class="inline-flex items-center gap-0.5">
                                                <flux:icon.star class="w-3 h-3 text-kawaii-coral"
                                                    aria-hidden="true" />
                                                <span x-text="anime.score?.toFixed(1)"></span>
                                            </span>
                                        </template>
                                    </span>
                                </div>

                                {{-- Remove Button --}}
                                <button type="button" x-on:click="removeAnime(anime.id)"
                                    class="shrink-0 p-1.5 rounded hover:bg-kawaii-coral/10 text-text-secondary hover:text-kawaii-coral transition-colors"
                                    aria-label="Remove anime">
                                    <flux:icon.x-mark class="w-4 h-4" aria-hidden="true" />
                                </button>
                            </li>
                        </template>
                    </ul>
                </div>

                {{-- Hidden inputs to sync with Livewire --}}
                <template x-for="anime in selectedAnime" :key="'input-' + anime.id">
                    <input type="hidden" name="selectedAnimeIds[]" x-bind:value="anime.id" />
                </template>
            </div>

            <div class="flex items-center gap-4 pt-4">
                <flux:button variant="primary" type="submit" class="w-full sm:w-auto">
                    Create List
                </flux:button>

                <flux:button variant="ghost" href="{{ route('dashboard') }}" wire:navigate>
                    Cancel
                </flux:button>
            </div>
        </form>
    </div>
</div>

@script
    <script>
        Alpine.data('animePicker', () => ({
            query: '',
            results: [],
            selectedAnime: [],
            showResults: false,
            isSearching: false,
            isDirty: false,

            init() {
                // Track form field changes
                this.$el.querySelectorAll('input, textarea, select').forEach(el => {
                    el.addEventListener('input', () => this.isDirty = true);
                    el.addEventListener('change', () => this.isDirty = true);
                });

                // Warn user before leaving with unsaved changes
                window.addEventListener('beforeunload', (e) => {
                    if (this.isDirty) {
                        e.preventDefault();
                    }
                });

                // Handle Livewire navigation
                document.addEventListener('livewire:navigating', (e) => {
                    if (this.isDirty && !confirm(
                            'You have unsaved changes. Are you sure you want to leave?')) {
                        e.preventDefault();
                    }
                });
            },

            async search() {
                if (this.query.length < 2) {
                    this.results = [];
                    this.showResults = false;
                    return;
                }

                this.isSearching = true;

                try {
                    this.results = await $wire.searchAnime(this.query);
                    this.showResults = true;
                } finally {
                    this.isSearching = false;
                }
            },

            addAnime(anime) {
                if (this.isSelected(anime.id)) return;

                this.selectedAnime.push({
                    id: anime.id,
                    title: anime.title,
                    score: anime.score,
                    episodes: anime.episodes,
                    image_url: anime.image_url,
                    year: anime.year,
                    type: anime.type
                });

                // Sync with Livewire property
                $wire.selectedAnimeIds = this.selectedAnime.map(a => a.id);
                this.isDirty = true;
            },

            removeAnime(id) {
                this.selectedAnime = this.selectedAnime.filter(a => a.id !== id);
                $wire.selectedAnimeIds = this.selectedAnime.map(a => a.id);
                this.isDirty = true;
            },

            clearAll() {
                this.selectedAnime = [];
                $wire.selectedAnimeIds = [];
                this.isDirty = true;
            },

            isSelected(id) {
                return this.selectedAnime.some(a => a.id === id);
            }
        }));
    </script>
@endscript
