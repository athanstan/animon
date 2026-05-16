{{--
    Single episode detail page for an anime.
--}}
<div class="container mx-auto px-4 py-10 max-w-3xl">
    <nav class="mb-8 text-sm font-bold text-text-secondary">
        <a
            href="{{ route('anime.show', $anime) }}"
            wire:navigate
            class="text-kawaii-pink hover:text-kawaii-coral transition-colors"
        >
            {{ $anime->title }}
        </a>
        <span class="mx-2" aria-hidden="true">/</span>
        <span class="text-text-primary">{{ __('Episode :num', ['num' => $episode->number]) }}</span>
    </nav>

    <header class="mb-8 space-y-4">
        <flux:heading size="2xl" class="font-display font-black">
            {{ $episode->title_romanji ?? $episode->title }}
        </flux:heading>

        <div class="flex flex-wrap gap-2">
            @if ($episode->filler)
                <flux:badge color="amber">{{ __('Filler') }}</flux:badge>
            @endif
            @if ($episode->recap)
                <flux:badge color="blue">{{ __('Recap') }}</flux:badge>
            @endif
        </div>

        <dl class="grid gap-3 text-sm sm:grid-cols-2">
            @if ($episode->aired)
                <div>
                    <dt class="font-bold text-text-secondary">{{ __('Aired') }}</dt>
                    <dd>{{ $episode->aired->format('M d, Y') }}</dd>
                </div>
            @endif
            @if ($episode->score)
                <div>
                    <dt class="font-bold text-text-secondary">{{ __('Score') }}</dt>
                    <dd>{{ number_format((float) $episode->score, 2) }}</dd>
                </div>
            @endif
            @if (filled($episode->duration))
                <div>
                    <dt class="font-bold text-text-secondary">{{ __('Duration') }}</dt>
                    <dd>{{ $episode->duration }}</dd>
                </div>
            @endif
        </dl>

        @if ($episode->url)
            <div>
                <a
                    href="{{ $episode->url }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="font-bold text-kawaii-pink hover:text-kawaii-coral hover:underline"
                >
                    {{ __('View on MyAnimeList') }}
                </a>
            </div>
        @endif
    </header>

    @if (filled($episode->synopsis))
        <section class="brutal-card rounded-2xl border-2 border-border-brutal bg-surface-secondary p-6 md:p-8">
            <flux:heading size="lg" class="font-display font-black mb-4">
                {{ __('Synopsis') }}
            </flux:heading>
            <flux:text class="leading-relaxed whitespace-pre-line">{{ $episode->synopsis }}</flux:text>
        </section>
    @endif
</div>
