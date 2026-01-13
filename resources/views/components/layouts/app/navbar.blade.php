<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Library App' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
</head>

<body class="bg-gray-50 text-gray-900 dark:bg-gray-900 dark:text-gray-100 antialiased">

    <header class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">

                {{-- LEFT SIDE: Logo & Navigation --}}
                <div class="flex">
                    <div class="shrink-0 flex items-center font-bold text-xl text-blue-600">
                        Feedback System
                    </div>

                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <!-- <a href="{{ route('services.index') }}" wire:navigate
                            class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-300 dark:hover:text-white transition">
                            Services
                        </a> -->
                    </div>
                </div>

                {{-- RIGHT SIDE: Dark Mode & User Menu --}}
                <div class="flex items-center gap-4">

                    {{-- Dark Mode Toggle --}}
                    <button
                        x-data="{ 
                            darkMode: localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches) 
                        }"
                        x-init="$watch('darkMode', val => {
                            val ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark');
                            localStorage.theme = val ? 'dark' : 'light';
                        })"
                        @click="darkMode = !darkMode"
                        class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 focus:outline-none transition">
                        <span x-show="darkMode">☀️</span>
                        <span x-show="!darkMode">🌙</span>
                    </button>

                    {{-- AUTHENTICATION LOGIC --}}
                    {{-- AUTHENTICATION LOGIC --}}
                    @auth
                    <div class="flex items-center gap-3">
                        {{-- 1. User Name & Admin Badge --}}
                        <div class="flex items-center gap-2">
                            <div class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ auth()->user()->name }}
                            </div>

                            {{-- ADMIN BADGE --}}
                            @if(auth()->user()->is_admin)
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200 border border-blue-200 dark:border-blue-800">
                                Admin
                            </span>
                            @endif
                        </div>

                        {{-- 2. Separator --}}
                        <span class="text-gray-300 dark:text-gray-600">|</span>

                        {{-- 3. Logout Button --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition">
                                Log Out
                            </button>
                        </form>
                    </div>
                    @else
                    {{-- Guest View --}}
                    <a href="{{ route('login') }}" wire:navigate class="text-sm font-medium text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white transition">
                        Log in
                    </a>
                    @endauth

                </div>
            </div>
        </div>
    </header>

    <main class="py-8">
        <div class="max-w-7xl mx-auto sm:px-4 lg:px-8">
            {{ $slot }}
        </div>
    </main>

</body>

</html>