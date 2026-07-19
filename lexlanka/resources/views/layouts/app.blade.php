<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Dynamic page title --}}
    <title>{{ isset($header) ? $header . ' — LexLanka' : 'LexLanka LPMS' }}</title>
    <meta name="description" content="{{ $metaDescription ?? 'LexLanka Legal Practice Management System' }}">

    {{-- Inter font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Vite: Tailwind CSS + Alpine.js --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="h-full flex flex-col bg-slate-50 antialiased">

    {{-- ══════════════════════════════════════════════════════════════════
         TOP NAVIGATION BAR
         Alpine scope: mobileOpen (hamburger toggle)
    ══════════════════════════════════════════════════════════════════ --}}
    <nav class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-sm"
         x-data="{ mobileOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                {{-- ── Brand / Wordmark ──────────────────────────────── --}}
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 shrink-0">
                    <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center shadow">
                        <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd" d="M12 1.5a.75.75 0 0 1 .75.75V4.5a.75.75 0 0 1-1.5 0V2.25A.75.75 0 0 1 12 1.5ZM5.636 4.136a.75.75 0 0 1 1.06 0l1.592 1.591a.75.75 0 0 1-1.061 1.06l-1.591-1.59a.75.75 0 0 1 0-1.061Zm12.728 0a.75.75 0 0 1 0 1.06l-1.591 1.592a.75.75 0 0 1-1.06-1.061l1.59-1.591a.75.75 0 0 1 1.061 0Zm-6.816 4.496a.75.75 0 0 1 .82.311l5.228 7.917a.75.75 0 0 1-.777 1.148l-2.096-.382-1.965 3.678a.75.75 0 0 1-1.323 0l-1.965-3.678-2.096.382a.75.75 0 0 1-.777-1.148l5.228-7.917a.75.75 0 0 1 .723-.311ZM3 13.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 0 1.5h-3A.75.75 0 0 1 3 13.5Zm14.25-.75a.75.75 0 0 1 0 1.5h-3a.75.75 0 0 1 0-1.5h3Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <span class="text-xl font-bold tracking-tight text-slate-900">
                        Lex<span class="text-indigo-600">Lanka</span>
                    </span>
                </a>

                {{-- ── Desktop Nav Links (tab-style active state) ──────── --}}
                @php
                    $navLinks = [
                        ['label' => 'Dashboard',  'route' => 'dashboard',          'match' => 'dashboard'],
                        ['label' => 'Cases',      'route' => 'cases.index',        'match' => 'cases.*'],
                        ['label' => 'Clients',    'route' => 'clients.index',      'match' => 'clients.*'],
                        ['label' => 'Scheduling', 'route' => 'scheduling.index',   'match' => 'scheduling.*'],
                        ['label' => 'Documents',  'route' => 'documents.index',    'match' => 'documents.*'],
                        ['label' => 'Billing',    'route' => 'billing.index',      'match' => 'billing*'],
                    ];
                @endphp

                <div class="hidden md:flex items-center h-full gap-0.5">
                    @foreach ($navLinks as $link)
                        @if (\Route::has($link['route']))
                            @php $isActive = request()->routeIs($link['match']); @endphp
                            <a href="{{ route($link['route']) }}"
                               class="relative flex items-center gap-1.5 px-3.5 h-16 text-sm font-medium transition-colors duration-150
                                      {{ $isActive
                                          ? 'text-indigo-600 font-semibold'
                                          : 'text-slate-600 hover:text-indigo-600 hover:bg-indigo-50/60' }}">
                                {{-- Active bottom border tab indicator --}}
                                @if ($isActive)
                                    <span class="absolute bottom-0 left-0 right-0 h-0.5 bg-indigo-600 rounded-t-full"></span>
                                @endif
                                {{ $link['label'] }}
                            </a>
                        @endif
                    @endforeach
                </div>

                {{-- ── Right side: Profile dropdown + Hamburger ───────── --}}
                <div class="flex items-center gap-2">

                    {{-- Profile dropdown (desktop only) --}}
                    <div class="hidden md:block relative"
                         x-data="{ open: false }"
                         @keydown.escape.window="open = false">

                        <button @click="open = !open"
                                class="flex items-center gap-2 text-sm font-medium text-slate-700
                                       hover:text-indigo-600 focus:outline-none transition-colors">
                            {{-- Avatar initials circle --}}
                            <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 font-bold text-xs
                                        flex items-center justify-center ring-2 ring-white">
                                {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 2)) }}
                            </div>
                            <span class="max-w-[140px] truncate">{{ Auth::user()->name ?? 'Guest' }}</span>
                            <svg class="w-4 h-4 text-slate-400 transition-transform duration-200"
                                 :class="open && 'rotate-180'"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        {{-- Dropdown card --}}
                        <div x-show="open"
                             x-cloak
                             @click.outside="open = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 translate-y-1"
                             class="absolute right-0 top-full mt-2 w-52 bg-white border border-slate-200
                                    rounded-xl shadow-lg py-1 origin-top-right z-50">

                            {{-- User info header --}}
                            <div class="px-4 py-3 border-b border-slate-100">
                                <p class="text-xs text-slate-400 mb-0.5">Signed in as</p>
                                <p class="text-sm font-semibold text-slate-800 truncate">
                                    {{ Auth::user()->name ?? '' }}
                                </p>
                                <p class="text-xs text-slate-500 truncate">{{ Auth::user()->email ?? '' }}</p>
                                @if(Auth::user()->role ?? false)
                                    <span class="inline-block mt-1 text-xs px-1.5 py-0.5 rounded
                                                 bg-indigo-50 text-indigo-700 font-medium capitalize">
                                        {{ Auth::user()->role }}
                                    </span>
                                @endif
                            </div>

                            {{-- Log Out --}}
                            <form method="POST" action="{{ route('logout') }}" class="px-2 py-1">
                                @csrf
                                <button type="submit"
                                        class="w-full flex items-center gap-2 px-3 py-2 text-sm text-red-600
                                               font-medium rounded-lg hover:bg-red-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Hamburger button (mobile only) --}}
                    <button @click="mobileOpen = !mobileOpen"
                            class="md:hidden p-2 rounded-md text-slate-500 hover:text-slate-800
                                   hover:bg-slate-100 transition-colors"
                            aria-label="Toggle menu">
                        {{-- Hamburger icon --}}
                        <svg x-show="!mobileOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        {{-- Close icon --}}
                        <svg x-show="mobileOpen" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

            </div>
        </div>

        {{-- ── Mobile Menu ──────────────────────────────────────────── --}}
        <div x-show="mobileOpen"
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-1"
             class="md:hidden border-t border-slate-200 bg-white shadow-lg">

            {{-- Mobile nav links --}}
            <div class="px-3 pt-2 pb-1 space-y-0.5">
                @foreach ($navLinks as $link)
                    @if (\Route::has($link['route']))
                        @php $isActive = request()->routeIs($link['match']); @endphp
                        <a href="{{ route($link['route']) }}"
                           @click="mobileOpen = false"
                           class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                                  {{ $isActive
                                      ? 'bg-indigo-50 text-indigo-700 font-semibold border-l-2 border-indigo-600'
                                      : 'text-slate-600 hover:bg-slate-50 hover:text-indigo-600' }}">
                            {{ $link['label'] }}
                        </a>
                    @endif
                @endforeach
            </div>

            {{-- Mobile user info + logout --}}
            <div class="px-4 py-3 mt-1 border-t border-slate-200 flex items-center justify-between bg-slate-50">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 font-bold text-xs
                                flex items-center justify-center">
                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 2)) }}
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-800">{{ Auth::user()->name ?? 'Guest' }}</p>
                        @if(Auth::user()->role ?? false)
                            <p class="text-xs text-slate-400 capitalize">{{ Auth::user()->role }}</p>
                        @endif
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="flex items-center gap-1.5 text-sm text-red-600 font-medium hover:text-red-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Log Out
                    </button>
                </form>
            </div>
        </div>
    </nav>

    {{-- ══════════════════════════════════════════════════════════════════
         FLASH MESSAGE BANNER (Alpine.js powered, auto-dismisses in 5s)
    ══════════════════════════════════════════════════════════════════ --}}
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
             class="flash-banner border mx-auto mt-4 max-w-7xl w-full px-4 sm:px-6 lg:px-8">

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

            <button @click="show = false"
                    class="ml-4 shrink-0 p-1 rounded-md hover:bg-black/5 transition-colors"
                    aria-label="Dismiss">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════════════
         PAGE HEADER (dynamic title + optional breadcrumbs + action slot)
    ══════════════════════════════════════════════════════════════════ --}}
    @isset($header)
        <header class="bg-white border-b border-slate-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between py-5">
                    {{-- Left: breadcrumbs + page title --}}
                    <div>
                        @isset($breadcrumbs)
                            <nav class="flex items-center gap-1.5 text-xs text-slate-400 mb-1"
                                 aria-label="Breadcrumb">
                                {{ $breadcrumbs }}
                            </nav>
                        @endisset
                        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">
                            {{ $header }}
                        </h1>
                    </div>

                    {{-- Right: action buttons slot --}}
                    @isset($actions)
                        <div class="flex items-center gap-2 shrink-0 ml-4">
                            {{ $actions }}
                        </div>
                    @endisset
                </div>
            </div>
        </header>
    @endisset

    {{-- ══════════════════════════════════════════════════════════════════
         MAIN CONTENT AREA
    ══════════════════════════════════════════════════════════════════ --}}
    <main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{ $slot }}
    </main>

    {{-- ══════════════════════════════════════════════════════════════════
         FOOTER
    ══════════════════════════════════════════════════════════════════ --}}
    <footer class="border-t border-slate-200 bg-white mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
            <p class="text-xs text-slate-400">
                &copy; {{ date('Y') }} LexLanka Legal Practice Management. All rights reserved.
            </p>
            <p class="text-xs text-slate-400 capitalize">
                {{ Auth::user()->role ?? '' }}
            </p>
        </div>
    </footer>

</body>
</html>
