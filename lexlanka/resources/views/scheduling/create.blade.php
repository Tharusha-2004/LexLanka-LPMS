<x-layouts.app>

    <x-slot name="header">Schedule Court Date</x-slot>

    <x-slot name="breadcrumbs">
        <a href="{{ route('scheduling.index') }}" class="hover:text-indigo-600">Scheduling</a>
        <span>/</span>
        <span class="text-slate-600">New</span>
    </x-slot>

    <div class="max-w-2xl">
        <div class="card">
            <form method="POST" action="{{ route('scheduling.store') }}" novalidate>
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
                                       text-slate-900
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                            <option value="">— Select a case —</option>
                            @foreach ($cases as $case)
                                <option value="{{ $case->id }}"
                                    {{ old('case_id', $preselectedCaseId) == $case->id ? 'selected' : '' }}>
                                    Case #{{ $case->id }}
                                    @if ($case->client)
                                        — {{ $case->client->name }}
                                    @endif
                                    ({{ $case->case_type }})
                                </option>
                            @endforeach
                        </select>
                        @error('case_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Date & Time --}}
                    <div>
                        <label for="date" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Date & Time <span class="text-red-500">*</span>
                        </label>
                        <input id="date"
                               type="datetime-local"
                               name="date"
                               value="{{ old('date') }}"
                               min="{{ now()->addMinutes(5)->format('Y-m-d\TH:i') }}"
                               class="w-full px-3.5 py-2.5 text-sm rounded-lg border
                                      {{ $errors->has('date') ? 'border-red-400 bg-red-50' : 'border-slate-300 bg-white' }}
                                      text-slate-900
                                      focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        @error('date')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-slate-400">Must be a future date and time.</p>
                    </div>

                    {{-- Type --}}
                    <div>
                        <label for="type" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Date Type <span class="text-red-500">*</span>
                        </label>
                        <select id="type" name="type"
                                class="w-full px-3.5 py-2.5 text-sm rounded-lg border
                                       {{ $errors->has('type') ? 'border-red-400 bg-red-50' : 'border-slate-300 bg-white' }}
                                       text-slate-900
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                            <option value="">— Select type —</option>
                            @foreach ($typeOptions as $value => $label)
                                <option value="{{ $value }}"
                                    {{ old('type') === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('type')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Info box --}}
                    <div class="flex items-start gap-3 p-3.5 rounded-lg bg-indigo-50 border border-indigo-100">
                        <svg class="w-4 h-4 text-indigo-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-xs text-indigo-700">
                            <strong>Calling Date</strong> — A preliminary hearing for procedural matters.<br>
                            <strong>Trial Date</strong> — The full trial or substantive hearing.
                        </p>
                    </div>

                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3 mt-7 pt-6 border-t border-slate-200">
                    <button type="submit" class="btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Confirm Date
                    </button>
                    <a href="{{ route('scheduling.index') }}" class="btn-secondary">Cancel</a>
                </div>

            </form>
        </div>
    </div>

</x-layouts.app>
