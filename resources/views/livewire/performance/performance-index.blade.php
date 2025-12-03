<div>
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">
            {{ __('Performance Review') }}
        </h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            {{ __('Kelola penilaian kinerja Anda di sini') }}
        </p>
    </div>

    <!-- Tutorial Link -->
    <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
        <div class="flex items-center justify-between">
            <div>
                <h4 class="font-semibold text-blue-900 dark:text-blue-200 mb-1">
                    {{ __('Butuh bantuan?') }}
                </h4>
                <p class="text-sm text-blue-800 dark:text-blue-300">
                    {{ __('Pelajari cara mengisi KPI dengan benar') }}
                </p>
            </div>
            <x-button href="{{ route('performance.tutorial') }}" class="bg-blue-600 hover:bg-blue-700">
                <x-heroicon-o-question-mark-circle class="mr-2 h-4 w-4" />
                {{ __('Tutorial') }}
            </x-button>
        </div>
    </div>

    <!-- Manager Review Section -->
    @if ($isManager)
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                <h4 class="text-base font-semibold text-gray-800 dark:text-gray-200">
                    {{ __('Review sebagai Manager') }}
                </h4>
                <x-secondary-button href="{{ route('performance.manager-history') }}" class="w-full sm:w-auto text-sm">
                    <x-heroicon-o-clock class="mr-2 h-4 w-4" />
                    {{ __('History Review') }}
                </x-secondary-button>
            </div>
            
            @if ($managerCycles->isNotEmpty())
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4 mb-4">
                    <p class="text-sm text-yellow-800 dark:text-yellow-300">
                        <strong>{{ __('Perhatian:') }}</strong> {{ __('Anda memiliki assessment yang perlu di-review sebagai manager.') }}
                    </p>
                </div>
            @endif
            
            @forelse ($managerCycles as $cycle)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6 mb-4">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex-1">
                            <h5 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                {{ $cycle->name }}
                            </h5>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                                {{ __('Periode') }}: {{ $cycle->start_date->format('d/m/Y') }} - {{ $cycle->end_date->format('d/m/Y') }}
                            </p>
                            <p class="text-sm font-medium text-red-600 dark:text-red-400">
                                {{ __('Karyawan yang perlu di-review') }}: {{ $cycle->assessments->count() }}
                            </p>
                        </div>
                        <x-button href="{{ route('performance.manager', $cycle->id) }}" class="w-full sm:w-auto bg-yellow-600 hover:bg-yellow-700">
                            <x-heroicon-o-pencil class="mr-2 h-4 w-4" />
                            {{ __('Review Sekarang') }}
                        </x-button>
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center">
                    <p class="text-gray-500 dark:text-gray-400">
                        {{ __('Tidak ada assessment yang perlu di-review saat ini.') }}
                    </p>
                </div>
            @endforelse
        </div>
    @endif

    <!-- Open Review Cycles -->
    <div class="mb-6">
        <h4 class="text-base font-semibold text-gray-800 dark:text-gray-200 mb-4">
            {{ __('Review Cycles yang Aktif (Self Assessment)') }}
        </h4>
        
        @forelse ($openCycles as $cycle)
            @php
                $assessment = $cycle->assessments->first();
                $status = $assessment ? $assessment->status : 'not_started';
            @endphp
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6 mb-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex-1">
                        <h5 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                            {{ $cycle->name }}
                        </h5>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                            {{ __('Periode') }}: {{ $cycle->start_date->format('d/m/Y') }} - {{ $cycle->end_date->format('d/m/Y') }}
                        </p>
                        @if ($cycle->description)
                            <p class="text-sm text-gray-500 dark:text-gray-500 mb-3">
                                {{ $cycle->description }}
                            </p>
                        @endif
                        <div class="flex items-center gap-2">
                            @php
                                $statusConfig = [
                                    'not_started' => ['label' => __('Belum Dimulai'), 'color' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'],
                                    'pending_self' => ['label' => __('Menunggu Self Assessment'), 'color' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300'],
                                    'pending_manager' => ['label' => __('Menunggu Review Manager'), 'color' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300'],
                                    'pending_360' => ['label' => __('Menunggu 360 Review'), 'color' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300'],
                                    'completed' => ['label' => __('Selesai'), 'color' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'],
                                ];
                                $currentStatus = $statusConfig[$status] ?? $statusConfig['not_started'];
                            @endphp
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $currentStatus['color'] }}">
                                {{ $currentStatus['label'] }}
                            </span>
                            @if ($assessment && $assessment->overall_score)
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    {{ __('Score') }}: {{ number_format($assessment->overall_score, 1) }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2">
                        @if ($status === 'not_started' || $status === 'pending_self')
                            <x-button href="{{ route('performance.self', $cycle->id) }}" class="w-full sm:w-auto">
                                <x-heroicon-o-pencil class="mr-2 h-4 w-4" />
                                {{ __('Mulai Assessment') }}
                            </x-button>
                        @elseif ($status === 'pending_manager' || $status === 'pending_360')
                            <x-secondary-button href="{{ route('performance.self', $cycle->id) }}" class="w-full sm:w-auto">
                                {{ __('Lihat Assessment') }}
                            </x-secondary-button>
                        @elseif ($status === 'completed')
                            <x-button href="{{ route('performance.results', $assessment->id) }}" class="w-full sm:w-auto bg-green-600 hover:bg-green-700">
                                <x-heroicon-o-chart-bar class="mr-2 h-4 w-4" />
                                {{ __('Lihat Hasil') }}
                            </x-button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center">
                <x-heroicon-o-information-circle class="mx-auto h-12 w-12 text-gray-400 mb-4" />
                <p class="text-gray-500 dark:text-gray-400">
                    {{ __('Tidak ada review cycle yang aktif saat ini.') }}
                </p>
            </div>
        @endforelse
    </div>

    <!-- Completed Assessments -->
    @if ($completedAssessments->isNotEmpty())
        <div class="mb-6">
            <h4 class="text-base font-semibold text-gray-800 dark:text-gray-200 mb-4">
                {{ __('Hasil Assessment Sebelumnya') }}
            </h4>
            
            <div class="space-y-4">
                @foreach ($completedAssessments as $assessment)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div>
                                <h5 class="text-base font-semibold text-gray-900 dark:text-white mb-1">
                                    {{ $assessment->reviewCycle->name }}
                                </h5>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                    {{ __('Periode') }}: {{ $assessment->reviewCycle->start_date->format('d/m/Y') }} - {{ $assessment->reviewCycle->end_date->format('d/m/Y') }}
                                </p>
                                <div class="flex items-center gap-3">
                                    <span class="text-sm font-semibold text-green-600 dark:text-green-400">
                                        {{ __('Overall Score') }}: {{ number_format($assessment->overall_score, 1) }}
                                    </span>
                                    @if ($assessment->completed_at)
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ __('Selesai') }}: {{ $assessment->completed_at->format('d/m/Y') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <x-button href="{{ route('performance.results', $assessment->id) }}" class="w-full sm:w-auto">
                                <x-heroicon-o-eye class="mr-2 h-4 w-4" />
                                {{ __('Detail') }}
                            </x-button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
