{{--
    Stats bar showing members, favorites, and rank.
--}}
@props(['members', 'favorites', 'rank' => null])

<div class="flex items-center justify-center gap-4 text-sm mx-auto">
    <!-- Members -->
    <flux:tooltip content="Members tracking this anime">
        <div class="flex items-center gap-1.5 cursor-default">
            <svg class="w-4 h-4 text-kawaii-sky" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                <path
                    d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
            </svg>
            <span class="font-bold">{{ $members }}</span>
        </div>
    </flux:tooltip>

    <!-- Favorites -->
    <flux:tooltip content="Users who favorited this anime">
        <div class="flex items-center gap-1.5 cursor-default">
            <svg class="w-4 h-4 text-kawaii-coral" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                <path fill-rule="evenodd"
                    d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                    clip-rule="evenodd" />
            </svg>
            <span class="font-bold">{{ $favorites }}</span>
        </div>
    </flux:tooltip>

    <!-- Rank -->
    @if ($rank)
        <flux:tooltip content="MAL Ranking">
            <div class="flex items-center gap-1.5 cursor-default">
                <svg class="w-4 h-4 text-kawaii-peach" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zM12 2a1 1 0 01.967.744L14.146 7.2 17.5 9.134a1 1 0 010 1.732l-3.354 1.935-1.18 4.455a1 1 0 01-1.933 0L9.854 12.8 6.5 10.866a1 1 0 010-1.732l3.354-1.935 1.18-4.455A1 1 0 0112 2z"
                        clip-rule="evenodd" />
                </svg>
                <span class="font-bold">#{{ $rank }}</span>
            </div>
        </flux:tooltip>
    @endif
</div>
