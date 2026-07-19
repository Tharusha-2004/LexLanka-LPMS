<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LexLanka — Legal Practice Management for Sri Lanka</title>
    <meta name="description" content="LexLanka is the premier case, scheduling, and billing management platform built exclusively for Sri Lankan law firms.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
        .hero-gradient {
            background: linear-gradient(135deg, #f8faff 0%, #eef2ff 40%, #e0e7ff 100%);
        }
        .feature-icon-ring {
            background: linear-gradient(135deg, #eef2ff, #e0e7ff);
        }
        .stat-card {
            background: rgba(255,255,255,0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
    </style>
</head>

<body class="antialiased font-['Inter'] bg-white text-slate-900">

    {{-- ══════════════════════════════════════════════════════════════════
         HEADER / NAVBAR
    ══════════════════════════════════════════════════════════════════ --}}
    <header class="fixed top-0 inset-x-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-200/60"
            x-data="{ scrolled: false }"
            @scroll.window="scrolled = window.scrollY > 10"
            :class="scrolled ? 'shadow-sm' : ''">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                {{-- Brand --}}
                <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd" d="M12 1.5a.75.75 0 0 1 .75.75V4.5a.75.75 0 0 1-1.5 0V2.25A.75.75 0 0 1 12 1.5ZM5.636 4.136a.75.75 0 0 1 1.06 0l1.592 1.591a.75.75 0 0 1-1.061 1.06l-1.591-1.59a.75.75 0 0 1 0-1.061Zm12.728 0a.75.75 0 0 1 0 1.06l-1.591 1.592a.75.75 0 0 1-1.06-1.061l1.59-1.591a.75.75 0 0 1 1.061 0Zm-6.816 4.496a.75.75 0 0 1 .82.311l5.228 7.917a.75.75 0 0 1-.777 1.148l-2.096-.382-1.965 3.678a.75.75 0 0 1-1.323 0l-1.965-3.678-2.096.382a.75.75 0 0 1-.777-1.148l5.228-7.917a.75.75 0 0 1 .723-.311ZM3 13.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 0 1.5h-3A.75.75 0 0 1 3 13.5Zm14.25-.75a.75.75 0 0 1 0 1.5h-3a.75.75 0 0 1 0-1.5h3Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <span class="text-xl font-bold tracking-tight text-slate-900">
                        Lex<span class="text-indigo-600">Lanka</span>
                    </span>
                </a>

                {{-- Desktop nav --}}
                <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-600">
                    <a href="#features" class="hover:text-indigo-600 transition-colors">Features</a>
                    <a href="#how-it-works" class="hover:text-indigo-600 transition-colors">How It Works</a>
                    <a href="#stats" class="hover:text-indigo-600 transition-colors">Platform</a>
                </nav>

                {{-- CTA button --}}
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-indigo-600 text-white
                                  text-sm font-semibold hover:bg-indigo-700 transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="hidden md:inline-flex text-sm font-medium text-slate-600 hover:text-indigo-600 transition-colors">
                            Sign in
                        </a>
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-full bg-indigo-600 text-white
                                  text-sm font-semibold hover:bg-indigo-700 transition-colors shadow-sm">
                            Login to Portal
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    {{-- ══════════════════════════════════════════════════════════════════
         HERO SECTION
    ══════════════════════════════════════════════════════════════════ --}}
    <section class="hero-gradient pt-32 pb-24 px-4 sm:px-6 lg:px-8 overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="max-w-4xl mx-auto text-center">

                {{-- Badge --}}
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-indigo-100 text-indigo-700
                            text-xs font-semibold tracking-wide uppercase mb-8 border border-indigo-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
                    Built for Sri Lanka's Legal Sector
                </div>

                {{-- Headline --}}
                <h1 class="text-5xl sm:text-6xl lg:text-7xl font-black tracking-tight text-slate-900 leading-[1.05] mb-6">
                    Modernize Your<br>
                    <span class="text-indigo-600">Legal Practice</span>
                </h1>

                {{-- Subtitle --}}
                <p class="text-lg sm:text-xl text-slate-600 max-w-2xl mx-auto leading-relaxed mb-10">
                    LexLanka is the premier case, scheduling, and billing management platform
                    built exclusively for Sri Lankan law firms. Run your entire practice
                    from a single, secure system.
                </p>

                {{-- CTA buttons --}}
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center gap-2 px-8 py-4 rounded-full bg-indigo-600 text-white
                              text-base font-bold hover:bg-indigo-700 active:scale-95 transition-all shadow-lg
                              shadow-indigo-200">
                        Access Client Portal
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                    <a href="#features"
                       class="inline-flex items-center gap-2 px-8 py-4 rounded-full bg-white text-slate-700
                              text-base font-semibold border border-slate-200 hover:border-indigo-300
                              hover:text-indigo-600 transition-all shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        See Features
                    </a>
                </div>
            </div>

            {{-- Stats row --}}
            <div id="stats" class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-20 max-w-3xl mx-auto">
                @foreach([
                    ['value' => '100%', 'label' => 'Sri Lanka Focused'],
                    ['value' => '5',    'label' => 'Core Modules'],
                    ['value' => 'PDF',  'label' => 'Auto Case Briefs'],
                    ['value' => '3NF',  'label' => 'Normalized DB'],
                ] as $stat)
                    <div class="stat-card border border-white/60 rounded-2xl p-5 text-center shadow-sm">
                        <p class="text-3xl font-black text-indigo-700">{{ $stat['value'] }}</p>
                        <p class="text-xs font-medium text-slate-500 mt-1">{{ $stat['label'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════════════════════
         FEATURES GRID
    ══════════════════════════════════════════════════════════════════ --}}
    <section id="features" class="bg-white py-24 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">

            {{-- Section header --}}
            <div class="text-center mb-16">
                <p class="text-sm font-semibold text-indigo-600 uppercase tracking-widest mb-3">Core Capabilities</p>
                <h2 class="text-4xl font-extrabold text-slate-900 tracking-tight">
                    Everything your practice needs
                </h2>
                <p class="mt-4 text-lg text-slate-500 max-w-xl mx-auto">
                    A complete, end-to-end solution covering every operational aspect of a modern law firm.
                </p>
            </div>

            {{-- 3-column feature grid --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                {{-- Feature 1: Case Tracking --}}
                <div class="group relative p-8 rounded-2xl border border-slate-200 hover:border-indigo-300
                            hover:shadow-lg hover:shadow-indigo-50 transition-all duration-300 bg-white">
                    <div class="feature-icon-ring w-12 h-12 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-3">
                        Comprehensive Case Tracking
                    </h3>
                    <p class="text-slate-500 leading-relaxed text-sm mb-5">
                        Manage the complete lifecycle of every matter from intake to closure. Assign attorneys, monitor statuses, and instantly generate official <strong class="text-slate-700">Case Brief PDFs</strong> for any case.
                    </p>
                    <ul class="space-y-2 text-sm">
                        @foreach(['Full document repository per case', 'Automated PDF case briefs via DomPDF', 'Real-time status change notifications'] as $item)
                            <li class="flex items-center gap-2 text-slate-600">
                                <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ $item }}
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Feature 2: Smart Scheduling --}}
                <div class="group relative p-8 rounded-2xl border border-indigo-200 bg-gradient-to-b from-indigo-50/60 to-white
                            hover:border-indigo-400 hover:shadow-lg hover:shadow-indigo-100 transition-all duration-300">
                    {{-- Popular badge --}}
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-600 text-white text-xs font-bold shadow">
                            Core Module
                        </span>
                    </div>
                    <div class="feature-icon-ring w-12 h-12 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-3">
                        Smart Court Scheduling
                    </h3>
                    <p class="text-slate-500 leading-relaxed text-sm mb-5">
                        Never miss a court date. Schedule and track <strong class="text-slate-700">Calling Dates</strong> and <strong class="text-slate-700">Trial Dates</strong> linked to specific cases, with built-in future-date validation and client reminders.
                    </p>
                    <ul class="space-y-2 text-sm">
                        @foreach(['Upcoming / past date toggle views', 'Calling & trial date classification', 'Automated email reminder tracking'] as $item)
                            <li class="flex items-center gap-2 text-slate-600">
                                <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ $item }}
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Feature 3: Billing --}}
                <div class="group relative p-8 rounded-2xl border border-slate-200 hover:border-indigo-300
                            hover:shadow-lg hover:shadow-indigo-50 transition-all duration-300 bg-white">
                    <div class="feature-icon-ring w-12 h-12 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-3">
                        Trust & Operational Billing
                    </h3>
                    <p class="text-slate-500 leading-relaxed text-sm mb-5">
                        Maintain strict compliance with <strong class="text-slate-700">isolated Client Trust and Operational ledgers</strong>. Automatically calculate attorney appearance fees based on trial date counts and flat rates.
                    </p>
                    <ul class="space-y-2 text-sm">
                        @foreach(['Partner-only financial dashboard', 'Retainer misclassification prevention', 'Appearance fee auto-calculation'] as $item)
                            <li class="flex items-center gap-2 text-slate-600">
                                <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ $item }}
                            </li>
                        @endforeach
                    </ul>
                </div>

            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════════════════════
         HOW IT WORKS SECTION
    ══════════════════════════════════════════════════════════════════ --}}
    <section id="how-it-works" class="bg-slate-50 py-24 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <p class="text-sm font-semibold text-indigo-600 uppercase tracking-widest mb-3">Workflow</p>
                <h2 class="text-4xl font-extrabold text-slate-900 tracking-tight">
                    From intake to resolution
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                @foreach([
                    ['step' => '01', 'title' => 'Register Client', 'desc' => 'Capture client details, NIC, and contact info during the intake process.', 'color' => 'indigo'],
                    ['step' => '02', 'title' => 'Open Case',       'desc' => 'Assign an attorney, set the case type, and link to the registered client.', 'color' => 'violet'],
                    ['step' => '03', 'title' => 'Schedule Dates',  'desc' => 'Add calling and trial dates. Clients receive automated status updates.', 'color' => 'sky'],
                    ['step' => '04', 'title' => 'Manage Billing',  'desc' => 'Record trust and operational entries. Generate reports for the firm.', 'color' => 'emerald'],
                ] as $step)
                    <div class="relative bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                        <div class="text-5xl font-black text-slate-100 mb-4 leading-none select-none">
                            {{ $step['step'] }}
                        </div>
                        <h3 class="text-base font-bold text-slate-900 mb-2">{{ $step['title'] }}</h3>
                        <p class="text-sm text-slate-500 leading-relaxed">{{ $step['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════════════════════
         FINAL CTA SECTION
    ══════════════════════════════════════════════════════════════════ --}}
    <section class="bg-indigo-600 py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-4xl font-extrabold text-white tracking-tight mb-4">
                Ready to transform your practice?
            </h2>
            <p class="text-indigo-200 text-lg mb-10">
                Access your secure portal and take full control of your firm's operations today.
            </p>
            <a href="{{ route('login') }}"
               class="inline-flex items-center gap-2 px-10 py-4 rounded-full bg-white text-indigo-700
                      text-base font-bold hover:bg-indigo-50 active:scale-95 transition-all shadow-xl">
                Access Client Portal
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════════════════════
         FOOTER
    ══════════════════════════════════════════════════════════════════ --}}
    <footer class="bg-slate-900 text-slate-400 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-8 mb-10">

                {{-- Brand --}}
                <div>
                    <div class="flex items-center gap-2.5 mb-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-500 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path fill-rule="evenodd" d="M12 1.5a.75.75 0 0 1 .75.75V4.5a.75.75 0 0 1-1.5 0V2.25A.75.75 0 0 1 12 1.5ZM5.636 4.136a.75.75 0 0 1 1.06 0l1.592 1.591a.75.75 0 0 1-1.061 1.06l-1.591-1.59a.75.75 0 0 1 0-1.061Zm12.728 0a.75.75 0 0 1 0 1.06l-1.591 1.592a.75.75 0 0 1-1.06-1.061l1.59-1.591a.75.75 0 0 1 1.061 0Zm-6.816 4.496a.75.75 0 0 1 .82.311l5.228 7.917a.75.75 0 0 1-.777 1.148l-2.096-.382-1.965 3.678a.75.75 0 0 1-1.323 0l-1.965-3.678-2.096.382a.75.75 0 0 1-.777-1.148l5.228-7.917a.75.75 0 0 1 .723-.311ZM3 13.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 0 1.5h-3A.75.75 0 0 1 3 13.5Zm14.25-.75a.75.75 0 0 1 0 1.5h-3a.75.75 0 0 1 0-1.5h3Z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-white tracking-tight">
                            Lex<span class="text-indigo-400">Lanka</span>
                        </span>
                    </div>
                    <p class="text-sm text-slate-500 max-w-xs leading-relaxed">
                        Legal Practice Management System built for Sri Lankan law firms.
                    </p>
                </div>

                {{-- Footer links --}}
                <div class="grid grid-cols-2 gap-x-16 gap-y-3 text-sm">
                    <div class="space-y-3">
                        <p class="text-xs font-semibold text-slate-300 uppercase tracking-widest">Platform</p>
                        <a href="#features"      class="block hover:text-white transition-colors">Features</a>
                        <a href="#how-it-works"  class="block hover:text-white transition-colors">How It Works</a>
                        <a href="{{ route('login') }}" class="block hover:text-white transition-colors">Client Portal</a>
                    </div>
                    <div class="space-y-3">
                        <p class="text-xs font-semibold text-slate-300 uppercase tracking-widest">Legal</p>
                        <a href="#" class="block hover:text-white transition-colors">Privacy Policy</a>
                        <a href="#" class="block hover:text-white transition-colors">Terms of Service</a>
                        <a href="#" class="block hover:text-white transition-colors">Data Security</a>
                    </div>
                </div>
            </div>

            {{-- Bottom bar --}}
            <div class="pt-8 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-600">
                <p>&copy; {{ date('Y') }} LexLanka Legal Practice Management. All rights reserved.</p>
                <p>Built with Laravel 13 &middot; Tailwind CSS &middot; Alpine.js</p>
            </div>
        </div>
    </footer>

</body>
</html>
