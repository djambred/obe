<x-filament-panels::page>
    <div class="space-y-6" x-data="{ scrollPosition: 0 }" x-init="$watch('$wire.mappingData', () => { window.scrollTo({ top: scrollPosition, behavior: 'instant' }); })" @scroll.window="scrollPosition = window.scrollY">
        <!-- Filter Form -->
        <x-filament::card>
            <div class="space-y-4">
                {{ $this->form }}

                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        Pilih filter dan klik "Load Data" untuk menampilkan peta kurikulum
                    </div>
                    <x-filament::button wire:click="loadMappingData" color="primary" icon="heroicon-o-arrow-path">
                        Load Data
                    </x-filament::button>
                </div>
            </div>
        </x-filament::card>

        @if(count($mappingData) > 0)
            <!-- Summary Statistics -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <x-filament::card class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 border-blue-200 dark:border-blue-800">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ count($mappingData) }}</div>
                            <div class="text-xs text-blue-700 dark:text-blue-300 mt-1 font-medium">Total Semester</div>
                        </div>
                        <svg class="w-10 h-10 text-blue-300 dark:text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </x-filament::card>

                <x-filament::card class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 border-green-200 dark:border-green-800">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-3xl font-bold text-green-600 dark:text-green-400">{{ collect($mappingData)->sum(fn($sem) => count($sem['courses'])) }}</div>
                            <div class="text-xs text-green-700 dark:text-green-300 mt-1 font-medium">Mata Kuliah</div>
                        </div>
                        <svg class="w-10 h-10 text-green-300 dark:text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                </x-filament::card>

                <x-filament::card class="bg-gradient-to-br from-amber-50 to-amber-100 dark:from-amber-900/20 dark:to-amber-800/20 border-amber-200 dark:border-amber-800">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-3xl font-bold text-amber-600 dark:text-amber-400">{{ collect($mappingData)->sum(fn($sem) => collect($sem['courses'])->sum('credits')) }}</div>
                            <div class="text-xs text-amber-700 dark:text-amber-300 mt-1 font-medium">Total SKS</div>
                        </div>
                        <svg class="w-10 h-10 text-amber-300 dark:text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                    </div>
                </x-filament::card>

                <x-filament::card class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 border-purple-200 dark:border-purple-800">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ collect($mappingData)->sum(function($sem) { return collect($sem['courses'])->sum(fn($c) => count($c['cpls'])); }) }}</div>
                            <div class="text-xs text-purple-700 dark:text-purple-300 mt-1 font-medium">Mapping CPL</div>
                        </div>
                        <svg class="w-10 h-10 text-purple-300 dark:text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </x-filament::card>
            </div>

            <!-- Export Actions -->
            <x-filament::card>
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Export Peta Kurikulum</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Download mapping dalam berbagai format</p>
                    </div>
                    <div class="flex gap-2">
                        <x-filament::button color="success" icon="heroicon-o-table-cells" outlined>
                            Export Excel
                        </x-filament::button>
                        <x-filament::button color="danger" icon="heroicon-o-document-text" outlined>
                            Export PDF
                        </x-filament::button>
                    </div>
                </div>
            </x-filament::card>

            <!-- Curriculum Mapping per Semester with Collapse -->
            <div class="space-y-3">
                @foreach($mappingData as $index => $semesterData)
                    <div x-data="{ open: {{ $index === 0 ? 'true' : 'false' }} }" class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden bg-white dark:bg-gray-800 shadow-sm">
                        <!-- Semester Header - Clickable -->
                        <button @click="open = !open" class="w-full px-6 py-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <div class="flex items-center gap-4">
                                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-primary-100 dark:bg-primary-900/30">
                                    <span class="text-xl font-bold text-primary-600 dark:text-primary-400">{{ $semesterData['semester'] }}</span>
                                </div>
                                <div class="text-left">
                                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Semester {{ $semesterData['semester'] }}</h2>
                                    <div class="flex items-center gap-4 mt-1 text-sm">
                                        <span class="text-gray-600 dark:text-gray-400">
                                            <strong>{{ count($semesterData['courses']) }}</strong> Mata Kuliah
                                        </span>
                                        <span class="text-primary-600 dark:text-primary-400">
                                            <strong>{{ collect($semesterData['courses'])->sum('credits') }}</strong> SKS
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <svg class="w-6 h-6 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- Collapsible Content -->
                        <div x-show="open" x-collapse class="border-t border-gray-200 dark:border-gray-700">
                            <div class="p-4 space-y-3 bg-gray-50 dark:bg-gray-900/50">
                                @foreach($semesterData['courses'] as $courseIndex => $course)
                                    <div x-data="{ showDetail: false }" class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                                        <!-- Course Header - Clickable -->
                                        <button @click="showDetail = !showDetail" class="w-full px-4 py-3 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                            <div class="flex items-center gap-3 flex-1 text-left">
                                                <div class="flex items-center gap-2 flex-wrap">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-primary-100 text-primary-800 dark:bg-primary-900 dark:text-primary-200">
                                                        {{ $course['code'] }}
                                                    </span>
                                                    @if($course['concentration'])
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                                            {{ $course['concentration'] }}
                                                        </span>
                                                    @endif
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                                        {{ $course['type'] ?? 'Wajib' }}
                                                    </span>
                                                </div>
                                                <div class="flex-1">
                                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $course['name'] }}</h3>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <span class="text-sm font-bold text-primary-600 dark:text-primary-400">{{ $course['credits'] }} SKS</span>
                                                <div class="flex items-center gap-1 text-xs text-gray-500">
                                                    <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded">{{ count($course['cpls']) }} CPL</span>
                                                    <span class="px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded">{{ count($course['cpmks']) }} CPMK</span>
                                                    <span class="px-2 py-1 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 rounded">{{ count($course['study_fields']) }} BK</span>
                                                </div>
                                                <svg class="w-5 h-5 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': showDetail }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </div>
                                        </button>

                                        <!-- Course Details - Collapsible -->
                                        <div x-show="showDetail" x-collapse class="border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/30">
                                            <div class="p-4 grid grid-cols-1 md:grid-cols-3 gap-3">
                                                <!-- CPL Mapping -->
                                                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-3 border border-blue-200 dark:border-blue-800">
                                                    <h4 class="text-xs font-bold text-blue-900 dark:text-blue-200 uppercase mb-2 flex items-center gap-2">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        CPL ({{ count($course['cpls']) }})
                                                    </h4>
                                                    @if(count($course['cpls']) > 0)
                                                        <ul class="space-y-1.5 text-xs max-h-32 overflow-y-auto">
                                                            @foreach($course['cpls'] as $cpl)
                                                                <li class="flex items-start gap-2 p-1.5 bg-white dark:bg-gray-800 rounded">
                                                                    <span class="font-bold text-blue-700 dark:text-blue-300 shrink-0">{{ $cpl['code'] }}</span>
                                                                    <span class="text-gray-700 dark:text-gray-300 line-clamp-2">{{ $cpl['description'] }}</span>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @else
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 italic">Belum ada mapping CPL</p>
                                                    @endif
                                                </div>

                                                <!-- CPMK Mapping -->
                                                <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-3 border border-green-200 dark:border-green-800">
                                                    <h4 class="text-xs font-bold text-green-900 dark:text-green-200 uppercase mb-2 flex items-center gap-2">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                                        </svg>
                                                        CPMK ({{ count($course['cpmks']) }})
                                                    </h4>
                                                    @if(count($course['cpmks']) > 0)
                                                        <ul class="space-y-1.5 text-xs max-h-32 overflow-y-auto">
                                                            @foreach($course['cpmks'] as $cpmk)
                                                                <li class="flex items-start gap-2 p-1.5 bg-white dark:bg-gray-800 rounded">
                                                                    <span class="font-bold text-green-700 dark:text-green-300 shrink-0">{{ $cpmk['code'] }}</span>
                                                                    <span class="text-gray-700 dark:text-gray-300 line-clamp-2">{{ $cpmk['description'] }}</span>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @else
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 italic">Belum ada CPMK</p>
                                                    @endif
                                                </div>

                                                <!-- Study Fields (Bahan Kajian) -->
                                                <div class="bg-amber-50 dark:bg-amber-900/20 rounded-lg p-3 border border-amber-200 dark:border-amber-800">
                                                    <h4 class="text-xs font-bold text-amber-900 dark:text-amber-200 uppercase mb-2 flex items-center gap-2">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                                        </svg>
                                                        Bahan Kajian ({{ count($course['study_fields']) }})
                                                    </h4>
                                                    @if(count($course['study_fields']) > 0)
                                                        <ul class="space-y-1.5 text-xs max-h-32 overflow-y-auto">
                                                            @foreach($course['study_fields'] as $field)
                                                                <li class="flex items-start gap-2 p-1.5 bg-white dark:bg-gray-800 rounded">
                                                                    <span class="font-bold text-amber-700 dark:text-amber-300 shrink-0">{{ $field['code'] }}</span>
                                                                    <span class="text-gray-700 dark:text-gray-300 line-clamp-2">{{ $field['name'] }}</span>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @else
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 italic">Belum ada bahan kajian</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <x-filament::card>
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Belum ada data</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Pilih Program Studi dan Kurikulum untuk melihat peta kurikulum
                    </p>
                </div>
            </x-filament::card>
        @endif
    </div>
</x-filament-panels::page>
