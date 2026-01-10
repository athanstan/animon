{{--
    Hero section with scattered anime images.
--}}
<section class="relative py-16 md:py-24 overflow-hidden">
    <!-- Scattered Anime Images -->
    <div class="absolute inset-0 overflow-hidden">
        @foreach ($animeList->take(10) as $index => $anime)
            @php
                $positions = [
                    ['top' => '5%', 'left' => '24%', 'rotate' => '-12deg', 'size' => 'w-28 h-40'],
                    ['top' => '10%', 'right' => '8%', 'rotate' => '8deg', 'size' => 'w-32 h-44'],
                    ['top' => '35%', 'left' => '10%', 'rotate' => '15deg', 'size' => 'w-24 h-36'],
                    ['top' => '65%', 'right' => '25%', 'rotate' => '-8deg', 'size' => 'w-28 h-40'],
                    ['bottom' => '10%', 'left' => '12%', 'rotate' => '-15deg', 'size' => 'w-32 h-44'],
                    ['bottom' => '20%', 'right' => '15%', 'rotate' => '12deg', 'size' => 'w-24 h-36'],
                    ['top' => '25%', 'right' => '22%', 'rotate' => '-6deg', 'size' => 'w-28 h-40'],
                    ['bottom' => '35%', 'left' => '24%', 'rotate' => '10deg', 'size' => 'w-26 h-38'],
                    ['top' => '45%', 'left' => '3%', 'rotate' => '-10deg', 'size' => 'w-24 h-36'],
                    ['bottom' => '5%', 'right' => '5%', 'rotate' => '14deg', 'size' => 'w-30 h-42'],
                ];
                $pos = $positions[$index] ?? $positions[0];
            @endphp
            <div class="absolute {{ $pos['size'] }} brutal-card rounded-xl overflow-hidden opacity-40 hover:opacity-70 transition-all duration-300 hover:scale-110 hover:z-50"
                style="
                    @if (isset($pos['top'])) top: {{ $pos['top'] }}; @endif
                    @if (isset($pos['bottom'])) bottom: {{ $pos['bottom'] }}; @endif
                    @if (isset($pos['left'])) left: {{ $pos['left'] }}; @endif
                    @if (isset($pos['right'])) right: {{ $pos['right'] }}; @endif
                    transform: rotate({{ $pos['rotate'] }});
                    animation: float-bg {{ 8 + $index }}s ease-in-out infinite {{ $index * 0.5 }}s;
                ">
                <img src="{{ $anime->images['webp']['large_image_url'] ?? $anime->images['jpg']['large_image_url'] }}"
                    alt="{{ $anime->title }}" class="w-full h-full object-cover" loading="lazy" />
            </div>
        @endforeach
    </div>

    <!-- Floating Icons -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none -z-10 opacity-15">
        <!-- TV/Monitor icons -->
        <x-icon.phosphor name="television" weight="fill" class="absolute text-6xl text-kawaii-pink"
            style="top: 15%; left: 25%; animation: float-bg 8s ease-in-out infinite;" />
        <x-icon.phosphor name="television" weight="fill" class="absolute text-5xl text-kawaii-pink"
            style="bottom: 20%; right: 28%; animation: float-bg 9s ease-in-out infinite 4s;" />

        <!-- Film/Movie icons -->
        <x-icon.phosphor name="film-strip" weight="bold" class="absolute text-6xl text-kawaii-coral"
            style="top: 25%; right: 30%; animation: float-bg 9s ease-in-out infinite 1s;" />
        <x-icon.phosphor name="film-strip" weight="bold" class="absolute text-5xl text-kawaii-coral"
            style="bottom: 25%; left: 32%; animation: float-bg 10s ease-in-out infinite 5s;" />

        <!-- Popcorn icons -->
        <x-icon.phosphor name="popcorn" weight="fill" class="absolute text-7xl text-kawaii-lavender"
            style="top: 35%; left: 20%; animation: float-bg 10s ease-in-out infinite 2s;" />
        <x-icon.phosphor name="popcorn" weight="fill" class="absolute text-6xl text-kawaii-lavender"
            style="bottom: 30%; right: 22%; animation: float-bg 11s ease-in-out infinite 6s;" />

        <!-- Play icons -->
        <x-icon.phosphor name="play-circle" weight="fill" class="absolute text-7xl text-kawaii-mint"
            style="top: 45%; right: 25%; animation: float-bg 7s ease-in-out infinite 3s;" />
        <x-icon.phosphor name="play-circle" weight="fill" class="absolute text-6xl text-kawaii-mint"
            style="bottom: 35%; left: 27%; animation: float-bg 8s ease-in-out infinite 7s;" />

        <!-- Heart/Love icons (for watchlist/favorites) -->
        <x-icon.phosphor name="heart" weight="fill" class="absolute text-6xl text-kawaii-coral"
            style="top: 20%; left: 35%; animation: float-bg 9s ease-in-out infinite 1.5s;" />
        <x-icon.phosphor name="heart" weight="fill" class="absolute text-5xl text-kawaii-coral"
            style="bottom: 15%; right: 35%; animation: float-bg 10s ease-in-out infinite 5.5s;" />

        <!-- Star icons (for ratings) -->
        <x-icon.phosphor name="star" weight="fill" class="absolute text-6xl text-kawaii-peach"
            style="top: 30%; right: 35%; animation: float-bg 8s ease-in-out infinite 2.5s;" />
        <x-icon.phosphor name="star" weight="fill" class="absolute text-5xl text-kawaii-peach"
            style="bottom: 40%; left: 35%; animation: float-bg 9s ease-in-out infinite 6.5s;" />

        <!-- List/Checklist icons (for tracking) -->
        <x-icon.phosphor name="list-checks" weight="bold" class="absolute text-6xl text-kawaii-sky"
            style="top: 40%; left: 28%; animation: float-bg 11s ease-in-out infinite 3.5s;" />
        <x-icon.phosphor name="list-checks" weight="bold" class="absolute text-5xl text-kawaii-sky"
            style="bottom: 18%; right: 30%; animation: float-bg 12s ease-in-out infinite 7.5s;" />

        <!-- Bookmark icons (for watchlist) -->
        <x-icon.phosphor name="bookmark" weight="fill" class="absolute text-6xl text-kawaii-mint"
            style="top: 50%; right: 32%; animation: float-bg 10s ease-in-out infinite 4s;" />
        <x-icon.phosphor name="bookmark" weight="fill" class="absolute text-5xl text-kawaii-mint"
            style="bottom: 45%; left: 30%; animation: float-bg 11s ease-in-out infinite 8s;" />

        <!-- Clock icons (for episode tracking) -->
        <x-icon.phosphor name="clock" weight="bold" class="absolute text-6xl text-kawaii-sage"
            style="top: 55%; left: 23%; animation: float-bg 9s ease-in-out infinite 4.5s;" />
        <x-icon.phosphor name="clock" weight="bold" class="absolute text-5xl text-kawaii-sage"
            style="bottom: 50%; right: 25%; animation: float-bg 10s ease-in-out infinite 8.5s;" />
    </div>

    <div class="container mx-auto px-4 text-center relative z-10">

        <!-- Decorative icons at bottom -->
        <div class="flex justify-center gap-6 mt-12 opacity-50">
            <x-icon.phosphor name="film-strip" weight="bold" class="text-2xl text-kawaii-pink" />
            <x-icon.phosphor name="heart" weight="fill" class="text-2xl text-kawaii-coral" />
            <x-icon.phosphor name="star" weight="fill" class="text-2xl text-kawaii-lavender" />
            <x-icon.phosphor name="bookmark" weight="bold" class="text-2xl text-kawaii-mint" />
        </div>

        <h1
            class="block font-display font-black text-3xl md:text-4xl lg:text-5xl mb-8 leading-tight tracking-tight max-w-4xl mx-auto">
            Track episodes you've watched
            <br />
            Discover new series
            <br />
            Share your anime journey with friends
        </h1>

        <p class="block text-2xl md:text-3xl font-bold text-text-primary mb-10 max-w-2xl mx-auto">
            Your Anime Journey Starts Here 🎌
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <flux:button :href="Route::has('register') ? route('register') : '#'"
                class="btn-kawaii px-8 py-4 text-lg md:text-xl rounded-2xl font-black bg-kawaii-coral group relative overflow-hidden">
                <span class="relative z-10 flex items-center gap-2">
                    <x-icon.phosphor name="play" weight="fill" class="text-xl" />
                    Play Around - It's Free!
                </span>
                <span
                    class="absolute inset-0 bg-kawaii-pink transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></span>
            </flux:button>
            <flux:button href="#browse"
                class="btn-kawaii px-8 py-4 text-lg md:text-xl rounded-2xl font-black bg-kawaii-sky group relative overflow-hidden">
                <span class="relative z-10 flex items-center gap-2">
                    <x-icon.phosphor name="compass" weight="bold" class="text-xl" />
                    Browse Anime
                </span>
                <span
                    class="absolute inset-0 bg-kawaii-mint transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></span>
            </flux:button>
        </div>

    </div>
</section>
