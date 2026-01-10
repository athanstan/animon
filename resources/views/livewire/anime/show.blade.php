{{--
    Anime detail page - inspired by Letterboxd's film pages.
--}}

<div x-data="{ showGoToTop: false }" x-init="window.addEventListener('scroll', () => { showGoToTop = window.scrollY > 500 })" x-cloak>
    <!-- Hero Section with Backdrop -->
    <section class="relative">
        <!-- Gradient Backdrop -->
        <div
            class="absolute inset-0 h-80 bg-gradient-to-b from-kawaii-lavender/40 via-kawaii-pink/20 to-surface-primary">
        </div>

        <div class="container mx-auto px-4 pt-8 pb-12 relative">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Poster Column -->
                <div class="flex-shrink-0 flex flex-col items-center lg:items-start gap-4">
                    <!-- Poster -->
                    <div class="anime-card rounded-2xl overflow-hidden w-64 md:w-72">
                        <img src="{{ $animeDetails->getLargeImageUrl() }}" alt="Poster for {{ $animeDetails->title }}"
                            class="w-full aspect-[2/3] object-cover" />
                    </div>

                    <!-- Stats Bar -->
                    <x-anime.stats-bar :members="$animeDetails->getFormattedMembers()" :favorites="$animeDetails->getFormattedFavorites()" :rank="$animeDetails->rank" />

                </div>

                <!-- Info Column -->
                <div class="flex-1 min-w-0">
                    <!-- Title Block -->
                    <div class="mb-6">
                        <h1
                            class="text-2xl md:text-3xl lg:text-4xl font-display font-black leading-tight mb-2 text-center md:text-left">
                            {{ $animeDetails->title }}
                        </h1>

                        @if ($animeDetails->titleEnglish && $animeDetails->titleEnglish !== $animeDetails->title)
                            <flux:text size="lg" class="text-text-secondary mb-1">
                                {{ $animeDetails->titleEnglish }}
                            </flux:text>
                        @endif

                        @if ($animeDetails->titleJapanese)
                            <flux:text size="base" class="text-text-secondary/70 font-kawaii">
                                {{ $animeDetails->titleJapanese }}
                            </flux:text>
                        @endif

                        <!-- Meta Line -->
                        <div class="flex flex-wrap items-center gap-3 mt-4 text-sm">
                            @if ($animeDetails->year)
                                <flux:link href="#" class="font-bold hover:text-kawaii-coral transition-colors">
                                    {{ $animeDetails->year }}
                                </flux:link>
                            @endif

                            @if ($animeDetails->type)
                                <span
                                    class="px-2 py-1 rounded-md font-semibold text-xs border-2 border-border-brutal bg-kawaii-sky">
                                    {{ $animeDetails->type }}
                                </span>
                            @endif

                            @if ($animeDetails->episodes)
                                <span class="text-text-secondary">
                                    {{ $animeDetails->episodes }} {{ Str::plural('episode', $animeDetails->episodes) }}
                                </span>
                            @endif

                            @if ($animeDetails->duration)
                                <span class="text-text-secondary">• {{ $animeDetails->duration }}</span>
                            @endif

                            @if (count($animeDetails->studios) > 0)
                                <span class="text-text-secondary">•</span>
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="text-text-secondary text-sm">by</span>
                                    @foreach ($animeDetails->studios as $studio)
                                        <a href="#"
                                            class="font-semibold hover:text-kawaii-coral transition-colors">
                                            {{ $studio['name'] }}
                                        </a>
                                        @if (!$loop->last)
                                            <span class="text-text-secondary">,</span>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Genres & Themes -->
                        @if (count($animeDetails->genres) > 0 || count($animeDetails->themes) > 0)
                            <div class="mt-4 space-y-3">
                                @if (count($animeDetails->genres) > 0)
                                    <div>
                                        <span
                                            class="text-xs font-bold text-text-secondary/70 uppercase tracking-wide mb-2 block">
                                            Genres
                                        </span>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach ($animeDetails->genres as $genre)
                                                <a href="#"
                                                    class="btn-kawaii px-3 py-1.5 rounded-lg text-sm font-semibold bg-kawaii-pink hover:bg-kawaii-coral transition-colors">
                                                    {{ $genre['name'] }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                @if (count($animeDetails->themes) > 0)
                                    <div>
                                        <span
                                            class="text-xs font-bold text-text-secondary/70 uppercase tracking-wide mb-2 block">
                                            Themes
                                        </span>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach ($animeDetails->themes as $theme)
                                                <a href="#"
                                                    class="btn-kawaii px-3 py-1.5 rounded-lg text-sm font-semibold bg-kawaii-lavender hover:bg-kawaii-sage transition-colors">
                                                    {{ $theme['name'] }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Synopsis & Background Tabs -->
                    <div class="mb-8" x-data="{ activeTab: 'synopsis' }" x-cloak>
                        <!-- Tab Buttons -->
                        <div class="flex gap-2 mb-4">
                            <button type="button" @click="activeTab = 'synopsis'"
                                :class="activeTab === 'synopsis' ? 'bg-kawaii-pink text-text-primary' :
                                    'bg-surface-secondary text-text-secondary hover:bg-surface-secondary/80'"
                                :aria-selected="activeTab === 'synopsis'"
                                class="px-4 py-2 rounded-lg font-display font-bold text-sm transition-colors">
                                Synopsis
                            </button>

                            @if ($animeDetails->background)
                                <button type="button" @click="activeTab = 'background'"
                                    :class="activeTab === 'background' ? 'bg-kawaii-pink text-text-primary' :
                                        'bg-surface-secondary text-text-secondary hover:bg-surface-secondary/80'"
                                    :aria-selected="activeTab === 'background'"
                                    class="px-4 py-2 rounded-lg font-display font-bold text-sm transition-colors">
                                    Background
                                </button>
                            @endif
                        </div>

                        <!-- Tab Panels -->
                        <div x-show="activeTab === 'synopsis'" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                            <flux:text class="text-text-secondary leading-relaxed max-w-3xl">
                                {{ $animeDetails->synopsis ?? 'No synopsis available.' }}
                            </flux:text>
                        </div>

                        @if ($animeDetails->background)
                            <div x-show="activeTab === 'background'"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                                <flux:text class="text-text-secondary leading-relaxed max-w-3xl">
                                    {{ $animeDetails->background }}
                                </flux:text>
                            </div>
                        @endif
                    </div>

                    <!-- Trailer -->
                    @if ($animeDetails->getTrailerEmbedUrl())
                        <div class="mb-8">
                            <flux:heading size="sm" class="font-bold mb-3 text-text-secondary">
                                🎬 Trailer
                            </flux:heading>

                            <div class="brutal-card rounded-2xl overflow-hidden bg-surface-secondary max-w-2xl">
                                <div class="aspect-video">
                                    <iframe src="{{ $animeDetails->getTrailerEmbedUrl() }}"
                                        title="Trailer for {{ $animeDetails->title }}" class="w-full h-full"
                                        allowfullscreen loading="lazy"></iframe>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>

                <!-- Sidebar Column -->
                <div class="w-full lg:w-80 flex-shrink-0 space-y-4">
                    <x-anime.sidebar-card :anime="$animeDetails" />

                    @if ($nextAiringEpisode)
                        <x-anime.next-airing-card :episode="$nextAiringEpisode" />
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Episodes Section -->
    <section class="py-12 border-t-4 border-border-brutal bg-surface-secondary/30">
        <div class="container mx-auto px-4">
            <flux:heading size="xl" class="section-title font-display font-black mb-8">
                📺 Episodes
            </flux:heading>

            <div class="max-w-4xl">
                <livewire:anime.episodes :animeId="$animeId" lazy />
            </div>
        </div>
    </section>

    <!-- Go to Top Button -->
    <button x-show="showGoToTop" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-4" @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
        class="fixed bottom-8 right-8 z-50 btn-kawaii bg-kawaii-pink p-4 rounded-full shadow-brutal-lg hover:scale-110 transition-transform"
        aria-label="Go to top">
        <flux:icon.arrow-up class="w-6 h-6" aria-hidden="true" />
    </button>
</div>
