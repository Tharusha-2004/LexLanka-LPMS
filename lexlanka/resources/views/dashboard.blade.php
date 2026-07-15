<x-layouts.app>

    {{-- ── Page header ──────────────────────────────────────────────── --}}
    <x-slot name="header">Dashboard</x-slot>

    <x-slot name="actions">
        <a href="{{ route('cases.index') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Case
        </a>
    </x-slot>

    {{-- ── Summary stat cards ───────────────────────────────────────── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">

        {{-- Total Cases --}}
        <div class="card group hover:shadow-md transition-shadow duration-200">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Cases</p>
                    <p class="mt-2 text-4xl font-bold text-slate-900">{{ $totalCases ?? 0 }}</p>
                    <p class="mt-1 text-sm text-slate-500">across all statuses</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center
                            group-hover:bg-indigo-100 transition-colors duration-200">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-500">
                    <span class="font-semibold text-emerald-600">{{ $activeCasesCount ?? 0 }}</span> active
                </span>
                <a href="{{ \Route::has('cases.index') ? route('cases.index') : '#' }}"
                   class="text-indigo-600 font-semibold hover:underline">View all →</a>
            </div>
        </div>

        {{-- Upcoming Court Dates --}}
        <div class="card group hover:shadow-md transition-shadow duration-200">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Upcoming Court Dates</p>
                    <p class="mt-2 text-4xl font-bold text-slate-900">{{ $upcomingCourtDates ?? 0 }}</p>
                    <p class="mt-1 text-sm text-slate-500">in the next 30 days</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center
                            group-hover:bg-amber-100 transition-colors duration-200">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-500">
                    <span class="font-semibold text-amber-600">{{ $todayCourtDates ?? 0 }}</span> today
                </span>
                <a href="{{ \Route::has('scheduling.index') ? route('scheduling.index') : '#' }}"
                   class="text-indigo-600 font-semibold hover:underline">View schedule →</a>
            </div>
        </div>

        {{-- Active Clients --}}
        <div class="card group hover:shadow-md transition-shadow duration-200">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Active Clients</p>
                    <p class="mt-2 text-4xl font-bold text-slate-900">{{ $activeClients ?? 0 }}</p>
                    <p class="mt-1 text-sm text-slate-500">with open matters</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center
                            group-hover:bg-emerald-100 transition-colors duration-200">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-500">
                    <span class="font-semibold text-slate-700">{{ $totalClients ?? 0 }}</span> total registered
                </span>
                <a href="{{ \Route::has('clients.index') ? route('clients.index') : '#' }}"
                   class="text-indigo-600 font-semibold hover:underline">View all →</a>
            </div>
        </div>

    </div>

    {{-- ── Two-column lower section ────────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Recent Cases table --}}
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base font-semibold text-slate-800">Recent Cases</h2>
                <a href="{{ \Route::has('cases.index') ? route('cases.index') : '#' }}"
                   class="text-xs text-indigo-600 font-semibold hover:underline">See all</a>
            </div>

            @if(isset($recentCases) && $recentCases->count())
                <div class="divide-y divide-slate-100">
                    @foreach ($recentCases as $case)
                        <div class="py-3 flex items-center justify-between gap-4">
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-slate-800 truncate">
                                    {{ $case->client->name ?? '—' }}
                                </p>
                                <p class="text-xs text-slate-400 truncate">{{ $case->case_type ?? 'General' }}</p>
                            </div>
                            @php
                                $statusColors = [
                                    'pending'            => 'bg-slate-100 text-slate-600',
                                    'active'             => 'bg-indigo-100 text-indigo-700',
                                    'trial_scheduled'    => 'bg-amber-100 text-amber-700',
                                    'judgment_delivered' => 'bg-purple-100 text-purple-700',
                                    'case_closed'        => 'bg-emerald-100 text-emerald-700',
                                ];
                                $color = $statusColors[$case->status] ?? 'bg-slate-100 text-slate-600';
                            @endphp
                            <span class="inline-block shrink-0 text-xs font-semibold px-2 py-0.5 rounded-full {{ $color }} capitalize">
                                {{ str_replace('_', ' ', $case->status) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-10 text-center text-slate-400">
                    <svg class="w-10 h-10 mb-2 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-sm">No cases yet.</p>
                </div>
            @endif
        </div>

        {{-- Upcoming court dates list --}}
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base font-semibold text-slate-800">Upcoming Hearings</h2>
                <a href="{{ \Route::has('scheduling.index') ? route('scheduling.index') : '#' }}"
                   class="text-xs text-indigo-600 font-semibold hover:underline">Full calendar</a>
            </div>

            @if(isset($nextCourtDates) && $nextCourtDates->count())
                <div class="divide-y divide-slate-100">
                    @foreach ($nextCourtDates as $hearing)
                        <div class="py-3 flex items-start gap-4">
                            <div class="shrink-0 w-10 h-10 rounded-lg bg-indigo-50 flex flex-col items-center justify-center
                                        text-indigo-700 leading-tight">
                                <span class="text-xs font-bold uppercase">{{ $hearing->date->format('M') }}</span>
                                <span class="text-sm font-extrabold">{{ $hearing->date->format('d') }}</span>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-slate-800 truncate">
                                    {{ $hearing->legalCase->client->name ?? '—' }}
                                </p>
                                <p class="text-xs text-slate-400 capitalize">
                                    {{ str_replace('_', ' ', $hearing->type) }} ·
                                    {{ $hearing->date->format('g:i A') }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-10 text-center text-slate-400">
                    <svg class="w-10 h-10 mb-2 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-sm">No hearings scheduled.</p>
                </div>
            @endif
        </div>

    </div>

</x-layouts.app>
