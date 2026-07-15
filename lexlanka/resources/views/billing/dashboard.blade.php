<x-layouts.app>

    <x-slot name="header">Financial Dashboard</x-slot>

    <x-slot name="actions">
        <a href="{{ route('ledger-entries.create') }}" class="btn-primary">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Transaction
        </a>
    </x-slot>

    {{-- Top Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="card bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
            <p class="text-sm font-medium text-slate-500 mb-1">Total Cases</p>
            <p class="text-3xl font-bold text-slate-900">{{ number_format($totalCases) }}</p>
        </div>
        <div class="card bg-white p-6 rounded-xl border border-emerald-200 shadow-sm bg-emerald-50/30">
            <p class="text-sm font-medium text-emerald-600 mb-1">Total Operational Revenue</p>
            <p class="text-3xl font-bold text-emerald-700">${{ number_format($totalOperational, 2) }}</p>
        </div>
        <div class="card bg-white p-6 rounded-xl border border-blue-200 shadow-sm bg-blue-50/30">
            <p class="text-sm font-medium text-blue-600 mb-1">Total Client Trust Held</p>
            <p class="text-3xl font-bold text-blue-700">${{ number_format($totalTrust, 2) }}</p>
        </div>
    </div>

    {{-- Data Table --}}
    <div class="card p-0 overflow-hidden border border-slate-200 shadow-sm rounded-xl bg-white">
        <div class="px-5 py-4 border-b border-slate-200 bg-slate-50">
            <h3 class="text-lg font-semibold text-slate-800">Case Billing Summary</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-100 border-b border-slate-200">
                        <th class="px-5 py-3 text-left font-semibold text-slate-600 uppercase tracking-wider text-xs">Case Name</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-600 uppercase tracking-wider text-xs">Attorney</th>
                        <th class="px-5 py-3 text-center font-semibold text-slate-600 uppercase tracking-wider text-xs">Trial Dates</th>
                        <th class="px-5 py-3 text-right font-semibold text-slate-600 uppercase tracking-wider text-xs">Appearance Fee</th>
                        <th class="px-5 py-3 text-right font-semibold text-slate-600 uppercase tracking-wider text-xs">Trust Balance</th>
                        <th class="px-5 py-3 text-right font-semibold text-slate-600 uppercase tracking-wider text-xs">Operational Balance</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($casesData as $data)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-3.5">
                                <a href="{{ route('cases.show', $data['case']) }}" class="text-indigo-600 font-medium hover:underline">
                                    Case #{{ $data['case']->id }}
                                </a>
                                <p class="text-xs text-slate-500">{{ $data['case']->client->name ?? 'Unknown Client' }}</p>
                            </td>
                            <td class="px-5 py-3.5 text-slate-700">
                                {{ $data['case']->assignedAttorney->name ?? 'Unassigned' }}
                            </td>
                            <td class="px-5 py-3.5 text-center text-slate-600">
                                {{ $data['case']->courtDates->where('type', 'trial_date')->count() }}
                            </td>
                            <td class="px-5 py-3.5 text-right font-medium text-slate-700">
                                ${{ number_format($data['appearance_fee'], 2) }}
                            </td>
                            <td class="px-5 py-3.5 text-right font-medium text-blue-600">
                                ${{ number_format($data['trust_balance'], 2) }}
                            </td>
                            <td class="px-5 py-3.5 text-right font-medium text-emerald-600">
                                ${{ number_format($data['operational_balance'], 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-slate-400">
                                No cases found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-layouts.app>
