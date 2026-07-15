<x-layouts.app>

    <x-slot name="header">Record Ledger Entry</x-slot>

    <x-slot name="breadcrumbs">
        <a href="{{ route('billing.index') }}" class="hover:text-indigo-600">Billing</a>
        <span>/</span>
        <span class="text-slate-600">New Transaction</span>
    </x-slot>

    <div class="max-w-2xl">
        <div class="card p-6 rounded-xl border border-slate-200 shadow-sm bg-white">
            <form method="POST" action="{{ route('ledger-entries.store') }}" novalidate>
                @csrf

                <div class="space-y-5">
                    {{-- Case --}}
                    <div>
                        <label for="case_id" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Case <span class="text-red-500">*</span>
                        </label>
                        <select id="case_id" name="case_id"
                                class="w-full px-3.5 py-2.5 text-sm rounded-lg border
                                       {{ $errors->has('case_id') ? 'border-red-400 bg-red-50' : 'border-slate-300 bg-white' }}
                                       text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">— Select a case —</option>
                            @foreach ($cases as $case)
                                <option value="{{ $case->id }}" {{ old('case_id') == $case->id ? 'selected' : '' }}>
                                    Case #{{ $case->id }} — {{ $case->client->name ?? 'Unknown' }} ({{ $case->case_type }})
                                </option>
                            @endforeach
                        </select>
                        @error('case_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Type --}}
                    <div>
                        <label for="type" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Ledger Type <span class="text-red-500">*</span>
                        </label>
                        <select id="type" name="type"
                                class="w-full px-3.5 py-2.5 text-sm rounded-lg border
                                       {{ $errors->has('type') ? 'border-red-400 bg-red-50' : 'border-slate-300 bg-white' }}
                                       text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">— Select type —</option>
                            <option value="trust" {{ old('type') === 'trust' ? 'selected' : '' }}>Trust</option>
                            <option value="operational" {{ old('type') === 'operational' ? 'selected' : '' }}>Operational</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Amount --}}
                    <div>
                        <label for="amount" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Amount ($) <span class="text-red-500">*</span>
                        </label>
                        <input id="amount" type="number" step="0.01" name="amount"
                               value="{{ old('amount') }}"
                               placeholder="e.g. 500.00"
                               class="w-full px-3.5 py-2.5 text-sm rounded-lg border
                                      {{ $errors->has('amount') ? 'border-red-400 bg-red-50' : 'border-slate-300 bg-white' }}
                                      text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('amount')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="description" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Description <span class="text-red-500">*</span>
                        </label>
                        <textarea id="description" name="description" rows="3"
                                  placeholder="Reason for this transaction..."
                                  class="w-full px-3.5 py-2.5 text-sm rounded-lg border
                                         {{ $errors->has('description') ? 'border-red-400 bg-red-50' : 'border-slate-300 bg-white' }}
                                         text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3 mt-7 pt-6 border-t border-slate-200">
                    <button type="submit" class="btn-primary">
                        Record Entry
                    </button>
                    <a href="{{ route('billing.index') }}" class="btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

</x-layouts.app>
