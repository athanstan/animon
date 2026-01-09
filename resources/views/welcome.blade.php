<x-layouts.guest title="animon.gg - Your Anime Journey Starts Here">
    <!-- Hero Section -->
    <section class="relative py-16 md:py-24">
        <div class="container mx-auto px-4 text-center">
            <!-- Sparkles decoration -->
            <div class="flex justify-center gap-4 mb-6">
                <span class="sparkle text-2xl" style="animation-delay: 0s;">✦</span>
                <span class="sparkle text-3xl" style="animation-delay: 0.3s;">★</span>
                <span class="sparkle text-2xl" style="animation-delay: 0.6s;">✦</span>
            </div>

            <flux:heading size="3xl" class="font-display font-black mb-6 leading-tight">
                Your Anime Journey
                <br />
                <span class="inline-block px-4 py-1 rounded-lg -rotate-1 bg-kawaii-pink">
                    Starts Here
                </span>
            </flux:heading>

            <flux:text size="xl" class="max-w-2xl mx-auto mb-10 text-text-secondary">
                Track episodes, discover new series, and share your otaku journey with a community that gets it.
                The Letterboxd for anime lovers. 🎌
            </flux:text>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <flux:button
                    :href="Route::has('register') ? route('register') : '#'"
                    class="btn-kawaii px-8 py-4 text-lg rounded-xl font-bold bg-kawaii-coral"
                >
                    Start Tracking Free →
                </flux:button>
                <flux:button
                    href="#browse"
                    class="btn-kawaii px-8 py-4 text-lg rounded-xl font-bold bg-kawaii-sky"
                >
                    Browse Anime
                </flux:button>
            </div>
        </div>
    </section>

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
            <div class="brutal-card rounded-2xl p-8 md:p-12 text-center bg-gradient-to-br from-kawaii-pink to-kawaii-lavender">
                <flux:heading size="2xl" class="font-display font-black mb-4">
                    Ready to level up your anime game? 🚀
                </flux:heading>
                <flux:text size="lg" class="mb-8 max-w-xl mx-auto opacity-80">
                    Join thousands of otaku tracking their progress, discovering hidden gems, and connecting with fellow
                    fans.
                </flux:text>
                <flux:button
                    :href="Route::has('register') ? route('register') : '#'"
                    class="btn-kawaii px-8 py-4 text-lg rounded-xl font-bold bg-surface-primary"
                >
                    Create Free Account ✨
                </flux:button>
            </div>
        </div>
    </section>
</x-layouts.guest>
