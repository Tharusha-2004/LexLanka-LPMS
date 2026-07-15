<x-layouts.app>

    <x-slot name="header">Edit Client</x-slot>

    <x-slot name="breadcrumbs">
        <a href="{{ route('clients.index') }}" class="hover:text-indigo-600">Clients</a>
        <span>/</span>
        <a href="{{ route('clients.show', $client) }}" class="hover:text-indigo-600">{{ $client->name }}</a>
        <span>/</span>
        <span class="text-slate-600">Edit</span>
    </x-slot>

    <div class="max-w-2xl">
        <div class="card">
            <form method="POST" action="{{ route('clients.update', $client) }}" novalidate>
                @csrf
                @method('PUT')

                <div class="space-y-5">

                    {{-- Name --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input id="name" type="text" name="name"
                               value="{{ old('name', $client->name) }}"
                               class="w-full px-3.5 py-2.5 text-sm rounded-lg border
                                      {{ $errors->has('name') ? 'border-red-400 bg-red-50' : 'border-slate-300 bg-white' }}
                                      text-slate-900 placeholder-slate-400
                                      focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        @error('name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- NIC --}}
                    <div>
                        <label for="nic" class="block text-sm font-medium text-slate-700 mb-1.5">
                            NIC Number <span class="text-red-500">*</span>
                        </label>
                        <input id="nic" type="text" name="nic"
                               value="{{ old('nic', $client->nic) }}"
                               class="w-full px-3.5 py-2.5 text-sm rounded-lg border
                                      {{ $errors->has('nic') ? 'border-red-400 bg-red-50' : 'border-slate-300 bg-white' }}
                                      text-slate-900 placeholder-slate-400
                                      focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        @error('nic')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Phone + Email --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="phone" class="block text-sm font-medium text-slate-700 mb-1.5">Phone</label>
                            <input id="phone" type="text" name="phone"
                                   value="{{ old('phone', $client->phone) }}"
                                   class="w-full px-3.5 py-2.5 text-sm rounded-lg border
                                          {{ $errors->has('phone') ? 'border-red-400 bg-red-50' : 'border-slate-300 bg-white' }}
                                          text-slate-900 placeholder-slate-400
                                          focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                            @error('phone')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">Email</label>
                            <input id="email" type="email" name="email"
                                   value="{{ old('email', $client->email) }}"
                                   class="w-full px-3.5 py-2.5 text-sm rounded-lg border
                                          {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-slate-300 bg-white' }}
                                          text-slate-900 placeholder-slate-400
                                          focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                            @error('email')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Intake Date --}}
                    <div>
                        <label for="intake_date" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Intake Date <span class="text-red-500">*</span>
                        </label>
                        <input id="intake_date" type="date" name="intake_date"
                               value="{{ old('intake_date', $client->intake_date->toDateString()) }}"
                               class="w-full px-3.5 py-2.5 text-sm rounded-lg border
                                      {{ $errors->has('intake_date') ? 'border-red-400 bg-red-50' : 'border-slate-300 bg-white' }}
                                      text-slate-900
                                      focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        @error('intake_date')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <div class="flex items-center gap-3 mt-7 pt-6 border-t border-slate-200">
                    <button type="submit" class="btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Update Client
                    </button>
                    <a href="{{ route('clients.show', $client) }}" class="btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

</x-layouts.app>
