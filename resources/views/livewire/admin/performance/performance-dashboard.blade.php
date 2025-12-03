<div>
    <div class="mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="rounded-lg bg-blue-200 px-4 py-3 sm:px-8 sm:py-4 text-gray-800 dark:bg-blue-900 dark:text-white">
            <div class="text-sm sm:text-base font-medium">{{ __('Active Cycles') }}</div>
            <div class="text-xl sm:text-2xl md:text-3xl font-bold">{{ $activeCycles }}</div>
        </div>
        <div class="rounded-lg bg-green-200 px-4 py-3 sm:px-8 sm:py-4 text-gray-800 dark:bg-green-900 dark:text-white">
            <div class="text-sm sm:text-base font-medium">{{ __('Total Cycles') }}</div>
            <div class="text-xl sm:text-2xl md:text-3xl font-bold">{{ $totalCycles }}</div>
        </div>
        <div class="rounded-lg bg-purple-200 px-4 py-3 sm:px-8 sm:py-4 text-gray-800 dark:bg-purple-900 dark:text-white">
            <div class="text-sm sm:text-base font-medium">{{ __('Total Assessments') }}</div>
            <div class="text-xl sm:text-2xl md:text-3xl font-bold">{{ $totalAssessments }}</div>
        </div>
        <div class="rounded-lg bg-yellow-200 px-4 py-3 sm:px-8 sm:py-4 text-gray-800 dark:bg-yellow-900 dark:text-white">
            <div class="text-sm sm:text-base font-medium">{{ __('Completed') }}</div>
            <div class="text-xl sm:text-2xl md:text-3xl font-bold">{{ $completedAssessments }}</div>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">{{ __('Recent Cycles') }}</h3>
            <div class="space-y-3">
                @forelse ($recentCycles as $cycle)
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $cycle->name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $cycle->start_date->format('d/m/Y') }} - {{ $cycle->end_date->format('d/m/Y') }}
                                </p>
                            </div>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                @if ($cycle->status === 'open') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                @elseif ($cycle->status === 'closed') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif">
                                {{ ucfirst($cycle->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('No cycles found.') }}</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">{{ __('Top Performers') }}</h3>
            <div class="space-y-3">
                @forelse ($topPerformers as $assessment)
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $assessment->user->name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $assessment->reviewCycle->name }}</p>
                            </div>
                            <span class="text-lg font-bold text-green-600 dark:text-green-400">
                                {{ number_format($assessment->overall_score, 1) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('No assessments found.') }}</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
