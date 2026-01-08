{{--
    Anime card component for displaying anime in grids.
--}}
@props(['title', 'score', 'episodes', 'image', 'color' => 'bg-kawaii-pink', 'rank' => null, 'status' => null])

<article class="anime-card rounded-xl overflow-hidden group cursor-pointer bg-surface-secondary">
    <!-- Image Container -->
    <div class="relative aspect-[3/4] overflow-hidden">
        <img src="{{ $image }}" alt="{{ $title }}"
            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110" loading="lazy" />

        <!-- Hover Overlay -->
        <div
            class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-3">
            <flux:text size="sm" class="text-white font-bold">+ Add to List</flux:text>
        </div>

        <!-- Rank Badge (if provided) -->
        @if ($rank)
            <div
                class="absolute top-2 left-2 w-8 h-8 rounded-lg flex items-center justify-center font-black text-sm border-2 border-border-brutal {{ $color }}">
                #{{ $rank }}
            </div>
        @endif

        <!-- Status Badge (if provided) -->
        @if ($status)
            <div
                class="absolute top-2 left-2 px-2 py-1 rounded-lg font-bold text-xs border-2 border-border-brutal {{ $color }}">
                {{ $status }}
            </div>
        @endif
    </div>

    <!-- Info -->
    <div class="p-3">
        <flux:heading size="sm" class="leading-tight truncate mb-1">{{ $title }}</flux:heading>
        <div class="flex items-center justify-between text-xs text-text-secondary">
            <flux:text size="xs" class="flex items-center gap-1">
                <span class="star-filled">★</span>
                {{ $score }}
            </flux:text>
            <flux:text size="xs">{{ $episodes }} eps</flux:text>
        </div>
    </div>
</article>
