<!doctype html>
<html lang="hr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
    <meta name="description" content="Špajza - popis za kupovinu" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Gabarito:wght@400..900&display=swap" rel="stylesheet" />
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="Špajza" />
    <link rel="manifest" href="/site.webmanifest" />
    <title>{{ $title ?? 'Špajza' }}</title>
    <script>
        (function() {
            const theme = localStorage.getItem('theme') || 'system';
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (theme === 'dark' || (theme === 'system' && prefersDark)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen font-sans antialiased">
    <header class="sticky top-0 z-50 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm border-b border-gray-200 dark:border-gray-700">
        <nav class="max-w-4xl mx-auto px-4 h-14 flex items-center justify-between">
            {{-- Logo --}}
            <a href="{{ route('lists.index') }}" class="flex text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                <x-app-logo class="h-10 w-auto" />
            </a>

            {{-- Desktop nav --}}
            <div class="hidden sm:flex items-center gap-6">
                <a href="{{ route('lists.index') }}" class="text-base font-medium text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors">
                    Lists
                </a>
                <a href="{{ route('items.index') }}" class="text-base font-medium text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors">
                    Items
                </a>
                <a href="{{ route('templates.index') }}" class="text-base font-medium text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors">
                    Templates
                </a>
                <a href="{{ route('categories.index') }}" class="text-base font-medium text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors">
                    Categories
                </a>
            </div>

            <div class="flex items-center gap-2" x-data="{ avatarOpen: false }">
                {{-- Avatar dropdown --}}
                <div class="relative" x-data="themeManager()" @click.outside="avatarOpen = false">
                    <button
                        @click="avatarOpen = !avatarOpen"
                        class="w-9 h-9 rounded-full bg-blue-600 text-white flex items-center justify-center text-sm font-semibold hover:bg-blue-700 transition-colors uppercase overflow-hidden ring-2 ring-gray-300 dark:ring-gray-600"
                        aria-label="User menu"
                        x-data="{ hasGravatar: true }"
                    >
                        <img
                            x-show="hasGravatar"
                            x-on:error="hasGravatar = false"
                            src="{{ auth()->user()->gravatarUrl(72) }}"
                            alt=""
                            class="w-full h-full object-cover"
                        >
                        <span x-show="!hasGravatar" x-cloak>{{ auth()->user()->initials() }}</span>
                    </button>
                    <div x-show="avatarOpen" x-cloak x-transition class="absolute right-0 mt-2 w-56 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-lg py-1 z-50">
                        <div class="px-4 py-2.5 border-b border-gray-200 dark:border-gray-700">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ auth()->user()->email }}</p>
                        </div>
                        <div class="py-1 border-b border-gray-200 dark:border-gray-700">
                            <p class="px-4 py-1.5 text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wide">Theme</p>
                            <button @click="setTheme('light')" class="w-full flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                Light
                                <svg x-show="current === 'light'" class="w-4 h-4 ml-auto" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                            </button>
                            <button @click="setTheme('dark')" class="w-full flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
                                Dark
                                <svg x-show="current === 'dark'" class="w-4 h-4 ml-auto" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                            </button>
                            <button @click="setTheme('system')" class="w-full flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                System
                                <svg x-show="current === 'system'" class="w-4 h-4 ml-auto" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                            </button>
                        </div>
                        <div class="py-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </nav>
    </header>

    <main class="max-w-4xl mx-auto px-4 py-6 pb-24 sm:pb-6">
        {{ $slot }}
    </main>

    {{-- Bottom tab navigation (mobile) --}}
    <nav class="fixed bottom-0 inset-x-0 z-50 sm:hidden bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm border-t border-gray-200 dark:border-gray-700" style="padding-bottom: env(safe-area-inset-bottom)">
        <div class="flex items-center justify-around h-16">
            <a href="{{ route('lists.index') }}" class="flex flex-col items-center gap-1 px-3 py-2 {{ request()->routeIs('lists.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                <span class="text-xs font-medium">Liste</span>
            </a>
            <a href="{{ route('items.index') }}" class="flex flex-col items-center gap-1 px-3 py-2 {{ request()->routeIs('items.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <span class="text-xs font-medium">Artikli</span>
            </a>
            <a href="{{ route('templates.index') }}" class="flex flex-col items-center gap-1 px-3 py-2 {{ request()->routeIs('templates.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                </svg>
                <span class="text-xs font-medium">Predlošci</span>
            </a>
            <a href="{{ route('categories.index') }}" class="flex flex-col items-center gap-1 px-3 py-2 {{ request()->routeIs('categories.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z" />
                </svg>
                <span class="text-xs font-medium">Kategorije</span>
            </a>
        </div>
    </nav>

    @livewireScripts
    <script>
        function themeManager() {
            return {
                current: localStorage.getItem('theme') || 'system',
                setTheme(theme) {
                    this.current = theme;
                    localStorage.setItem('theme', theme);
                    this.apply(theme);
                },
                apply(theme) {
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    if (theme === 'dark' || (theme === 'system' && prefersDark)) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                },
                init() {
                    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
                        if (this.current === 'system') this.apply('system');
                    });
                }
            }
        }
    </script>
</body>
</html>
