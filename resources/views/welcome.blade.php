<x-layouts.guest title="animon.gg - Your Anime Journey Starts Here">
    @guest
        <!-- Hero Section - Auth Only -->
        @livewire('welcome.hero-with-anime')
    @endguest

    <!-- Most Famous Section -->
    <div id="browse">
        @livewire('welcome.top-anime', [
            'type' => \App\Enums\JikanAnimeType::TV,
            'sectionTitle' => '🔥 Most Famous',
            'sectionColor' => 'bg-kawaii-coral',
            'limit' => 6,
        ])
    </div>

    <!-- New Releases Section -->
    @livewire('welcome.top-anime', [
        'type' => \App\Enums\JikanAnimeType::TV,
        'filter' => \App\Enums\TopAnimeFilter::Airing,
        'sectionTitle' => '✨ Currently Airing',
        'sectionColor' => 'bg-kawaii-mint',
        'limit' => 6,
    ])

    <!-- CTA Section -->
    <section class="relative py-16">
        <div class="container mx-auto px-4">
            <div
                class="brutal-card rounded-2xl p-8 md:p-12 text-center bg-gradient-to-br from-kawaii-pink to-kawaii-lavender">
                <flux:heading size="2xl" class="font-display font-black mb-4">
                    Ready to level up your anime game? 🚀
                </flux:heading>
                <flux:text size="lg" class="mb-8 max-w-xl mx-auto opacity-80">
                    Join thousands of otaku tracking their progress, discovering hidden gems, and connecting with fellow
                    fans.
                </flux:text>
                <flux:button :href="Route::has('register') ? route('register') : '#'"
                    class="btn-kawaii px-8 py-4 text-lg rounded-xl font-bold bg-surface-primary">
                    Create Free Account ✨
                </flux:button>
            </div>
        </div>
    </section>
</x-layouts.guest>
