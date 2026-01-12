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
    <header class="relative z-[100] border-b-4 border-border-brutal bg-surface-secondary">
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
                <div class="hidden sm:block">
                    <livewire:anime-search />
                </div>

                <!-- Right Side Actions -->
                <div class="flex items-center gap-2">
                    @if (Route::has('login'))
                        @auth
                            <!-- Authenticated User Dropdown -->
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" @click.away="open = false"
                                    class="flex items-center gap-2 px-3 py-2 rounded-lg font-bold text-sm text-text-primary hover:bg-surface-primary/50 transition-colors"
                                    aria-label="User menu">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-kawaii-pink/20 border-2 border-border-brutal flex items-center justify-center font-black text-xs">
                                        {{ auth()->user()->initials() }}
                                    </div>
                                    <span class="hidden sm:inline">{{ auth()->user()->name }}</span>
                                    <flux:icon.chevron-down class="w-4 h-4" />
                                </button>

                                <div x-show="open" x-transition:enter="transition ease-out duration-150"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-100"
                                    x-transition:leave-start="opacity-100 scale-100"
                                    x-transition:leave-end="opacity-0 scale-95"
                                    class="absolute right-0 mt-2 w-56 rounded-lg border-2 border-border-brutal bg-surface-secondary shadow-brutal overflow-hidden z-50"
                                    x-cloak>
                                    <div class="p-3 border-b-2 border-border-brutal">
                                        <p class="font-bold text-sm text-text-primary truncate">{{ auth()->user()->name }}
                                        </p>
                                        <p class="text-xs text-text-secondary truncate">{{ auth()->user()->email }}</p>
                                    </div>

                                    <div class="py-1">
                                        <a href="{{ route('dashboard') }}"
                                            class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-text-primary hover:bg-surface-primary transition-colors"
                                            wire:navigate>
                                            <flux:icon.home class="w-4 h-4" />
                                            Dashboard
                                        </a>
                                        <a href="{{ route('profile.edit') }}"
                                            class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-text-primary hover:bg-surface-primary transition-colors"
                                            wire:navigate>
                                            <flux:icon.cog class="w-4 h-4" />
                                            Settings
                                        </a>
                                        <a href="{{ route('appearance.edit') }}"
                                            class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-text-primary hover:bg-surface-primary transition-colors"
                                            wire:navigate>
                                            <flux:icon.swatch class="w-4 h-4" />
                                            Appearance
                                        </a>
                                    </div>

                                    <div class="border-t-2 border-border-brutal py-1">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit"
                                                class="w-full flex items-center gap-2 px-3 py-2 text-sm font-medium text-red-600 hover:bg-surface-primary transition-colors">
                                                <flux:icon.arrow-right-start-on-rectangle class="w-4 h-4" />
                                                Log Out
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
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
    <main class="relative z-[1]">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <x-welcome.footer />
</body>

</html>
