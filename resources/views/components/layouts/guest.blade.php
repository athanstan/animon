{{--
    Guest layout for public-facing pages.
--}}
@props(['title' => 'animon.gg'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>{{ $title }}</title>

    <link rel="icon" href="/favicon.ico" sizes="any" />
    <link rel="icon" href="/favicon.svg" type="image/svg+xml" />
    <link rel="apple-touch-icon" href="/apple-touch-icon.png" />

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
        <div class="container mx-auto px-4 py-3">
            <nav class="flex items-center justify-between gap-4">
                <!-- Logo -->
                <a href="/" class="flex items-center gap-2 shrink-0">
                    <div class="font-display">
                        <span class="text-xl font-black tracking-tight">animon</span>
                        <span class="text-xl font-black text-kawaii-coral">.gg</span>
                    </div>
                </a>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center gap-1">
                    <a href="#"
                        class="px-3 py-2 rounded-lg font-bold text-sm text-text-secondary hover:text-text-primary hover:bg-surface-primary/50 transition-colors">
                        Feed
                    </a>
                    <a href="#"
                        class="px-3 py-2 rounded-lg font-bold text-sm text-text-secondary hover:text-text-primary hover:bg-surface-primary/50 transition-colors">
                        Lists
                    </a>
                    <a href="#"
                        class="px-3 py-2 rounded-lg font-bold text-sm text-text-secondary hover:text-text-primary hover:bg-surface-primary/50 transition-colors">
                        Members
                    </a>
                    <a href="#"
                        class="px-3 py-2 rounded-lg font-bold text-sm text-text-secondary hover:text-text-primary hover:bg-surface-primary/50 transition-colors">
                        Calendar
                    </a>
                </div>

                <!-- Search Bar -->
                <div class="flex-1 max-w-md hidden sm:block">
                    <div class="relative">
                        <flux:icon.magnifying-glass
                            class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-text-secondary" />
                        <input type="search" placeholder="Search anime..."
                            class="w-full pl-10 pr-4 py-2 rounded-xl border-2 border-border-brutal bg-surface-primary text-sm font-medium placeholder:text-text-secondary/60 focus:outline-none focus:ring-2 focus:ring-kawaii-pink focus:border-kawaii-pink transition-all" />
                    </div>
                </div>

                <!-- Right Side Actions -->
                <div class="flex items-center gap-2">
                    @if (Route::has('login'))
                        @auth
                            <flux:button :href="url('/dashboard')" variant="ghost" size="sm" class="font-bold">
                                Dashboard
                            </flux:button>
                        @else
                            <a href="{{ route('login') }}"
                                class="px-3 py-2 rounded-lg font-bold text-sm text-text-secondary hover:text-text-primary hover:bg-surface-primary/50 transition-colors hidden sm:block">
                                Log in
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="btn-kawaii px-4 py-2 rounded-xl font-bold text-sm bg-kawaii-pink">
                                    Sign Up ✨
                                </a>
                            @endif
                        @endauth
                    @endif

                    <!-- Theme Toggle -->
                    <button @click="darkMode = !darkMode"
                        class="btn-kawaii w-9 h-9 rounded-xl flex items-center justify-center bg-kawaii-lavender"
                        aria-label="Toggle theme">
                        <template x-if="!darkMode">
                            <flux:icon.moon class="w-4 h-4" />
                        </template>
                        <template x-if="darkMode">
                            <flux:icon.sun class="w-4 h-4" />
                        </template>
                    </button>
                </div>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="relative z-10">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <x-welcome.footer />
</body>

</html>
