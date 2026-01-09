{{--
    Top Anime section component.
--}}

<section class="relative z-10 py-12">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between mb-8">
            <flux:heading size="xl" class="section-title font-display font-black">
                {{ $sectionTitle }}
            </flux:heading>
            <flux:button href="#"
                class="btn-kawaii px-4 py-2 rounded-lg font-semibold text-sm {{ $sectionColor }}">
                View All
            </flux:button>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 md:gap-6">
            @foreach ($animeList as $index => $anime)
                <x-welcome.anime-card
                    :title="$anime->title"
                    :score="$anime->score ?? 0"
                    :episodes="$anime->episodes ?? 0"
                    :image="$anime->images['jpg']['large_image_url'] ?? $anime->images['jpg']['image_url']"
                    :color="$sectionColor"
                    :rank="$index + 1"
                    :malId="$anime->malId"
                />
            @endforeach
        </div>
    </div>
</section>
