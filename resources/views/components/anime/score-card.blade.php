{{--
    Score card showing rating and stats.
--}}
@props(['score', 'scoredBy' => null, 'rank' => null, 'popularity' => null])

<div class="brutal-card rounded-2xl p-6 bg-surface-secondary">
    <!-- Score Display -->
    <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center w-24 h-24 rounded-2xl bg-gradient-to-br from-kawaii-pink to-kawaii-coral border-4 border-border-brutal mb-3">
            <span class="text-3xl font-black">
                {{ $score ? number_format($score, 1) : 'N/A' }}
            </span>
        </div>

        <!-- Star Rating Visual -->
        @if ($score)
            <div class="flex justify-center gap-0.5 mb-2">
                @php
                    $fullStars = floor($score / 2);
                    $halfStar = ($score / 2) - $fullStars >= 0.5;
                    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                @endphp

                @for ($i = 0; $i < $fullStars; $i++)
                    <span class="text-xl star-filled">★</span>
                @endfor

                @if ($halfStar)
                    <span class="text-xl star-filled">★</span>
                @endif

                @for ($i = 0; $i < $emptyStars; $i++)
                    <span class="text-xl star-empty">☆</span>
                @endfor
            </div>
        @endif

        @if ($scoredBy)
            <flux:text size="sm" class="text-text-secondary">
                {{ number_format($scoredBy) }} ratings
            </flux:text>
        @endif
    </div>

    <!-- Stats List -->
    <div class="space-y-3 border-t-2 border-border-brutal/30 pt-4">
        @if ($rank)
            <div class="flex items-center justify-between">
                <span class="text-text-secondary text-sm">Ranked</span>
                <span class="font-bold text-kawaii-coral">#{{ number_format($rank) }}</span>
            </div>
        @endif

        @if ($popularity)
            <div class="flex items-center justify-between">
                <span class="text-text-secondary text-sm">Popularity</span>
                <span class="font-bold text-kawaii-lavender">#{{ number_format($popularity) }}</span>
            </div>
        @endif
    </div>

    <!-- Rate This Anime CTA -->
    <div class="mt-6 pt-4 border-t-2 border-border-brutal/30">
        <button class="btn-kawaii w-full py-3 rounded-xl font-bold bg-kawaii-lavender flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
            </svg>
            <span>Rate This</span>
        </button>
    </div>
</div>
