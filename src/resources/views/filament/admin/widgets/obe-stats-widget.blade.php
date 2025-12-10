<x-filament-widgets::widget>
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        @foreach ($this->getStats() as $stat)
            <div class="relative overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 h-32">
                <a 
                    href="{{ $stat['url'] }}" 
                    class="overlook-link absolute inset-0 py-2 px-3 text-gray-600 font-medium ring-primary-500 dark:text-gray-400 group hover:ring-2 focus:ring-2 rounded-xl"
                >
                    <x-filament::icon 
                        :icon="$stat['icon']" 
                        class="overlook-icon w-auto h-24 absolute start-0 top-8 text-{{ $stat['color'] }}-500 opacity-20 dark:opacity-20 transition group-hover:scale-110 group-hover:-rotate-12 rtl:group-hover:rotate-12 group-hover:opacity-40 dark:group-hover:opacity-80"
                    />
                    
                    <span class="overlook-name relative z-10 block text-sm font-medium text-gray-500 dark:text-gray-400">
                        {{ $stat['label'] }}
                    </span>
                    
                    <span class="overlook-count text-gray-600 dark:text-gray-300 absolute leading-none bottom-3 end-4 text-3xl font-bold">
                        {{ $stat['value'] }}
                    </span>
                </a>
            </div>
        @endforeach
    </div>
</x-filament-widgets::widget>
