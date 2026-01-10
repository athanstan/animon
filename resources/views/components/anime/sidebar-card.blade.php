{{--
    Sidebar card with actions, rating, and anime details.
    Inspired by Letterboxd's film detail sidebar.
--}}

@props(['anime'])

<div class="brutal-card rounded-2xl bg-surface-secondary overflow-hidden">
    @auth
        {{-- Action Icons Row (Watch, Like, Watchlist) --}}
        <div class="flex border-b-2 border-border-brutal/30">
            <flux:button variant="ghost" class="flex-1 flex-col !h-auto py-4 gap-1.5 rounded-none"
                aria-label="Mark as watched">
                <flux:icon.eye class="!size-7" />
                <span class="text-xs">Watch</span>
            </flux:button>

            <flux:button variant="ghost"
                class="flex-1 flex-col !h-auto py-4 gap-1.5 rounded-none border-x-2 border-border-brutal/30"
                aria-label="Add to favorites">
                <flux:icon.heart class="!size-7" />
                <span class="text-xs">Like</span>
            </flux:button>

            <flux:button variant="ghost" class="flex-1 flex-col !h-auto py-4 gap-1.5 rounded-none"
                aria-label="Add to watchlist">
                <flux:icon.clock class="!size-7" />
                <span class="text-xs">Watchlist</span>
            </flux:button>
        </div>

        {{-- Rate Section --}}
        <div class="px-5 py-4 border-b-2 border-border-brutal/30">
            <p class="text-center text-text-secondary text-sm mb-3">Rate</p>
            <div class="flex justify-center gap-1">
                @for ($i = 1; $i <= 5; $i++)
                    <flux:button variant="ghost" size="sm" square
                        class="!text-2xl text-text-secondary/40 hover:!text-kawaii-coral"
                        aria-label="Rate {{ $i }} stars">
                        ★
                    </flux:button>
                @endfor
            </div>
        </div>
    @else
        {{-- Guest Sign-in Prompt --}}
        <div class="px-5 py-6 border-b-2 border-border-brutal/30 text-center">
            <flux:icon.user-circle class="w-12 h-12 mx-auto mb-3 text-text-secondary/30" aria-hidden="true" />
            <p class="text-sm text-text-secondary mb-3">
                Sign in to log, rate or review
            </p>
            <div class="flex flex-col gap-2">
                <flux:button :href="route('login')" size="sm" class="btn-kawaii bg-kawaii-pink w-full">
                    Sign In
                </flux:button>
                <flux:button :href="route('register')" variant="ghost" size="sm" class="w-full">
                    Create Account
                </flux:button>
            </div>
        </div>
    @endauth

    {{-- Anime Details --}}
    <div class="px-5 py-4 space-y-3">
        @if ($anime->titleEnglish)
            <div class="flex justify-between gap-4">
                <span class="text-text-secondary text-sm shrink-0">English</span>
                <span class="text-sm font-medium text-right">{{ $anime->titleEnglish }}</span>
            </div>
        @endif

        @if ($anime->titleJapanese)
            <div class="flex justify-between gap-4">
                <span class="text-text-secondary text-sm shrink-0">Japanese</span>
                <span class="text-sm font-medium text-right font-kawaii">{{ $anime->titleJapanese }}</span>
            </div>
        @endif

        @if ($anime->title !== $anime->titleEnglish && $anime->title !== $anime->titleJapanese)
            <div class="flex justify-between gap-4">
                <span class="text-text-secondary text-sm shrink-0">Romaji</span>
                <span class="text-sm font-medium text-right">{{ $anime->title }}</span>
            </div>
        @endif

        @if ($anime->type)
            <div class="flex justify-between gap-4">
                <span class="text-text-secondary text-sm">Type</span>
                <span class="text-sm font-medium">{{ $anime->type }}</span>
            </div>
        @endif

        @if ($anime->episodes)
            <div class="flex justify-between gap-4">
                <span class="text-text-secondary text-sm">Episodes</span>
                <span class="text-sm font-medium">{{ $anime->episodes }}</span>
            </div>
        @endif

        @if ($anime->status)
            <div class="flex justify-between gap-4">
                <span class="text-text-secondary text-sm">Status</span>
                <span class="text-sm font-medium">
                    @if ($anime->status === 'Finished Airing')
                        Finished
                    @elseif ($anime->status === 'Currently Airing')
                        <span class="text-kawaii-coral">● Airing</span>
                    @elseif ($anime->status === 'Not yet aired')
                        <span class="text-kawaii-lavender">Upcoming</span>
                    @else
                        {{ $anime->status }}
                    @endif
                </span>
            </div>
        @endif

        @if ($anime->airedString)
            <div class="flex justify-between gap-4">
                <span class="text-text-secondary text-sm">Aired</span>
                <span class="text-sm font-medium text-right">{{ $anime->airedString }}</span>
            </div>
        @endif

        @if ($anime->season && $anime->year)
            <div class="flex justify-between gap-4">
                <span class="text-text-secondary text-sm">Season</span>
                <span class="text-sm font-medium capitalize">{{ ucfirst($anime->season) }} {{ $anime->year }}</span>
            </div>
        @endif

        @if ($anime->source)
            <div class="flex justify-between gap-4">
                <span class="text-text-secondary text-sm">Source</span>
                <span class="text-sm font-medium">{{ $anime->source }}</span>
            </div>
        @endif

        @if ($anime->rating)
            <div class="flex justify-between gap-4">
                <span class="text-text-secondary text-sm">Rating</span>
                <span class="text-sm font-medium">{{ Str::before($anime->rating, ' -') }}</span>
            </div>
        @endif

        @if ($anime->duration)
            <div class="flex justify-between gap-4">
                <span class="text-text-secondary text-sm">Duration</span>
                <span class="text-sm font-medium">{{ $anime->duration }}</span>
            </div>
        @endif

        @if ($anime->rank)
            <div class="flex justify-between gap-4">
                <span class="text-text-secondary text-sm">Ranked</span>
                <span class="text-sm font-bold text-kawaii-coral">#{{ number_format($anime->rank) }}</span>
            </div>
        @endif

        @if ($anime->popularity)
            <div class="flex justify-between gap-4">
                <span class="text-text-secondary text-sm">Popularity</span>
                <span class="text-sm font-bold text-kawaii-lavender">#{{ number_format($anime->popularity) }}</span>
            </div>
        @endif
    </div>

    @auth
        {{-- More Actions --}}
        <div class="px-5 py-4 border-t-2 border-border-brutal/30 space-y-1">
            <flux:button variant="ghost" class="w-full justify-start" size="sm">
                Review or log...
            </flux:button>

            <flux:button variant="ghost" class="w-full justify-start" size="sm">
                Add to lists...
            </flux:button>

            <flux:button variant="ghost" class="w-full justify-start" size="sm">
                Share this anime
            </flux:button>
        </div>
    @endauth
</div>
