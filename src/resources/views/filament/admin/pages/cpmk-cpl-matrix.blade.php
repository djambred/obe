<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Filter Form --}}
        <x-filament::card>
            <form wire:submit.prevent="loadMatrix">
                {{ $this->form }}

                <div class="mt-6">
                    <x-filament::button type="submit" color="primary" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="loadMatrix">Load Matriks</span>
                        <span wire:loading wire:target="loadMatrix">Loading...</span>
                    </x-filament::button>
                </div>
            </form>
        </x-filament::card>

        {{-- Matrix Display --}}
        @if($showMatrix)
            <x-filament::card>
                <div class="space-y-4">
                    <div>
                        <h2 class="text-2xl font-bold text-center text-gray-900 dark:text-white">
                            Kontribusi CPMK terhadap CPL
                        </h2>
                        @if($course_id)
                            @php
                                $course = \App\Models\Course::find($course_id);
                            @endphp
                            <p class="text-center text-gray-600 dark:text-gray-400 mt-2">
                                Mata Kuliah: <strong>{{ $course->code }} - {{ $course->name }}</strong>
                            </p>
                        @endif
                    </div>

                    @if($cpls->count() > 0 && $cpmks->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full border-collapse">
                                <thead>
                                    <tr class="bg-gray-100 dark:bg-gray-800">
                                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-3 text-left font-semibold text-gray-900 dark:text-white">
                                            CPMK
                                        </th>
                                        @foreach($cpls as $cpl)
                                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-3 text-center font-semibold text-gray-900 dark:text-white">
                                                {{ $cpl->code }}
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($matrix as $row)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-3 font-medium text-gray-900 dark:text-white">
                                                {{ $row['cpmk']->code }}
                                            </td>
                                            @foreach($cpls as $cpl)
                                                @php
                                                    $value = $row['contributions'][$cpl->id] ?? 0;
                                                @endphp
                                                <td class="border border-gray-300 dark:border-gray-600 px-4 py-3 text-center {{ $value == 1 ? 'bg-yellow-300 dark:bg-yellow-600' : 'bg-gray-100 dark:bg-gray-800' }}">
                                                    <span class="font-bold text-gray-900 dark:text-white">{{ $value }}</span>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Statistics --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                                <div class="text-sm text-blue-600 dark:text-blue-400 font-medium">Total CPMK</div>
                                <div class="text-2xl font-bold text-blue-900 dark:text-blue-100">{{ $cpmks->count() }}</div>
                            </div>
                            <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
                                <div class="text-sm text-green-600 dark:text-green-400 font-medium">Total CPL</div>
                                <div class="text-2xl font-bold text-green-900 dark:text-green-100">{{ $cpls->count() }}</div>
                            </div>
                            <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4">
                                <div class="text-sm text-yellow-600 dark:text-yellow-400 font-medium">Total Kontribusi</div>
                                <div class="text-2xl font-bold text-yellow-900 dark:text-yellow-100">
                                    {{ collect($matrix)->sum(fn($row) => array_sum($row['contributions'])) }}
                                </div>
                            </div>
                        </div>

                        {{-- Legend --}}
                        <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Keterangan:</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm text-gray-700 dark:text-gray-300">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 bg-yellow-300 dark:bg-yellow-600 border border-gray-300 dark:border-gray-600 rounded flex items-center justify-center">
                                        <span class="font-bold text-gray-900 dark:text-white text-xs">1</span>
                                    </div>
                                    <span>CPMK berkontribusi terhadap CPL</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded flex items-center justify-center">
                                        <span class="font-bold text-gray-900 dark:text-white text-xs">0</span>
                                    </div>
                                    <span>CPMK tidak berkontribusi terhadap CPL</span>
                                </div>
                                <div class="md:col-span-2">
                                    <strong>CPL</strong>: Capaian Pembelajaran Lulusan (Program Learning Outcome)<br>
                                    <strong>CPMK</strong>: Capaian Pembelajaran Mata Kuliah (Course Learning Outcome)
                                </div>
                            </div>
                        </div>

                        {{-- Export Buttons --}}
                        <div class="mt-6 flex gap-3">
                            <x-filament::button color="success" icon="heroicon-o-document-arrow-down">
                                Export to Excel
                            </x-filament::button>
                            <x-filament::button color="danger" icon="heroicon-o-document-arrow-down">
                                Export to PDF
                            </x-filament::button>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-gray-500 dark:text-gray-400">
                                @if($cpls->count() == 0)
                                    <p>Tidak ada CPL untuk program studi ini.</p>
                                @elseif($cpmks->count() == 0)
                                    <p>Tidak ada CPMK untuk mata kuliah ini.</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </x-filament::card>
        @endif
    </div>
</x-filament-panels::page>
