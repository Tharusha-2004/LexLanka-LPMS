<x-layouts.app>

    <x-slot name="header">Edit Case #{{ $case->id }}</x-slot>

    <x-slot name="breadcrumbs">
        <a href="{{ route('cases.index') }}" class="hover:text-indigo-600">Cases</a>
        <span>/</span>
        <a href="{{ route('cases.show', $case) }}" class="hover:text-indigo-600">Case #{{ $case->id }}</a>
        <span>/</span>
        <span class="text-slate-600">Edit</span>
    </x-slot>

    <div class="max-w-2xl">
        <div class="card">
            <form method="POST" action="{{ route('cases.update', $case) }}" novalidate>
                @csrf
                @method('PUT')

                <div class="space-y-5">

                    {{-- Client --}}
                    <div>
                        <label for="client_id" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Client <span class="text-red-500">*</span>
                        </label>
                        <select id="client_id" name="client_id"
                                class="w-full px-3.5 py-2.5 text-sm rounded-lg border
                                       {{ $errors->has('client_id') ? 'border-red-400 bg-red-50' : 'border-slate-300 bg-white' }}
                                       text-slate-900
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                            <option value="">— Select a client —</option>
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}"
                                    {{ old('client_id', $case->client_id) == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Assigned Attorney --}}
                    <div>
                        <label for="assigned_attorney_id" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Assigned Attorney <span class="text-red-500">*</span>
                        </label>
                        <select id="assigned_attorney_id" name="assigned_attorney_id"
                                class="w-full px-3.5 py-2.5 text-sm rounded-lg border
                                       {{ $errors->has('assigned_attorney_id') ? 'border-red-400 bg-red-50' : 'border-slate-300 bg-white' }}
                                       text-slate-900
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                            <option value="">— Select an attorney —</option>
                            @foreach ($attorneys as $attorney)
                                <option value="{{ $attorney->id }}"
                                    {{ old('assigned_attorney_id', $case->assigned_attorney_id) == $attorney->id ? 'selected' : '' }}>
                                    {{ $attorney->name }} ({{ ucfirst($attorney->role) }})
                                </option>
                            @endforeach
                        </select>
                        @error('assigned_attorney_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Case Type --}}
                    <div>
                        <label for="case_type" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Case Type <span class="text-red-500">*</span>
                        </label>
                        <input id="case_type" type="text" name="case_type"
                               value="{{ old('case_type', $case->case_type) }}"
                               class="w-full px-3.5 py-2.5 text-sm rounded-lg border
                                      {{ $errors->has('case_type') ? 'border-red-400 bg-red-50' : 'border-slate-300 bg-white' }}
                                      text-slate-900 placeholder-slate-400
                                      focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        @error('case_type')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div>
                        <label for="status" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select id="status" name="status"
                                class="w-full px-3.5 py-2.5 text-sm rounded-lg border
                                       {{ $errors->has('status') ? 'border-red-400 bg-red-50' : 'border-slate-300 bg-white' }}
                                       text-slate-900
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                            @foreach ($statusOptions as $value => $label)
                                <option value="{{ $value }}"
                                    {{ old('status', $case->status) === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <div class="flex items-center gap-3 mt-7 pt-6 border-t border-slate-200">
                    <button type="submit" class="btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Update Case
                    </button>
                    <a href="{{ route('cases.show', $case) }}" class="btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

</x-layouts.app>
