{{--
    Episodes list with infinite loading - loads from newest to oldest.
--}}
<div>
    <!-- Loading Placeholder (shown during lazy load) -->
    <div wire:loading.delay class="space-y-4">
        <div class="flex items-center justify-center py-12">
            <div class="text-center">
                <div
                    class="animate-spin h-12 w-12 border-4 border-kawaii-pink border-t-transparent rounded-full mx-auto mb-4">
                </div>
                <p class="text-sm text-text-secondary font-medium">Loading episodes...</p>
            </div>
        </div>

        <!-- Skeleton Loading States -->
        <div class="space-y-2" role="status" aria-label="Loading episodes">
            @for ($i = 0; $i < 5; $i++)
                <div
                    class="flex items-center gap-4 px-4 py-3 rounded-lg border-2 border-border-brutal/20 bg-surface-secondary animate-pulse">
                    <div class="shrink-0 w-12 h-6 bg-text-secondary/20 rounded"></div>
                    <div class="flex-1 space-y-2">
                        <div class="h-4 bg-text-secondary/20 rounded w-3/4"></div>
                        <div class="h-3 bg-text-secondary/10 rounded w-1/2"></div>
                    </div>
                </div>
            @endfor
        </div>
    </div>

    <!-- Actual Content (shown after load) -->
    <div wire:loading.remove>
        <!-- Episodes Stats -->
        @if ($this->episodes->isNotEmpty())
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-4 text-sm text-text-secondary">
                    <span>{{ $this->episodes->count() }} episodes loaded</span>

                    @if ($this->episodes->fillers()->count() > 0)
                        <span class="flex items-center gap-1">
                            <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                            {{ $this->episodes->fillers()->count() }} filler
                        </span>
                    @endif

                    @if ($this->episodes->recaps()->count() > 0)
                        <span class="flex items-center gap-1">
                            <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                            {{ $this->episodes->recaps()->count() }} recap
                        </span>
                    @endif
                </div>

                @if (count($loadedPages) > 0)
                    <span class="text-xs text-text-secondary/60">
                        {{ count($loadedPages) }} {{ Str::plural('page', count($loadedPages)) }} loaded
                    </span>
                @endif
            </div>
        @endif

        <!-- Minimal Episode List -->
        <div class="space-y-2" wire:loading.class="opacity-60">
            @foreach ($this->episodes as $episode)
                <a href="{{ $episode->url }}" target="_blank" rel="noopener noreferrer"
                    wire:key="episode-{{ $episode->malId }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-lg border-2 border-border-brutal/20 bg-surface-secondary hover:bg-surface-primary hover:border-border-brutal/40 transition-all group">
                    <!-- Episode Number -->
                    <div class="shrink-0 w-12 text-center">
                        <span
                            class="text-lg font-black text-text-primary group-hover:text-kawaii-pink transition-colors">
                            {{ $episode->malId }}
                        </span>
                    </div>

                    <!-- Episode Info -->
                    <div class="flex-1 min-w-0">
                        <h4
                            class="font-semibold text-sm text-text-primary truncate group-hover:text-kawaii-coral transition-colors">
                            {{ $episode->getDisplayTitle() }}
                        </h4>
                        <div class="flex items-center gap-3 mt-0.5 text-xs text-text-secondary">
                            <span>{{ $episode->getFormattedAiredDate() }}</span>

                            @if ($episode->score)
                                <span class="flex items-center gap-1">
                                    <flux:icon.star class="w-3 h-3 text-kawaii-coral" aria-hidden="true" />
                                    {{ $episode->getFormattedScore() }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Badges -->
                    <div class="shrink-0 flex gap-2">
                        @if ($episode->filler)
                            <span
                                class="px-2 py-1 rounded text-xs font-bold bg-amber-400/20 text-amber-600 border border-amber-400/40"
                                title="Filler Episode">
                                Filler
                            </span>
                        @endif

                        @if ($episode->recap)
                            <span
                                class="px-2 py-1 rounded text-xs font-bold bg-blue-400/20 text-blue-600 border border-blue-400/40"
                                title="Recap Episode">
                                Recap
                            </span>
                        @endif

                        @if (!$episode->hasAired())
                            <span
                                class="px-2 py-1 rounded text-xs font-bold bg-kawaii-lavender/30 text-text-secondary border border-border-brutal/20"
                                title="Not yet aired">
                                Upcoming
                            </span>
                        @endif
                    </div>

                    <!-- Arrow Icon -->
                    <div class="shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
                        <flux:icon.arrow-top-right-on-square class="w-4 h-4 text-kawaii-pink" aria-hidden="true" />
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Load More Button -->
        @if ($this->hasMorePages)
            <div class="mt-6 text-center">
                <flux:button wire:click="loadMore" wire:loading.attr="disabled" variant="ghost"
                    class="btn-kawaii bg-kawaii-pink px-6 py-3">
                    <span wire:loading.remove wire:target="loadMore">
                        Load More Episodes
                    </span>
                    <span wire:loading wire:target="loadMore" class="flex items-center gap-2">
                        <div
                            class="animate-spin h-4 w-4 border-2 border-text-primary border-t-transparent rounded-full">
                        </div>
                        Loading...
                    </span>
                </flux:button>
            </div>
        @else
            @if ($this->episodes->isNotEmpty())
                <div class="mt-6 text-center">
                    <p class="text-sm text-text-secondary/60">
                        ✨ All episodes loaded
                    </p>
                </div>
            @endif
        @endif

        <!-- Empty State -->
        @if ($this->episodes->isEmpty())
            <div class="text-center py-12">
                <flux:icon.film class="w-16 h-16 mx-auto mb-4 text-text-secondary/30" aria-hidden="true" />
                <p class="text-text-secondary">No episodes available yet</p>
            </div>
        @endif
    </div>
</div>
