<x-layouts.app>

    <x-slot name="header">Clients</x-slot>

    <x-slot name="actions">
        <a href="{{ route('clients.create') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Client
        </a>
    </x-slot>

    {{-- Search bar --}}
    <form method="GET" action="{{ route('clients.index') }}" class="mb-6">
        <div class="relative max-w-sm">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Search by name, NIC, email…"
                   class="w-full pl-9 pr-4 py-2 text-sm rounded-lg border border-slate-300 bg-white
                          text-slate-900 placeholder-slate-400
                          focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
        </div>
    </form>

    {{-- Results table --}}
    <div class="card p-0 overflow-hidden">
        @if ($clients->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-center text-slate-400">
                <svg class="w-12 h-12 mb-3 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                @if (request('search'))
                    <p class="text-sm font-medium">No clients match "<span class="text-slate-600">{{ request('search') }}</span>".</p>
                    <a href="{{ route('clients.index') }}" class="mt-2 text-indigo-600 text-sm hover:underline">Clear search</a>
                @else
                    <p class="text-sm font-medium">No clients yet.</p>
                    <a href="{{ route('clients.create') }}" class="mt-2 btn-primary text-sm">Add your first client</a>
                @endif
            </div>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Name</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">NIC</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider hidden md:table-cell">Contact</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider hidden lg:table-cell">Intake Date</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Cases</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($clients as $client)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-3.5 font-medium text-slate-900">
                                <a href="{{ route('clients.show', $client) }}"
                                   class="hover:text-indigo-600 transition-colors">
                                    {{ $client->name }}
                                </a>
                            </td>
                            <td class="px-5 py-3.5 text-slate-500 font-mono text-xs">{{ $client->nic }}</td>
                            <td class="px-5 py-3.5 text-slate-500 hidden md:table-cell">
                                <div>{{ $client->phone ?? '—' }}</div>
                                <div class="text-xs text-slate-400">{{ $client->email ?? '' }}</div>
                            </td>
                            <td class="px-5 py-3.5 text-slate-500 hidden lg:table-cell">
                                {{ $client->intake_date->format('d M Y') }}
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold
                                    {{ $client->legal_cases_count > 0 ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $client->legal_cases_count }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                <a href="{{ route('clients.edit', $client) }}"
                                   class="text-slate-400 hover:text-indigo-600 transition-colors mr-3 text-xs font-medium">
                                    Edit
                                </a>
                                <form method="POST"
                                      action="{{ route('clients.destroy', $client) }}"
                                      class="inline-block"
                                      x-data
                                      @submit.prevent="
                                          if(confirm('Delete {{ addslashes($client->name) }}? This cannot be undone.')) $el.submit()
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
            @if ($clients->hasPages())
                <div class="px-5 py-4 border-t border-slate-200">
                    {{ $clients->links() }}
                </div>
            @endif
        @endif
    </div>

</x-layouts.app>
