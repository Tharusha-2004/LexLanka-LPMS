<x-layouts.app>

    <x-slot name="header">{{ $client->name }}</x-slot>

    <x-slot name="breadcrumbs">
        <a href="{{ route('clients.index') }}" class="hover:text-indigo-600">Clients</a>
        <span>/</span>
        <span class="text-slate-600">{{ $client->name }}</span>
    </x-slot>

    <x-slot name="actions">
        <a href="{{ route('clients.edit', $client) }}" class="btn-secondary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit
        </a>
        <form method="POST"
              action="{{ route('clients.destroy', $client) }}"
              x-data
              @submit.prevent="
                  if(confirm('Permanently delete {{ addslashes($client->name) }}? This cannot be undone.')) $el.submit()
              ">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-secondary text-red-600 border-red-200 hover:bg-red-50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Delete
            </button>
        </form>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left: client profile card --}}
        <div class="lg:col-span-1 space-y-4">
            <div class="card">
                {{-- Avatar --}}
                <div class="flex items-center gap-4 mb-5">
                    <div class="w-14 h-14 rounded-full bg-indigo-100 text-indigo-700 font-bold text-xl
                                flex items-center justify-center shrink-0">
                        {{ strtoupper(substr($client->name, 0, 2)) }}
                    </div>
                    <div class="min-w-0">
                        <h2 class="text-base font-semibold text-slate-900 truncate">{{ $client->name }}</h2>
                        <p class="text-xs text-slate-400">Client since {{ $client->intake_date->format('d M Y') }}</p>
                    </div>
                </div>

                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-slate-400 font-medium">NIC</dt>
                        <dd class="font-mono text-slate-700 text-xs">{{ $client->nic }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-400 font-medium">Phone</dt>
                        <dd class="text-slate-700">{{ $client->phone ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-400 font-medium">Email</dt>
                        <dd class="text-slate-700 truncate max-w-[160px]">{{ $client->email ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between pt-3 border-t border-slate-100">
                        <dt class="text-slate-400 font-medium">Total Cases</dt>
                        <dd class="font-bold text-indigo-600">{{ $client->legal_cases_count }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- Right: case history --}}
        <div class="lg:col-span-2">
            <div class="card p-0 overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200">
                    <h3 class="text-sm font-semibold text-slate-800">Case History</h3>
                    @if (\Route::has('cases.create'))
                        <a href="{{ route('cases.create', ['client_id' => $client->id]) }}"
                           class="btn-primary text-xs px-3 py-1.5">
                            + New Case
                        </a>
                    @endif
                </div>

                @if ($cases->isEmpty())
                    <div class="flex flex-col items-center justify-center py-12 text-slate-400 text-center">
                        <svg class="w-10 h-10 mb-2 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-sm">No cases on file yet.</p>
                    </div>
                @else
                    <div class="divide-y divide-slate-100">
                        @foreach ($cases as $case)
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
                            <div class="px-5 py-3.5 flex items-center justify-between gap-4">
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-slate-800">
                                        {{ $case->case_type ?? 'General Matter' }}
                                    </p>
                                    <p class="text-xs text-slate-400">
                                        Attorney: {{ $case->assignedAttorney->name ?? '—' }}
                                        · Filed {{ $case->created_at->format('d M Y') }}
                                    </p>
                                </div>
                                <span class="shrink-0 inline-block text-xs font-semibold px-2.5 py-0.5 rounded-full {{ $color }} capitalize">
                                    {{ str_replace('_', ' ', $case->status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

    </div>

</x-layouts.app>
