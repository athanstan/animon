<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>animon.gg - Your Anime Journey Starts Here</title>

    <link rel="icon" href="/favicon.ico" sizes="any" />
    <link rel="icon" href="/favicon.svg" type="image/svg+xml" />
    <link rel="apple-touch-icon" href="/apple-touch-icon.png" />

    <!-- Fonts - Kawaii-inspired typography -->
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=nunito:400,600,700,800,900|zen-maru-gothic:400,500,700,900"
        rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-kawaii antialiased min-h-screen bg-surface-primary bg-grain bg-blend-overlay text-text-primary"
    x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="$watch('darkMode', val => {
        localStorage.setItem('darkMode', val);
        document.documentElement.classList.toggle('dark', val)
    });
    if (darkMode) document.documentElement.classList.add('dark')" x-cloak>
    <!-- Decorative Background Blobs -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="kawaii-blob w-96 h-96 bg-kawaii-pink opacity-30"
            style="top: -100px; right: -100px; animation-delay: 0s;"></div>
        <div class="kawaii-blob w-72 h-72 bg-kawaii-lavender opacity-25"
            style="bottom: 20%; left: -50px; animation-delay: -3s;"></div>
        <div class="kawaii-blob w-64 h-64 bg-kawaii-mint opacity-20"
            style="top: 40%; right: 10%; animation-delay: -5s;"></div>
        <div class="kawaii-blob w-80 h-80 bg-kawaii-sky opacity-25"
            style="bottom: -100px; right: 30%; animation-delay: -2s;"></div>
    </div>

    <!-- Header -->
    <header class="relative z-10 border-b-4 border-border-brutal bg-surface-secondary">
        <div class="container mx-auto px-4 py-4">
            <nav class="flex items-center justify-between">
                <!-- Logo -->
                <flux:link href="/" class="flex items-center gap-3 group">
                    <div class="brutal-card w-12 h-12 flex items-center justify-center rounded-xl bg-kawaii-pink">
                        <x-welcome.animon-mascot class="w-7 h-7" />
                    </div>
                    <div class="font-display">
                        <span class="text-2xl font-black tracking-tight">animon</span>
                        <span class="text-2xl font-black text-kawaii-coral">.gg</span>
                        <flux:text size="xs" class="font-medium text-text-secondary">Track. Discover. Share.
                        </flux:text>
                    </div>
                </flux:link>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center gap-6">
                    <flux:link href="#" class="font-semibold">Browse</flux:link>
                    <flux:link href="#" class="font-semibold">Seasonal</flux:link>
                    <flux:link href="#" class="font-semibold">Community</flux:link>
                </div>

                <!-- Right Side Actions -->
                <div class="flex items-center gap-3">
                    <!-- Theme Toggle -->
                    <button @click="darkMode = !darkMode"
                        class="btn-kawaii w-10 h-10 rounded-lg flex items-center justify-center bg-kawaii-lavender"
                        aria-label="Toggle theme">
                        <template x-if="!darkMode">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                        </template>
                        <template x-if="darkMode">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </template>
                    </button>

                    @if (Route::has('login'))
                        @auth
                            <flux:button :href="url('/dashboard')"
                                class="btn-kawaii px-5 py-2 rounded-lg font-bold bg-kawaii-mint">
                                Dashboard
                            </flux:button>
                        @else
                            <flux:link :href="route('login')" class="font-semibold hidden sm:block">
                                Log in
                            </flux:link>

                            @if (Route::has('register'))
                                <flux:button :href="route('register')"
                                    class="btn-kawaii px-5 py-2 rounded-lg font-bold bg-kawaii-pink">
                                    Sign Up ✨
                                </flux:button>
                            @endif
                        @endauth
                    @endif
                </div>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative z-10 py-16 md:py-24">
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
                <flux:button :href="Route::has('register') ? route('register') : '#'"
                    class="btn-kawaii px-8 py-4 text-lg rounded-xl font-bold bg-kawaii-coral">
                    Start Tracking Free →
                </flux:button>
                <flux:button href="#browse" class="btn-kawaii px-8 py-4 text-lg rounded-xl font-bold bg-kawaii-sky">
                    Browse Anime
                </flux:button>
            </div>
        </div>
    </section>

    <!-- Most Famous Section -->
    <section class="relative z-10 py-12" id="browse">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between mb-8">
                <flux:heading size="xl" class="section-title font-display font-black">
                    🔥 Most Famous
                </flux:heading>
                <flux:button href="#"
                    class="btn-kawaii px-4 py-2 rounded-lg font-semibold text-sm bg-kawaii-peach">
                    View All
                </flux:button>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 md:gap-6">
                @php
                    $famousAnime = [
                        [
                            'title' => 'Attack on Titan',
                            'score' => 9.1,
                            'episodes' => 87,
                            'image' => 'https://cdn.myanimelist.net/images/anime/10/47347.jpg',
                            'color' => 'bg-kawaii-coral',
                        ],
                        [
                            'title' => 'Demon Slayer',
                            'score' => 8.9,
                            'episodes' => 44,
                            'image' => 'https://cdn.myanimelist.net/images/anime/1286/99889.jpg',
                            'color' => 'bg-kawaii-pink',
                        ],
                        [
                            'title' => 'Jujutsu Kaisen',
                            'score' => 8.7,
                            'episodes' => 47,
                            'image' => 'https://cdn.myanimelist.net/images/anime/1171/109222.jpg',
                            'color' => 'bg-kawaii-lavender',
                        ],
                        [
                            'title' => 'One Piece',
                            'score' => 8.8,
                            'episodes' => 1100,
                            'image' => 'https://cdn.myanimelist.net/images/anime/6/73245.jpg',
                            'color' => 'bg-kawaii-sky',
                        ],
                        [
                            'title' => 'Fullmetal Alchemist',
                            'score' => 9.2,
                            'episodes' => 64,
                            'image' => 'https://cdn.myanimelist.net/images/anime/1223/96541.jpg',
                            'color' => 'bg-kawaii-mint',
                        ],
                        [
                            'title' => 'Death Note',
                            'score' => 8.6,
                            'episodes' => 37,
                            'image' => 'https://cdn.myanimelist.net/images/anime/9/9453.jpg',
                            'color' => 'bg-kawaii-peach',
                        ],
                    ];
                @endphp

                @foreach ($famousAnime as $index => $anime)
                    <x-welcome.anime-card :title="$anime['title']" :score="$anime['score']" :episodes="$anime['episodes']" :image="$anime['image']"
                        :color="$anime['color']" :rank="$index + 1" />
                @endforeach
            </div>
        </div>
    </section>

    <!-- New Releases Section -->
    <section class="relative z-10 py-12">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between mb-8">
                <flux:heading size="xl" class="section-title font-display font-black">
                    ✨ New Releases
                </flux:heading>
                <flux:button href="#"
                    class="btn-kawaii px-4 py-2 rounded-lg font-semibold text-sm bg-kawaii-mint">
                    View All
                </flux:button>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 md:gap-6">
                @php
                    $newReleases = [
                        [
                            'title' => 'Frieren',
                            'score' => 9.3,
                            'episodes' => 28,
                            'image' => 'https://cdn.myanimelist.net/images/anime/1015/138006.jpg',
                            'color' => 'bg-kawaii-mint',
                            'status' => 'Airing',
                        ],
                        [
                            'title' => 'Solo Leveling',
                            'score' => 8.4,
                            'episodes' => 12,
                            'image' => 'https://cdn.myanimelist.net/images/anime/1139/141102.jpg',
                            'color' => 'bg-kawaii-lavender',
                            'status' => 'New',
                        ],
                        [
                            'title' => 'Oshi no Ko S2',
                            'score' => 8.8,
                            'episodes' => 13,
                            'image' => 'https://cdn.myanimelist.net/images/anime/1764/141930.jpg',
                            'color' => 'bg-kawaii-pink',
                            'status' => 'Airing',
                        ],
                        [
                            'title' => 'Dandadan',
                            'score' => 8.6,
                            'episodes' => 12,
                            'image' => 'https://cdn.myanimelist.net/images/anime/1255/142645.jpg',
                            'color' => 'bg-kawaii-coral',
                            'status' => 'New',
                        ],
                        [
                            'title' => 'Blue Lock S2',
                            'score' => 8.2,
                            'episodes' => 14,
                            'image' => 'https://cdn.myanimelist.net/images/anime/1567/145109.jpg',
                            'color' => 'bg-kawaii-sky',
                            'status' => 'Airing',
                        ],
                        [
                            'title' => 'Sakamoto Days',
                            'score' => 8.5,
                            'episodes' => 11,
                            'image' => 'https://cdn.myanimelist.net/images/anime/1105/147137.jpg',
                            'color' => 'bg-kawaii-peach',
                            'status' => 'New',
                        ],
                    ];
                @endphp

                @foreach ($newReleases as $anime)
                    <x-welcome.anime-card :title="$anime['title']" :score="$anime['score']" :episodes="$anime['episodes']" :image="$anime['image']"
                        :color="$anime['color']" :status="$anime['status']" />
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="relative z-10 py-16">
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

    <!-- Footer -->
    <x-welcome.footer />
</body>

</html>
