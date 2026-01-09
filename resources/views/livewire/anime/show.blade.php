{{--
    Anime detail page - inspired by Letterboxd's film pages.
--}}

<div>
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
                        <img src="{{ $anime->getLargeImageUrl() }}" alt="Poster for {{ $anime->title }}"
                            class="w-full aspect-[2/3] object-cover" />
                    </div>

                    <!-- Stats Bar -->
                    <x-anime.stats-bar :members="$anime->getFormattedMembers()" :favorites="$anime->getFormattedFavorites()" :rank="$anime->rank" />

                </div>

                <!-- Info Column -->
                <div class="flex-1 min-w-0">
                    <!-- Title Block -->
                    <div class="mb-6">
                        <h1
                            class="text-2xl md:text-3xl lg:text-4xl font-display font-black leading-tight mb-2 text-center md:text-left">
                            {{ $anime->title }}
                        </h1>

                        @if ($anime->titleEnglish && $anime->titleEnglish !== $anime->title)
                            <flux:text size="lg" class="text-text-secondary mb-1">
                                {{ $anime->titleEnglish }}
                            </flux:text>
                        @endif

                        @if ($anime->titleJapanese)
                            <flux:text size="base" class="text-text-secondary/70 font-kawaii">
                                {{ $anime->titleJapanese }}
                            </flux:text>
                        @endif

                        <!-- Meta Line -->
                        <div class="flex flex-wrap items-center gap-3 mt-4 text-sm">
                            @if ($anime->year)
                                <flux:link href="#" class="font-bold hover:text-kawaii-coral transition-colors">
                                    {{ $anime->year }}
                                </flux:link>
                            @endif

                            @if ($anime->type)
                                <span
                                    class="px-2 py-1 rounded-md font-semibold text-xs border-2 border-border-brutal bg-kawaii-sky">
                                    {{ $anime->type }}
                                </span>
                            @endif

                            @if ($anime->episodes)
                                <span class="text-text-secondary">
                                    {{ $anime->episodes }} {{ Str::plural('episode', $anime->episodes) }}
                                </span>
                            @endif

                            @if ($anime->duration)
                                <span class="text-text-secondary">• {{ $anime->duration }}</span>
                            @endif
                        </div>

                        <!-- Genres & Studios -->
                        <div class="flex flex-wrap items-center gap-4 mt-4">
                            @if (count($anime->genres) > 0)
                                <div class="flex flex-wrap items-center gap-2">
                                    @foreach ($anime->genres as $genre)
                                        <a href="#"
                                            class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-kawaii-pink hover:bg-kawaii-coral transition-colors">
                                            {{ $genre['name'] }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif

                            @if (count($anime->studios) > 0)
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="text-text-secondary text-sm">by</span>
                                    @foreach ($anime->studios as $studio)
                                        <a href="#"
                                            class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-kawaii-mint hover:bg-kawaii-sky transition-colors">
                                            {{ $studio['name'] }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
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

                            @if ($anime->background)
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
                                {{ $anime->synopsis ?? 'No synopsis available.' }}
                            </flux:text>
                        </div>

                        @if ($anime->background)
                            <div x-show="activeTab === 'background'"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                                <flux:text class="text-text-secondary leading-relaxed max-w-3xl">
                                    {{ $anime->background }}
                                </flux:text>
                            </div>
                        @endif
                    </div>

                    <!-- Trailer -->
                    @if ($anime->getTrailerEmbedUrl())
                        <div class="mb-8">
                            <flux:heading size="sm" class="font-bold mb-3 text-text-secondary">
                                🎬 Trailer
                            </flux:heading>

                            <div class="brutal-card rounded-2xl overflow-hidden bg-surface-secondary max-w-2xl">
                                <div class="aspect-video">
                                    <iframe src="{{ $anime->getTrailerEmbedUrl() }}"
                                        title="Trailer for {{ $anime->title }}" class="w-full h-full" allowfullscreen
                                        loading="lazy"></iframe>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>

                <!-- Sidebar Column -->
                <div class="w-full lg:w-80 flex-shrink-0 space-y-4">
                    <x-anime.sidebar-card :anime="$anime" />

                    @if ($nextAiringEpisode)
                        <x-anime.next-airing-card :episode="$nextAiringEpisode" />
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Themes Section -->
    @if (count($anime->themes) > 0)
        <section class="py-8 border-t-4 border-border-brutal bg-surface-secondary/50">
            <div class="container mx-auto px-4">
                <div>
                    <flux:heading size="sm" class="font-bold mb-3 text-text-secondary">Themes</flux:heading>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($anime->themes as $theme)
                            <a href="#"
                                class="btn-kawaii px-3 py-1.5 rounded-lg text-sm font-semibold bg-kawaii-lavender hover:bg-kawaii-sage transition-colors">
                                {{ $theme['name'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Episodes Section -->
    @if ($episodes->isNotEmpty())
        <section class="py-12">
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-between mb-8">
                    <flux:heading size="xl" class="section-title font-display font-black">
                        📺 Episodes
                    </flux:heading>

                    <div class="flex items-center gap-4 text-sm text-text-secondary">
                        <span>{{ $episodes->count() }} episodes</span>

                        @if ($episodes->fillers()->count() > 0)
                            <span class="flex items-center gap-1">
                                <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                                {{ $episodes->fillers()->count() }} filler
                            </span>
                        @endif

                        @if ($episodes->recaps()->count() > 0)
                            <span class="flex items-center gap-1">
                                <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                                {{ $episodes->recaps()->count() }} recap
                            </span>
                        @endif
                    </div>
                </div>

                <x-anime.episode-list :episodes="$episodes" />
            </div>
        </section>
    @endif
</div>
