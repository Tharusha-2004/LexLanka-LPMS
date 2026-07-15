<x-layouts.app>

    <x-slot name="header">Case #{{ $case->id }}</x-slot>

    <x-slot name="breadcrumbs">
        <a href="{{ route('cases.index') }}" class="hover:text-indigo-600">Cases</a>
        <span>/</span>
        <span class="text-slate-600">Case #{{ $case->id }}</span>
    </x-slot>

    <x-slot name="actions">
        <a href="{{ route('cases.edit', $case) }}" class="btn-secondary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit Case
        </a>
        <form method="POST" action="{{ route('cases.destroy', $case) }}"
              x-data @submit.prevent="if(confirm('Delete Case #{{ $case->id }}? This cannot be undone.')) $el.submit()">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-secondary text-red-600 border-red-200 hover:bg-red-50">
                Delete
            </button>
        </form>
    </x-slot>

    @php
        $statusConfig = [
            'pending'            => ['bg-slate-100 text-slate-600',    'Pending'],
            'active'             => ['bg-indigo-100 text-indigo-700',  'Active'],
            'trial_scheduled'    => ['bg-amber-100 text-amber-700',    'Trial Scheduled'],
            'judgment_delivered' => ['bg-purple-100 text-purple-700',  'Judgment Delivered'],
            'case_closed'        => ['bg-emerald-100 text-emerald-700','Case Closed'],
        ];
        [$badge, $statusLabel] = $statusConfig[$case->status] ?? ['bg-slate-100 text-slate-600', $case->status];
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left: Case summary --}}
        <div class="space-y-4">

            {{-- Summary card --}}
            <div class="card">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-semibold text-slate-700">Case Details</h2>
                    <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full {{ $badge }}">
                        {{ $statusLabel }}
                    </span>
                </div>
                <dl class="space-y-3 text-sm">
                    <div>
                        <dt class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-0.5">Client</dt>
                        <dd>
                            <a href="{{ route('clients.show', $case->client) }}"
                               class="font-medium text-indigo-600 hover:underline">
                                {{ $case->client->name }}
                            </a>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-0.5">Case Type</dt>
                        <dd class="font-medium text-slate-800">{{ $case->case_type }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-0.5">Assigned Attorney</dt>
                        <dd class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold flex items-center justify-center">
                                {{ strtoupper(substr($case->assignedAttorney->name ?? '?', 0, 1)) }}
                            </div>
                            <span class="text-slate-700">{{ $case->assignedAttorney->name ?? '—' }}</span>
                        </dd>
                    </div>
                    <div class="pt-2 border-t border-slate-100">
                        <dt class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-0.5">Opened</dt>
                        <dd class="text-slate-600">{{ $case->created_at->format('d F Y') }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Quick stats --}}
            <div class="grid grid-cols-3 gap-3">
                <div class="card text-center py-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $case->courtDates->count() }}</p>
                    <p class="text-xs text-slate-400 mt-0.5">Hearings</p>
                </div>
                <div class="card text-center py-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $case->documents->count() }}</p>
                    <p class="text-xs text-slate-400 mt-0.5">Documents</p>
                </div>
                <div class="card text-center py-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $case->ledgerEntries->count() }}</p>
                    <p class="text-xs text-slate-400 mt-0.5">Entries</p>
                </div>
            </div>
        </div>

        {{-- Right: Court dates timeline --}}
        <div class="lg:col-span-2 space-y-4">

            {{-- Court Dates --}}
            <div class="card p-0 overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200">
                    <h3 class="text-sm font-semibold text-slate-800">Court Dates</h3>
                    {{-- Link to scheduling create (will be built later) --}}
                    <span class="text-xs text-slate-400">{{ $case->courtDates->count() }} scheduled</span>
                </div>

                @if ($case->courtDates->isEmpty())
                    <div class="py-10 text-center text-slate-400">
                        <p class="text-sm">No court dates scheduled.</p>
                    </div>
                @else
                    <div class="divide-y divide-slate-100">
                        @foreach ($case->courtDates as $courtDate)
                            <div class="px-5 py-3.5 flex items-start gap-4">
                                <div class="shrink-0 w-10 h-10 rounded-lg
                                            {{ $courtDate->date->isPast() ? 'bg-slate-100 text-slate-500' : 'bg-indigo-50 text-indigo-700' }}
                                            flex flex-col items-center justify-center text-xs font-bold leading-tight">
                                    <span class="uppercase text-[10px]">{{ $courtDate->date->format('M') }}</span>
                                    <span>{{ $courtDate->date->format('d') }}</span>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-slate-800 capitalize">
                                        {{ str_replace('_', ' ', $courtDate->type) }}
                                    </p>
                                    <p class="text-xs text-slate-400">
                                        {{ $courtDate->date->format('l, d F Y — g:i A') }}
                                        @if ($courtDate->reminder_sent)
                                            · <span class="text-emerald-600 font-medium">Reminder sent</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Documents (preview) --}}
            @if ($case->documents->isNotEmpty())
                <div class="card p-0 overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-200">
                        <h3 class="text-sm font-semibold text-slate-800">
                            Documents ({{ $case->documents->count() }})
                        </h3>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @foreach ($case->documents->take(5) as $doc)
                            <div class="px-5 py-3 flex items-center gap-3">
                                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <div class="min-w-0">
                                    <p class="text-sm text-slate-700 truncate">{{ basename($doc->file_path) }}</p>
                                    <p class="text-xs text-slate-400 capitalize">{{ $doc->category }} · {{ strtoupper($doc->file_type) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>

</x-layouts.app>
