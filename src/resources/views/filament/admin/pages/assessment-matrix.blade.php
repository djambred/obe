<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Filter Form -->
        <x-filament::card>
            <div class="space-y-4">
                {{ $this->form }}

                @if($courseId)
                    <div class="flex justify-end">
                        <x-filament::button wire:click="loadMatrixData" color="primary" icon="heroicon-o-arrow-path">
                            Refresh Data
                        </x-filament::button>
                    </div>
                @endif
            </div>
        </x-filament::card>

        @if(!empty($matrixData))
            <!-- Course Info -->
            <x-filament::card>
                <div class="space-y-2">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $matrixData['course']->code }} - {{ $matrixData['course']->name }}
                    </h2>
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        <span class="font-semibold">SKS:</span> {{ $matrixData['course']->credits }} SKS
                        <span class="mx-2">|</span>
                        <span class="font-semibold">Semester:</span> {{ $matrixData['course']->semester }}
                    </div>
                </div>
            </x-filament::card>

            <!-- Assessment Matrix Table -->
            <x-filament::card>
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-100 dark:bg-gray-800">
                                <th colspan="2" class="border border-gray-300 dark:border-gray-600 px-4 py-3 text-center font-bold text-gray-900 dark:text-white">
                                    Penilaian
                                </th>
                                <th colspan="{{ count($matrixData['cpmks']) }}" class="border border-gray-300 dark:border-gray-600 px-4 py-3 text-center font-bold text-gray-900 dark:text-white">
                                    CPMK
                                </th>
                                <th rowspan="2" class="border border-gray-300 dark:border-gray-600 px-4 py-3 text-center font-bold text-gray-900 dark:text-white bg-blue-50 dark:bg-blue-900/20">
                                    Bobot<br>Penilaian
                                </th>
                            </tr>
                            <tr class="bg-gray-50 dark:bg-gray-700">
                                <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-center text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    Metode
                                </th>
                                <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-center text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    Urut
                                </th>
                                @foreach($matrixData['cpmks'] as $cpmk)
                                    <th class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center text-xs font-semibold text-gray-700 dark:text-gray-300">
                                        {{ $cpmk->code }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($matrixData['matrix'] as $assessmentGroup)
                                @foreach($assessmentGroup['items'] as $index => $item)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                        @if($index === 0)
                                            <td rowspan="{{ count($assessmentGroup['items']) }}" class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-center font-semibold bg-gray-50 dark:bg-gray-800">
                                                {{ $assessmentGroup['type'] }}
                                            </td>
                                        @endif
                                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-center font-mono text-sm">
                                            {{ $item['code'] }}
                                        </td>
                                        @foreach($matrixData['cpmks'] as $cpmk)
                                            <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center text-sm">
                                                @if(($item['cpmk_weights'][$cpmk->code] ?? 0) > 0)
                                                    <span class="font-semibold text-blue-600 dark:text-blue-400">
                                                        {{ number_format($item['cpmk_weights'][$cpmk->code], 3) }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-300 dark:text-gray-600">-</span>
                                                @endif
                                            </td>
                                        @endforeach
                                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-center font-semibold bg-blue-50 dark:bg-blue-900/20">
                                            {{ number_format($item['total_weight'], 3) }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach

                            <!-- Total Row -->
                            <tr class="bg-yellow-100 dark:bg-yellow-900/30 font-bold">
                                <td colspan="2" class="border border-gray-300 dark:border-gray-600 px-4 py-3 text-center">
                                    Bobot CPMK
                                </td>
                                @foreach($matrixData['cpmks'] as $cpmk)
                                    <td class="border border-gray-300 dark:border-gray-600 px-3 py-3 text-center text-blue-700 dark:text-blue-300">
                                        {{ number_format($matrixData['cpmk_totals'][$cpmk->code] ?? 0, 3) }}
                                    </td>
                                @endforeach
                                <td class="border border-gray-300 dark:border-gray-600 px-4 py-3 text-center bg-blue-100 dark:bg-blue-900/40">
                                    {{ number_format(array_sum($matrixData['cpmk_totals']), 3) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Legend -->
                <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-3">Keterangan:</h3>
                    <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                        <li class="flex items-start gap-2">
                            <span class="text-blue-600 dark:text-blue-400">•</span>
                            <span>Setiap CPMK merupakan uraian CPL (Capaian Pembelajaran Lulusan)</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-blue-600 dark:text-blue-400">•</span>
                            <span>Pengukuran ketercapaian CPMK dilakukan melalui berbagai metode penilaian</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-blue-600 dark:text-blue-400">•</span>
                            <span>Setiap penilaian berkontribusi terhadap nilai akhir</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-blue-600 dark:text-blue-400">•</span>
                            <span>Setiap CPMK berkontribusi terhadap pencapaian CPL</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-blue-600 dark:text-blue-400">•</span>
                            <span><strong>Bobot CPMK</strong> = Total kontribusi semua penilaian terhadap CPMK tersebut</span>
                        </li>
                    </ul>
                </div>

                <!-- Actions -->
                <div class="mt-6 flex justify-end gap-3">
                    <x-filament::button color="success" icon="heroicon-o-table-cells" outlined>
                        Export Excel
                    </x-filament::button>
                    <x-filament::button color="danger" icon="heroicon-o-document-text" outlined>
                        Export PDF
                    </x-filament::button>
                </div>
            </x-filament::card>
        @else
            <x-filament::card>
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Belum ada data</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Pilih Fakultas, Program Studi, dan Mata Kuliah untuk melihat matriks bobot penilaian
                    </p>
                </div>
            </x-filament::card>
        @endif
    </div>
</x-filament-panels::page>
