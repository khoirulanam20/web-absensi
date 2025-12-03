<div>
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">
                    {{ __('Hasil Assessment') }} - {{ $assessment->reviewCycle->name }}
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Periode') }}: {{ $assessment->reviewCycle->start_date->format('d/m/Y') }} - {{ $assessment->reviewCycle->end_date->format('d/m/Y') }}
                </p>
            </div>
            @if ($assessment->overall_score)
                <div class="text-center sm:text-right">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">{{ __('Overall Score') }}</p>
                    <p class="text-3xl font-bold 
                        @if ($assessment->overall_score >= 80) text-green-600 dark:text-green-400
                        @elseif ($assessment->overall_score >= 60) text-blue-600 dark:text-blue-400
                        @elseif ($assessment->overall_score >= 40) text-yellow-600 dark:text-yellow-400
                        @else text-red-600 dark:text-red-400 @endif">
                        {{ number_format($assessment->overall_score, 1) }}
                    </p>
                </div>
            @endif
        </div>

        <!-- Employee Info -->
        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Nama') }}</p>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $assessment->user->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('NIP') }}</p>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $assessment->user->nip ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Status') }}</p>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                        @if ($assessment->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                        @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 @endif">
                        {{ ucfirst(str_replace('_', ' ', $assessment->status)) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Details -->
    <div class="mb-6">
        <h4 class="text-base font-semibold text-gray-800 dark:text-gray-200 mb-4">
            {{ __('Detail Penilaian per KPI') }}
        </h4>
        
        <div class="space-y-4">
            @foreach ($assessment->items as $item)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4">
                        <div class="flex-1">
                            <h5 class="text-base font-semibold text-gray-900 dark:text-white mb-1">
                                {{ $item->kpiAssignment->kpi->title }}
                            </h5>
                            @if ($item->kpiAssignment->kpi->description)
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                    {{ $item->kpiAssignment->kpi->description }}
                                </p>
                            @endif
                            <div class="flex flex-wrap gap-3 text-sm">
                                <span class="text-gray-500 dark:text-gray-400">
                                    {{ __('Target') }}: <strong>{{ $item->kpiAssignment->target }} {{ $item->kpiAssignment->kpi->unit ?? '' }}</strong>
                                </span>
                                <span class="text-gray-500 dark:text-gray-400">
                                    {{ __('Bobot') }}: <strong>{{ $item->kpiAssignment->weight }}%</strong>
                                </span>
                            </div>
                        </div>
                        <div class="mt-3 sm:mt-0 text-center sm:text-right">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">{{ __('Final Score') }}</p>
                            <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                                {{ number_format($item->final_score ?? 0, 1) }}
                            </p>
                        </div>
                    </div>

                    <!-- Scores Breakdown -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div class="bg-blue-50 dark:bg-blue-900/20 p-3 rounded border border-blue-200 dark:border-blue-800">
                            <p class="text-xs text-blue-600 dark:text-blue-400 mb-1">{{ __('Self Score') }}</p>
                            <p class="text-lg font-semibold text-blue-900 dark:text-blue-200">
                                {{ number_format($item->self_score ?? 0, 1) }}
                            </p>
                        </div>
                        <div class="bg-green-50 dark:bg-green-900/20 p-3 rounded border border-green-200 dark:border-green-800">
                            <p class="text-xs text-green-600 dark:text-green-400 mb-1">{{ __('Manager Score') }}</p>
                            <p class="text-lg font-semibold text-green-900 dark:text-green-200">
                                {{ number_format($item->manager_score ?? 0, 1) }}
                            </p>
                        </div>
                        @if ($item->reviewer_score)
                            <div class="bg-purple-50 dark:bg-purple-900/20 p-3 rounded border border-purple-200 dark:border-purple-800">
                                <p class="text-xs text-purple-600 dark:text-purple-400 mb-1">{{ __('Peer Score') }}</p>
                                <p class="text-lg font-semibold text-purple-900 dark:text-purple-200">
                                    {{ number_format($item->reviewer_score, 1) }}
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- Comments -->
                    @if ($item->comment)
                        <div class="mb-3 p-3 bg-blue-50 dark:bg-blue-900/20 rounded border border-blue-200 dark:border-blue-800">
                            <p class="text-xs font-semibold text-blue-900 dark:text-blue-200 mb-1">{{ __('Komentar Karyawan') }}:</p>
                            <p class="text-sm text-blue-800 dark:text-blue-300">{{ $item->comment }}</p>
                        </div>
                    @endif

                    @if ($item->manager_comment)
                        <div class="mb-3 p-3 bg-green-50 dark:bg-green-900/20 rounded border border-green-200 dark:border-green-800">
                            <p class="text-xs font-semibold text-green-900 dark:text-green-200 mb-1">{{ __('Feedback Manager') }}:</p>
                            <p class="text-sm text-green-800 dark:text-green-300">{{ $item->manager_comment }}</p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>


    <div class="flex justify-end gap-3">
        <x-secondary-button onclick="window.print()" class="w-full sm:w-auto">
            <x-heroicon-o-printer class="mr-2 h-4 w-4" />
            {{ __('Print') }}
        </x-secondary-button>
        <x-button href="{{ route('performance.index') }}" class="w-full sm:w-auto">
            {{ __('Kembali') }}
        </x-button>
    </div>
</div>
