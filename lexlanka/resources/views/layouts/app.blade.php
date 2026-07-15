<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Dynamic page title --}}
    <title>{{ isset($title) ? $title . ' — LexLanka' : 'LexLanka LPMS' }}</title>
    <meta name="description" content="{{ $metaDescription ?? 'LexLanka Legal Practice Management System' }}">

    {{-- Inter font from Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Vite: Tailwind CSS + Alpine JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full flex flex-col bg-slate-50 antialiased">

    {{-- ──────────────────────────────────────────────────────────────────
         TOP NAVIGATION BAR
    ────────────────────────────────────────────────────────────────── --}}
    <nav class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-sm" x-data="{ mobileOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                {{-- Wordmark --}}
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 shrink-0">
                    <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center shadow">
                        {{-- Scales-of-justice icon (inline SVG) --}}
                        <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd" d="M12 1.5a.75.75 0 0 1 .75.75V4.5a.75.75 0 0 1-1.5 0V2.25A.75.75 0 0 1 12 1.5ZM5.636 4.136a.75.75 0 0 1 1.06 0l1.592 1.591a.75.75 0 0 1-1.061 1.06l-1.591-1.59a.75.75 0 0 1 0-1.061Zm12.728 0a.75.75 0 0 1 0 1.06l-1.591 1.592a.75.75 0 0 1-1.06-1.061l1.59-1.591a.75.75 0 0 1 1.061 0Zm-6.816 4.496a.75.75 0 0 1 .82.311l5.228 7.917a.75.75 0 0 1-.777 1.148l-2.096-.382-1.965 3.678a.75.75 0 0 1-1.323 0l-1.965-3.678-2.096.382a.75.75 0 0 1-.777-1.148l5.228-7.917a.75.75 0 0 1 .723-.311ZM3 13.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 0 1.5h-3A.75.75 0 0 1 3 13.5Zm14.25-.75a.75.75 0 0 1 0 1.5h-3a.75.75 0 0 1 0-1.5h3Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <span class="text-xl font-bold tracking-tight text-slate-900">
                        Lex<span class="text-indigo-600">Lanka</span>
                    </span>
                </a>

                {{-- Desktop nav links --}}
                <div class="hidden md:flex items-center gap-1">
                    @php
                        $navLinks = [
                            ['label' => 'Dashboard',   'route' => 'dashboard',   'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                            ['label' => 'Cases',       'route' => 'cases.index',       'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                            ['label' => 'Clients',     'route' => 'clients.index',     'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                            ['label' => 'Scheduling',  'route' => 'scheduling.index',  'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                            ['label' => 'Documents',   'route' => 'documents.index',   'icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'],
                            ['label' => 'Billing',     'route' => 'billing.index',     'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ];
                    @endphp

                    @foreach ($navLinks as $link)
                        @if (\Route::has($link['route']))
                            @php $isActive = request()->routeIs($link['route'] . '*'); @endphp
                            <a href="{{ route($link['route']) }}"
                               class="{{ $isActive ? 'nav-link-active' : 'nav-link' }}">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $link['icon'] }}"/>
                                </svg>
                                {{ $link['label'] }}
                            </a>
                        @endif
                    @endforeach
                </div>

                {{-- Right side: user menu + mobile hamburger --}}
                <div class="flex items-center gap-3">

                    {{-- User pill (desktop) --}}
                    <div class="hidden md:flex items-center gap-2 pl-4 border-l border-slate-200"
                         x-data="{ open: false }" @keydown.escape.window="open = false">
                        <button @click="open = !open"
                                class="flex items-center gap-2 text-sm font-medium text-slate-700 hover:text-indigo-600 transition-colors">
                            <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 font-bold text-xs flex items-center justify-center">
                                {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 2)) }}
                            </div>
                            <span class="max-w-[120px] truncate">{{ Auth::user()->name ?? 'Guest' }}</span>
                            <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="open && 'rotate-180'"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        {{-- Dropdown --}}
                        <div x-show="open" x-cloak
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             @click.outside="open = false"
                             class="absolute right-4 top-14 z-50 w-48 bg-white border border-slate-200 rounded-xl shadow-lg py-1 origin-top-right">
                            <div class="px-4 py-2 border-b border-slate-100">
                                <p class="text-xs text-slate-400">Signed in as</p>
                                <p class="text-sm font-semibold text-slate-700 truncate">{{ Auth::user()->email ?? '' }}</p>
                                <span class="inline-block mt-0.5 text-xs px-1.5 py-0.5 rounded bg-indigo-50 text-indigo-700 font-medium capitalize">
                                    {{ Auth::user()->role ?? '' }}
                                </span>
                            </div>
                            <a href="#" class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">Profile</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-medium">
                                    Sign out
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Mobile hamburger --}}
                    <button @click="mobileOpen = !mobileOpen"
                            class="md:hidden p-2 rounded-md text-slate-500 hover:text-slate-700 hover:bg-slate-100 transition-colors">
                        <svg x-show="!mobileOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <svg x-show="mobileOpen" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile menu --}}
        <div x-show="mobileOpen" x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="md:hidden border-t border-slate-200 bg-white px-4 pb-4 pt-2 space-y-1">
            @foreach ($navLinks as $link)
                @if (\Route::has($link['route']))
                    @php $isActive = request()->routeIs($link['route'] . '*'); @endphp
                    <a href="{{ route($link['route']) }}"
                       class="{{ $isActive ? 'nav-link-active' : 'nav-link' }} w-full">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $link['icon'] }}"/>
                        </svg>
                        {{ $link['label'] }}
                    </a>
                @endif
            @endforeach

            <div class="pt-3 mt-3 border-t border-slate-200 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 font-bold text-xs flex items-center justify-center">
                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 2)) }}
                    </div>
                    <span class="text-sm font-medium text-slate-700">{{ Auth::user()->name ?? 'Guest' }}</span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-red-600 font-medium">Sign out</button>
                </form>
            </div>
        </div>
    </nav>

    {{-- ──────────────────────────────────────────────────────────────────
         FLASH MESSAGE BANNER (Alpine.js powered, auto-dismiss)
    ────────────────────────────────────────────────────────────────── --}}
    @if (session('success') || session('error'))
        <div x-data="{
                show: true,
                type: '{{ session('error') ? 'error' : 'success' }}',
                message: '{{ addslashes(session('success') ?? session('error')) }}'
             }"
             x-show="show"
             x-cloak
             x-init="setTimeout(() => show = false, 5000)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             :class="type === 'success'
                        ? 'bg-emerald-50 border-emerald-300 text-emerald-800'
                        : 'bg-red-50 border-red-300 text-red-800'"
             class="flash-banner border mx-auto mt-4 max-w-7xl px-4 sm:px-6 lg:px-8 w-full">

            {{-- Left: icon + message --}}
            <div class="flex items-center gap-3">
                <template x-if="type === 'success'">
                    <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </template>
                <template x-if="type === 'error'">
                    <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </template>
                <span x-text="message" class="text-sm font-medium"></span>
            </div>

            {{-- Right: dismiss button --}}
            <button @click="show = false"
                    class="ml-4 shrink-0 p-1 rounded-md hover:bg-black/5 transition-colors"
                    aria-label="Dismiss">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    @endif

    {{-- ──────────────────────────────────────────────────────────────────
         PAGE HEADER SLOT  (title + optional action button)
    ────────────────────────────────────────────────────────────────── --}}
    @isset($header)
        <div class="bg-white border-b border-slate-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="page-header">
                    {{-- Left: breadcrumb + page title --}}
                    <div>
                        @isset($breadcrumbs)
                            <nav class="flex items-center gap-1 text-xs text-slate-400 mb-1">
                                {{ $breadcrumbs }}
                            </nav>
                        @endisset
                        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">
                            {{ $header }}
                        </h1>
                    </div>

                    {{-- Right: action slot --}}
                    @isset($actions)
                        <div class="flex items-center gap-2">
                            {{ $actions }}
                        </div>
                    @endisset
                </div>
            </div>
        </div>
    @endisset

    {{-- ──────────────────────────────────────────────────────────────────
         MAIN CONTENT AREA
    ────────────────────────────────────────────────────────────────── --}}
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{ $slot }}
    </main>

    {{-- ──────────────────────────────────────────────────────────────────
         FOOTER
    ────────────────────────────────────────────────────────────────── --}}
    <footer class="border-t border-slate-200 bg-white mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
            <p class="text-xs text-slate-400">
                &copy; {{ date('Y') }} LexLanka LPMS. All rights reserved.
            </p>
            <p class="text-xs text-slate-400">
                {{ Auth::user()->branch ?? '' }}
            </p>
        </div>
    </footer>

</body>
</html>
