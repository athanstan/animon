{{--
    Anime search component with dropdown results.
--}}
<div class="relative w-full max-w-2xl" x-data="{
    open: @entangle('showResults'),
    searchInput: null,
    init() {
        this.searchInput = this.$el.querySelector('input[type=search]');

        // Global keyboard shortcut: press '/' to focus search
        window.addEventListener('keydown', (e) => {
            if (e.key === '/' && !['INPUT', 'TEXTAREA'].includes(document.activeElement.tagName)) {
                e.preventDefault();
                this.searchInput.focus();
            }
        });
    }
}" x-on:click.away="open = false; $wire.closeResults()"
    x-cloak>
    <!-- Search Input -->
    <div class="relative">
        <flux:icon.magnifying-glass
            class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-text-secondary pointer-events-none z-10"
            aria-hidden="true" />
        <input type="search" wire:model.live.debounce.300ms="query"
            x-on:focus="if ($wire.query.length >= 2) { open = true; $wire.showResults = true; }"
            placeholder="Search anime... (Press / to focus)"
            class="w-full pl-9 pr-10 py-2 rounded-lg border-2 border-border-brutal bg-surface-primary text-sm font-medium placeholder:text-text-secondary/50 focus:outline-none focus:ring-2 focus:ring-kawaii-pink focus:border-kawaii-pink transition-all"
            aria-label="Search anime" aria-autocomplete="list" aria-controls="search-results"
            x-bind:aria-expanded="open.toString()" />

        <!-- Keyboard Shortcut Hint -->
        <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" x-show="!$wire.query">
            <kbd
                class="px-1.5 py-0.5 text-xs font-semibold text-text-secondary/60 bg-surface-secondary border border-border-brutal rounded">
                /
            </kbd>
        </div>

        <!-- Loading Indicator -->
        <div wire:loading wire:target="query" class="absolute right-3 top-1/2 -translate-y-1/2">
            <div class="animate-spin h-4 w-4 border-2 border-kawaii-pink border-t-transparent rounded-full"></div>
        </div>
    </div>

    <!-- Results Dropdown -->
    @if (count($this->results) > 0)
        <div x-show="open" x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-1" id="search-results" role="listbox"
            class="absolute top-full left-0 mt-2 w-[400px] rounded-lg border-2 border-border-brutal bg-surface-secondary shadow-lg overflow-hidden z-50">
            <!-- Fixed Height Scrollable Container -->
            <div class="max-h-[450px] overflow-y-auto scrollbar-kawaii">
                @foreach ($this->results as $anime)
                    <a href="{{ route('anime.show', $anime['slug']) }}" wire:key="anime-{{ $anime['slug'] }}"
                        role="option"
                        class="flex items-center gap-3 px-3 py-2.5 hover:bg-surface-primary transition-colors border-b border-border-brutal/20 last:border-b-0 group">
                        <!-- Anime Image -->
                        <div
                            class="shrink-0 w-12 h-16 rounded border border-border-brutal/30 overflow-hidden bg-surface-primary">
                            @if ($anime['image_url'])
                                <img src="{{ $anime['image_url'] }}" alt="{{ $anime['title'] }}"
                                    class="w-full h-full object-cover" loading="lazy" />
                            @else
                                <div
                                    class="w-full h-full flex items-center justify-center bg-gradient-to-br from-kawaii-pink/10 to-kawaii-lavender/10">
                                    <flux:icon.photo class="w-5 h-5 text-text-secondary/30" aria-hidden="true" />
                                </div>
                            @endif
                        </div>

                        <!-- Anime Info -->
                        <div class="flex-1 min-w-0">
                            <h3
                                class="font-semibold text-sm text-text-primary truncate group-hover:text-kawaii-pink transition-colors">
                                {{ $anime['title'] }}
                            </h3>
                            <div class="flex items-center gap-2.5 mt-0.5 text-xs text-text-secondary">
                                <!-- Rating -->
                                @if ($anime['score'])
                                    <div class="flex items-center gap-1">
                                        <flux:icon.star class="w-3 h-3 text-kawaii-coral" aria-hidden="true" />
                                        <span class="font-medium">{{ number_format($anime['score'], 1) }}</span>
                                    </div>
                                @endif

                                <!-- Episodes -->
                                @if ($anime['episodes'])
                                    <div class="flex items-center gap-1">
                                        <flux:icon.film class="w-3 h-3" aria-hidden="true" />
                                        <span>{{ $anime['episodes'] }} eps</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Arrow Indicator -->
                        <div class="shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
                            <flux:icon.arrow-right class="w-4 h-4 text-kawaii-pink" aria-hidden="true" />
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
