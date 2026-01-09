{{--
    Details grid showing anime metadata.
--}}
@props(['anime'])

<div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    <!-- Status -->
    <div class="brutal-card rounded-xl p-4 bg-surface-secondary">
        <flux:text size="xs" class="text-text-secondary font-semibold uppercase tracking-wide mb-1">
            Status
        </flux:text>
        <flux:text class="font-bold">
            @if ($anime->status === 'Finished Airing')
                <span class="text-kawaii-mint">✓ Completed</span>
            @elseif ($anime->status === 'Currently Airing')
                <span class="text-kawaii-coral">● Airing</span>
            @else
                {{ $anime->status ?? 'Unknown' }}
            @endif
        </flux:text>
    </div>

    <!-- Aired -->
    @if ($anime->airedString)
        <div class="brutal-card rounded-xl p-4 bg-surface-secondary">
            <flux:text size="xs" class="text-text-secondary font-semibold uppercase tracking-wide mb-1">
                Aired
            </flux:text>
            <flux:text class="font-bold text-sm">
                {{ $anime->airedString }}
            </flux:text>
        </div>
    @endif

    <!-- Source -->
    @if ($anime->source)
        <div class="brutal-card rounded-xl p-4 bg-surface-secondary">
            <flux:text size="xs" class="text-text-secondary font-semibold uppercase tracking-wide mb-1">
                Source
            </flux:text>
            <flux:text class="font-bold">
                {{ $anime->source }}
            </flux:text>
        </div>
    @endif

    <!-- Rating -->
    @if ($anime->rating)
        <div class="brutal-card rounded-xl p-4 bg-surface-secondary">
            <flux:text size="xs" class="text-text-secondary font-semibold uppercase tracking-wide mb-1">
                Rating
            </flux:text>
            <flux:text class="font-bold text-sm">
                {{ $anime->rating }}
            </flux:text>
        </div>
    @endif

    <!-- Season -->
    @if ($anime->season && $anime->year)
        <div class="brutal-card rounded-xl p-4 bg-surface-secondary">
            <flux:text size="xs" class="text-text-secondary font-semibold uppercase tracking-wide mb-1">
                Season
            </flux:text>
            <flux:text class="font-bold capitalize">
                {{ $anime->season }} {{ $anime->year }}
            </flux:text>
        </div>
    @endif

    <!-- Producers -->
    @if (count($anime->producers) > 0)
        <div class="brutal-card rounded-xl p-4 bg-surface-secondary col-span-2 md:col-span-3">
            <flux:text size="xs" class="text-text-secondary font-semibold uppercase tracking-wide mb-1">
                Producers
            </flux:text>
            <flux:text class="font-bold text-sm">
                {{ implode(', ', array_map(fn($p) => $p['name'], $anime->producers)) }}
            </flux:text>
        </div>
    @endif
</div>
