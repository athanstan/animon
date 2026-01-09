{{--
    Next airing episode card - shows countdown and episode info.
--}}

@props(['episode'])

<div class="brutal-card rounded-2xl p-5 bg-gradient-to-br from-kawaii-coral/20 to-kawaii-pink/20 border-2 border-kawaii-coral">
    <div class="flex items-center gap-3 mb-3">
        <span class="flex h-3 w-3 relative">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-kawaii-coral opacity-75"></span>
            <span class="relative inline-flex rounded-full h-3 w-3 bg-kawaii-coral"></span>
        </span>
        <flux:heading size="sm" class="font-bold text-kawaii-coral">
            Next Episode
        </flux:heading>
    </div>

    <div class="space-y-2">
        <flux:text class="font-bold text-lg">
            Episode {{ $episode->malId }}
        </flux:text>

        <flux:text class="text-text-secondary text-sm line-clamp-1">
            {{ $episode->getDisplayTitle() }}
        </flux:text>

        @if ($episode->aired)
            <div
                class="flex items-center gap-2 text-sm"
                x-data="countdown('{{ $episode->aired->toIso8601String() }}')"
            >
                <flux:icon.clock class="w-4 h-4 text-kawaii-coral" />
                <span class="font-mono font-semibold text-kawaii-coral" x-text="timeLeft"></span>
            </div>

            <flux:text class="text-xs text-text-secondary">
                {{ $episode->aired->format('l, M d \a\t g:i A') }}
            </flux:text>
        @endif
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('countdown', (targetDate) => ({
            timeLeft: '',
            interval: null,

            init() {
                this.updateCountdown();
                this.interval = setInterval(() => this.updateCountdown(), 1000);
            },

            destroy() {
                if (this.interval) clearInterval(this.interval);
            },

            updateCountdown() {
                const target = new Date(targetDate);
                const now = new Date();
                const diff = target - now;

                if (diff <= 0) {
                    this.timeLeft = 'Airing now!';
                    if (this.interval) clearInterval(this.interval);
                    return;
                }

                const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                if (days > 0) {
                    this.timeLeft = `${days}d ${hours}h ${minutes}m`;
                } else if (hours > 0) {
                    this.timeLeft = `${hours}h ${minutes}m ${seconds}s`;
                } else {
                    this.timeLeft = `${minutes}m ${seconds}s`;
                }
            }
        }));
    });
</script>
