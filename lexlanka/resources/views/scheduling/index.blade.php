<x-layouts.app>

    <x-slot name="header">Scheduling</x-slot>

    <x-slot name="actions">
        <a href="{{ route('scheduling.create') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Schedule Date
        </a>
    </x-slot>

    {{-- Filter bar --}}
    <div class="flex flex-wrap gap-3 mb-6">

        {{-- Upcoming / Past / All toggle --}}
        <div class="inline-flex rounded-lg border border-slate-200 bg-white overflow-hidden text-sm">
            @foreach (['upcoming' => 'Upcoming', 'past' => 'Past', 'all' => 'All'] as $key => $label)
                <a href="{{ route('scheduling.index', array_merge(request()->query(), ['view' => $key])) }}"
                   class="px-4 py-2 font-medium transition-colors
                          {{ ($view ?? 'upcoming') === $key
                              ? 'bg-indigo-600 text-white'
                              : 'text-slate-600 hover:bg-slate-50' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        {{-- Type filter --}}
        <select name="type"
                onchange="window.location = '{{ route('scheduling.index') }}?view={{ request('view','upcoming') }}&type=' + this.value"
                class="px-3 py-2 text-sm rounded-lg border border-slate-300 bg-white text-slate-700
                       focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="">All Types</option>
            <option value="calling_date" {{ request('type') === 'calling_date' ? 'selected' : '' }}>Calling Date</option>
            <option value="trial_date"   {{ request('type') === 'trial_date'   ? 'selected' : '' }}>Trial Date</option>
        </select>

        @if (request('type'))
            <a href="{{ route('scheduling.index', ['view' => request('view','upcoming')]) }}"
               class="btn-secondary self-stretch sm:self-auto flex items-center text-sm">
                Clear
            </a>
        @endif
    </div>

    {{-- Table --}}
    <div class="card p-0 overflow-hidden">
        @if ($courtDates->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-center text-slate-400">
                <svg class="w-12 h-12 mb-3 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-sm font-medium">
                    No {{ ($view ?? 'upcoming') !== 'all' ? ($view ?? 'upcoming') : '' }} court dates found.
                </p>
                <a href="{{ route('scheduling.create') }}" class="mt-3 btn-primary text-sm">
                    Schedule a date
                </a>
            </div>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Date & Time</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Type</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Case</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider hidden md:table-cell">Client</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider hidden lg:table-cell">Attorney</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider hidden lg:table-cell">Reminder</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($courtDates as $courtDate)
                        @php
                            $isPast = $courtDate->date->isPast();
                            $isToday = $courtDate->date->isToday();
                            $typeConfig = [
                                'calling_date' => ['bg-sky-100 text-sky-700',    'Calling Date'],
                                'trial_date'   => ['bg-rose-100 text-rose-700',  'Trial Date'],
                            ];
                            [$typeBadge, $typeLabel] = $typeConfig[$courtDate->type] ?? ['bg-slate-100 text-slate-600', ucfirst($courtDate->type)];
                        @endphp
                        <tr class="transition-colors {{ $isPast ? 'bg-slate-50/60 hover:bg-slate-100/60' : 'hover:bg-slate-50' }}">

                            {{-- Date & Time --}}
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    {{-- Mini calendar badge --}}
                                    <div class="shrink-0 w-10 h-10 rounded-lg flex flex-col items-center
                                                justify-center text-xs font-bold leading-tight
                                                {{ $isToday ? 'bg-indigo-600 text-white'
                                                   : ($isPast ? 'bg-slate-200 text-slate-500'
                                                              : 'bg-indigo-50 text-indigo-700') }}">
                                        <span class="uppercase text-[10px]">{{ $courtDate->date->format('M') }}</span>
                                        <span class="text-sm">{{ $courtDate->date->format('d') }}</span>
                                    </div>
                                    <div>
                                        <p class="font-medium {{ $isPast ? 'text-slate-400' : 'text-slate-800' }}">
                                            {{ $courtDate->date->format('D, d M Y') }}
                                        </p>
                                        <p class="text-xs text-slate-400">{{ $courtDate->date->format('g:i A') }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Type badge --}}
                            <td class="px-5 py-3.5">
                                <span class="inline-block text-xs font-semibold px-2.5 py-0.5 rounded-full {{ $typeBadge }}">
                                    {{ $typeLabel }}
                                </span>
                            </td>

                            {{-- Case --}}
                            <td class="px-5 py-3.5">
                                <a href="{{ route('cases.show', $courtDate->legalCase) }}"
                                   class="text-indigo-600 font-medium hover:underline text-sm">
                                    Case #{{ $courtDate->legalCase->id }}
                                </a>
                                <p class="text-xs text-slate-400">{{ $courtDate->legalCase->case_type }}</p>
                            </td>

                            {{-- Client --}}
                            <td class="px-5 py-3.5 hidden md:table-cell">
                                <a href="{{ route('clients.show', $courtDate->legalCase->client) }}"
                                   class="text-slate-700 hover:text-indigo-600 transition-colors font-medium text-sm">
                                    {{ $courtDate->legalCase->client->name ?? '—' }}
                                </a>
                            </td>

                            {{-- Attorney --}}
                            <td class="px-5 py-3.5 hidden lg:table-cell">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 text-xs
                                                font-bold flex items-center justify-center shrink-0">
                                        {{ strtoupper(substr($courtDate->legalCase->assignedAttorney->name ?? '?', 0, 1)) }}
                                    </div>
                                    <span class="text-slate-600 text-sm">
                                        {{ $courtDate->legalCase->assignedAttorney->name ?? '—' }}
                                    </span>
                                </div>
                            </td>

                            {{-- Reminder status --}}
                            <td class="px-5 py-3.5 text-center hidden lg:table-cell">
                                @if ($courtDate->reminder_sent)
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-emerald-700">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Sent
                                    </span>
                                @else
                                    <span class="text-xs text-slate-400">Pending</span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                <a href="{{ route('scheduling.edit', $courtDate) }}"
                                   class="text-slate-400 hover:text-indigo-600 transition-colors mr-3 text-xs font-medium">
                                    Edit
                                </a>
                                <form method="POST"
                                      action="{{ route('scheduling.destroy', $courtDate) }}"
                                      class="inline-block"
                                      x-data
                                      @submit.prevent="
                                          if(confirm('Remove this court date? This cannot be undone.')) $el.submit()
                                      ">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-slate-400 hover:text-red-600 transition-colors text-xs font-medium">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Pagination --}}
            @if ($courtDates->hasPages())
                <div class="px-5 py-4 border-t border-slate-200">
                    {{ $courtDates->links() }}
                </div>
            @endif
        @endif
    </div>

</x-layouts.app>
