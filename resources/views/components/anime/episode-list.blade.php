{{--
    Episode list component - displays a grid of episodes with filler/recap badges.
--}}

@props(['episodes', 'limit' => 24])

@php
    $displayEpisodes = $episodes->take($limit);
    $hasMore = $episodes->count() > $limit;
@endphp

<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
    @foreach ($displayEpisodes as $episode)
        <div
            class="brutal-card rounded-xl p-4 bg-surface-secondary hover:bg-surface-secondary/80 transition-colors group cursor-pointer"
            title="{{ $episode->title }}"
        >
            <!-- Episode Number -->
            <div class="flex items-center justify-between mb-2">
                <span class="font-bold text-lg">{{ $episode->malId }}</span>

                <!-- Badges -->
                <div class="flex gap-1">
                    @if ($episode->filler)
                        <span
                            class="px-1.5 py-0.5 rounded text-[10px] font-bold uppercase bg-amber-400/20 text-amber-600 border border-amber-400/40"
                            title="Filler Episode"
                        >
                            F
                        </span>
                    @endif

                    @if ($episode->recap)
                        <span
                            class="px-1.5 py-0.5 rounded text-[10px] font-bold uppercase bg-blue-400/20 text-blue-600 border border-blue-400/40"
                            title="Recap Episode"
                        >
                            R
                        </span>
                    @endif
                </div>
            </div>

            <!-- Title -->
            <flux:text class="text-xs text-text-secondary line-clamp-2 mb-2 min-h-[2.5rem]">
                {{ $episode->getDisplayTitle() }}
            </flux:text>

            <!-- Aired Date & Score -->
            <div class="flex items-center justify-between text-[10px] text-text-secondary/70">
                <span>{{ $episode->getFormattedAiredDate() }}</span>

                @if ($episode->score)
                    <span class="flex items-center gap-0.5">
                        <flux:icon.star class="w-3 h-3 text-kawaii-coral" />
                        {{ $episode->getFormattedScore() }}
                    </span>
                @endif
            </div>
        </div>
    @endforeach
</div>

@if ($hasMore)
    <div class="mt-6 text-center">
        <flux:button variant="ghost" class="btn-kawaii">
            View all {{ $episodes->count() }} episodes
        </flux:button>
    </div>
@endif
