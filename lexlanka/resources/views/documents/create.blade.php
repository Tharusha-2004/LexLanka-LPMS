<x-layouts.app>

    <x-slot name="header">Upload Document</x-slot>

    <x-slot name="breadcrumbs">
        <a href="{{ route('documents.index') }}" class="hover:text-indigo-600">Documents</a>
        <span>/</span>
        <span class="text-slate-600">Upload</span>
    </x-slot>

    <div class="max-w-2xl">
        <div class="card">

            {{-- CRITICAL: enctype="multipart/form-data" required for file uploads --}}
            <form method="POST"
                  action="{{ route('documents.store') }}"
                  enctype="multipart/form-data"
                  novalidate>
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
                                    @if ($case->client)— {{ $case->client->name }}@endif
                                    ({{ $case->case_type }})
                                </option>
                            @endforeach
                        </select>
                        @error('case_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Category --}}
                    <div>
                        <label for="category" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Category <span class="text-red-500">*</span>
                        </label>
                        <select id="category" name="category"
                                class="w-full px-3.5 py-2.5 text-sm rounded-lg border
                                       {{ $errors->has('category') ? 'border-red-400 bg-red-50' : 'border-slate-300 bg-white' }}
                                       text-slate-900
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                            <option value="">— Select category —</option>
                            @foreach ($categoryOptions as $value => $label)
                                <option value="{{ $value }}"
                                    {{ old('category') === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('category')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- File Upload --}}
                    <div x-data="{ fileName: '', dragging: false }">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">
                            Document File <span class="text-red-500">*</span>
                        </label>

                        {{-- Drop zone --}}
                        <label for="file"
                               class="relative flex flex-col items-center justify-center w-full
                                      rounded-lg border-2 border-dashed cursor-pointer
                                      transition-colors py-8 px-4 text-center
                                      {{ $errors->has('file') ? 'border-red-400 bg-red-50' : 'border-slate-300 bg-slate-50 hover:bg-indigo-50 hover:border-indigo-400' }}"
                               :class="dragging ? 'border-indigo-500 bg-indigo-50' : ''"
                               @dragover.prevent="dragging = true"
                               @dragleave.prevent="dragging = false"
                               @drop.prevent="
                                   dragging = false;
                                   const f = $event.dataTransfer.files[0];
                                   if (f) {
                                       fileName = f.name;
                                       $refs.fileInput.files = $event.dataTransfer.files;
                                   }
                               ">

                            <svg class="w-8 h-8 mb-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>

                            <template x-if="!fileName">
                                <div>
                                    <p class="text-sm font-medium text-slate-600">
                                        <span class="text-indigo-600">Click to upload</span> or drag and drop
                                    </p>
                                    <p class="mt-1 text-xs text-slate-400">PDF, JPG, PNG — max 25 MB</p>
                                </div>
                            </template>

                            <template x-if="fileName">
                                <p class="text-sm font-semibold text-indigo-700" x-text="fileName"></p>
                            </template>

                            <input id="file"
                                   type="file"
                                   name="file"
                                   x-ref="fileInput"
                                   accept=".pdf,.jpg,.jpeg,.png"
                                   class="absolute inset-0 opacity-0 cursor-pointer"
                                   @change="fileName = $event.target.files[0]?.name ?? ''">
                        </label>

                        @error('file')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Info --}}
                    <div class="flex items-start gap-3 p-3.5 rounded-lg bg-slate-50 border border-slate-200">
                        <svg class="w-4 h-4 text-slate-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-xs text-slate-500">
                            The file will be stored securely on the server.
                            You will be recorded as the uploader. Once uploaded, documents cannot be replaced — delete and re-upload to update.
                        </p>
                    </div>

                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3 mt-7 pt-6 border-t border-slate-200">
                    <button type="submit" class="btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        Upload Document
                    </button>
                    <a href="{{ route('documents.index') }}" class="btn-secondary">Cancel</a>
                </div>

            </form>
        </div>
    </div>

</x-layouts.app>
