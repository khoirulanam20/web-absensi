<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Performance Review') }}
        </h2>
    </x-slot>

    <div class="py-4 sm:py-6 md:py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl dark:bg-gray-800 sm:rounded-lg">
                <div class="p-4 sm:p-6 lg:p-8">
                    @livewire('performance.performance-index')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

