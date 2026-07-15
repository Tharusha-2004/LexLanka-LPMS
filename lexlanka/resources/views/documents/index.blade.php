<x-layouts.app>

    <x-slot name="header">Documents</x-slot>

    <x-slot name="actions">
        <a href="{{ route('documents.create') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
            </svg>
            Upload Document
        </a>
    </x-slot>

    {{-- Category filter chips --}}
    <div class="flex flex-wrap gap-2 mb-6">
        @php
            $allCategories = ['' => 'All', 'evidence' => 'Evidence', 'deeds' => 'Deeds', 'correspondence' => 'Correspondence'];
        @endphp
        @foreach ($allCategories as $value => $label)
            <a href="{{ route('documents.index', array_merge(request()->query(), ['category' => $value])) }}"
               class="px-3.5 py-1.5 rounded-full text-xs font-semibold border transition-colors
                      {{ request('category', '') === $value
                          ? 'bg-indigo-600 text-white border-indigo-600'
                          : 'bg-white text-slate-600 border-slate-300 hover:border-indigo-400 hover:text-indigo-600' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- Table --}}
    <div class="card p-0 overflow-hidden">
        @if ($documents->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-center text-slate-400">
                <svg class="w-12 h-12 mb-3 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-sm font-medium">No documents found.</p>
                <a href="{{ route('documents.create') }}" class="mt-3 btn-primary text-sm">Upload first document</a>
            </div>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">File</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider hidden md:table-cell">Case</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Category</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider hidden lg:table-cell">Uploaded By</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider hidden lg:table-cell">Date</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($documents as $doc)
                        @php
                            $categoryConfig = [
                                'evidence'       => ['bg-amber-100 text-amber-700',   'Evidence'],
                                'deeds'          => ['bg-blue-100 text-blue-700',      'Deeds'],
                                'correspondence' => ['bg-violet-100 text-violet-700',  'Correspondence'],
                            ];
                            [$catBadge, $catLabel] = $categoryConfig[$doc->category] ?? ['bg-slate-100 text-slate-600', ucfirst($doc->category)];

                            $typeIconMap = [
                                'pdf'  => ['text-red-500',  'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z'],
                                'jpg'  => ['text-sky-500',  'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'],
                                'jpeg' => ['text-sky-500',  'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'],
                                'png'  => ['text-emerald-500', 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'],
                            ];
                            [$iconColor, $iconPath] = $typeIconMap[$doc->file_type] ?? ['text-slate-400', 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'];
                        @endphp
                        <tr class="hover:bg-slate-50 transition-colors">

                            {{-- File name + type icon --}}
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="shrink-0 w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $iconPath }}"/>
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-medium text-slate-800 truncate max-w-[180px]">
                                            {{ basename($doc->file_path) }}
                                        </p>
                                        <p class="text-xs text-slate-400 uppercase">{{ $doc->file_type }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Case --}}
                            <td class="px-5 py-3.5 hidden md:table-cell">
                                <a href="{{ route('cases.show', $doc->legalCase) }}"
                                   class="text-indigo-600 font-medium hover:underline text-sm">
                                    Case #{{ $doc->legalCase->id }}
                                </a>
                                <p class="text-xs text-slate-400">{{ $doc->legalCase->case_type }}</p>
                            </td>

                            {{-- Category badge --}}
                            <td class="px-5 py-3.5">
                                <span class="inline-block text-xs font-semibold px-2.5 py-0.5 rounded-full {{ $catBadge }}">
                                    {{ $catLabel }}
                                </span>
                            </td>

                            {{-- Uploader --}}
                            <td class="px-5 py-3.5 hidden lg:table-cell">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 text-xs
                                                font-bold flex items-center justify-center shrink-0">
                                        {{ strtoupper(substr($doc->uploader->name ?? '?', 0, 1)) }}
                                    </div>
                                    <span class="text-slate-600 text-sm">{{ $doc->uploader->name ?? '—' }}</span>
                                </div>
                            </td>

                            {{-- Upload date --}}
                            <td class="px-5 py-3.5 text-slate-400 text-xs hidden lg:table-cell">
                                {{ $doc->created_at->format('d M Y') }}<br>
                                <span class="text-slate-300">{{ $doc->created_at->format('g:i A') }}</span>
                            </td>

                            {{-- Actions --}}
                            <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                {{-- View/Download --}}
                                <a href="{{ asset('storage/' . $doc->file_path) }}"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="inline-flex items-center gap-1 text-indigo-600 hover:text-indigo-800
                                          text-xs font-medium mr-3 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    Download
                                </a>

                                {{-- Delete --}}
                                <form method="POST"
                                      action="{{ route('documents.destroy', $doc) }}"
                                      class="inline-block"
                                      x-data
                                      @submit.prevent="
                                          if(confirm('Permanently delete this document? The file cannot be recovered.')) $el.submit()
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
            @if ($documents->hasPages())
                <div class="px-5 py-4 border-t border-slate-200">
                    {{ $documents->links() }}
                </div>
            @endif
        @endif
    </div>

</x-layouts.app>
