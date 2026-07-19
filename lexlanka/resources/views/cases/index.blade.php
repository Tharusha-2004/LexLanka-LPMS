<x-layouts.app>

    <x-slot name="header">Legal Cases</x-slot>

    <x-slot name="actions">
        <a href="{{ route('cases.create') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Case
        </a>
    </x-slot>

    {{-- Filters row --}}
    <form method="GET" action="{{ route('cases.index') }}"
          class="flex flex-col sm:flex-row gap-3 mb-6">

        {{-- Text search --}}
        <div class="relative flex-1 max-w-sm">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="search"
                   value="{{ request('search') }}"
                   placeholder="Search by client or case type…"
                   class="w-full pl-9 pr-4 py-2 text-sm rounded-lg border border-slate-300 bg-white
                          text-slate-900 placeholder-slate-400
                          focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
        </div>

        {{-- Status filter --}}
        <select name="status"
                onchange="this.form.submit()"
                class="px-3 py-2 text-sm rounded-lg border border-slate-300 bg-white text-slate-700
                       focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            <option value="">All Statuses</option>
            @foreach ($statusOptions as $value => $label)
                <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>

        @if (request('search') || request('status'))
            <a href="{{ route('cases.index') }}"
               class="btn-secondary self-stretch sm:self-auto flex items-center">
                Clear
            </a>
        @endif
    </form>

    {{-- Table --}}
    <div class="card p-0 overflow-hidden">
        @if ($cases->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-center text-slate-400">
                <svg class="w-12 h-12 mb-3 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                @if (request('search') || request('status'))
                    <p class="text-sm font-medium">No cases match your filters.</p>
                    <a href="{{ route('cases.index') }}" class="mt-2 text-indigo-600 text-sm hover:underline">Clear filters</a>
                @else
                    <p class="text-sm font-medium">No cases yet.</p>
                    <a href="{{ route('cases.create') }}" class="mt-3 btn-primary text-sm">Open first case</a>
                @endif
            </div>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider w-16">#</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Client</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider hidden md:table-cell">Case Type</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider hidden lg:table-cell">Attorney</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider hidden lg:table-cell">Opened</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($cases as $case)
                        @php
                            $statusConfig = [
                                'pending'            => ['bg-slate-100 text-slate-600',    'Pending'],
                                'active'             => ['bg-indigo-100 text-indigo-700',  'Active'],
                                'trial_scheduled'    => ['bg-amber-100 text-amber-700',    'Trial Scheduled'],
                                'judgment_delivered' => ['bg-purple-100 text-purple-700',  'Judgment Delivered'],
                                'case_closed'        => ['bg-emerald-100 text-emerald-700','Case Closed'],
                            ];
                            [$badge, $label] = $statusConfig[$case->status] ?? ['bg-slate-100 text-slate-600', ucfirst($case->status)];
                        @endphp
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-3.5 text-slate-400 font-mono text-xs">
                                #{{ $case->id }}
                            </td>
                            <td class="px-5 py-3.5 font-medium text-slate-900">
                                <a href="{{ route('cases.show', $case) }}"
                                   class="hover:text-indigo-600 transition-colors">
                                    {{ $case->client->name ?? '—' }}
                                </a>
                                <p class="text-xs text-slate-400 md:hidden">{{ $case->case_type }}</p>
                            </td>
                            <td class="px-5 py-3.5 text-slate-600 hidden md:table-cell">
                                {{ $case->case_type }}
                            </td>
                            <td class="px-5 py-3.5 hidden lg:table-cell">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold flex items-center justify-center shrink-0">
                                        {{ strtoupper(substr($case->assignedAttorney->name ?? '?', 0, 1)) }}
                                    </div>
                                    <span class="text-slate-600 text-sm">{{ $case->assignedAttorney->name ?? '—' }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="inline-block text-xs font-semibold px-2.5 py-0.5 rounded-full {{ $badge }}">
                                    {{ $label }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-slate-400 text-xs hidden lg:table-cell">
                                {{ $case->created_at->format('d M Y') }}
                            </td>
                            
                            {{-- UPDATED ACTIONS COLUMN WITH PDF BUTTON --}}
                            <td class="px-5 py-3.5 text-right whitespace-nowrap flex items-center justify-end">
                                <a href="{{ route('cases.show', $case) }}"
                                   class="text-slate-400 hover:text-indigo-600 transition-colors mr-3 text-xs font-medium">
                                    View
                                </a>
                                <a href="{{ route('cases.edit', $case) }}"
                                   class="text-slate-400 hover:text-indigo-600 transition-colors mr-4 text-xs font-medium">
                                    Edit
                                </a>
                                
                                <a href="{{ url('/cases/' . $case->id . '/export-brief') }}" 
                                   class="inline-flex items-center px-2.5 py-1.5 bg-white border border-slate-300 rounded text-slate-700 hover:bg-slate-50 hover:text-indigo-600 transition-colors text-xs font-medium shadow-sm">
                                    <svg class="w-3.5 h-3.5 mr-1.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    PDF
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Pagination --}}
            @if ($cases->hasPages())
                <div class="px-5 py-4 border-t border-slate-200">
                    {{ $cases->links() }}
                </div>
            @endif
        @endif
    </div>

</x-layouts.app>