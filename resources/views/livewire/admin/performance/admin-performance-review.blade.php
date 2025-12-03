<div>
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">
            {{ __('Performance Review Management') }}
        </h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            {{ __('Kelola dan monitor semua assessment karyawan') }}
        </p>
    </div>

    <!-- Statistics -->
    <div class="mb-6 grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 border border-blue-200 dark:border-blue-800">
            <p class="text-xs text-blue-600 dark:text-blue-400 mb-1">{{ __('Total') }}</p>
            <p class="text-xl font-bold text-blue-900 dark:text-blue-200">{{ $totalAssessments }}</p>
        </div>
        <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4 border border-yellow-200 dark:border-yellow-800">
            <p class="text-xs text-yellow-600 dark:text-yellow-400 mb-1">{{ __('Pending Self') }}</p>
            <p class="text-xl font-bold text-yellow-900 dark:text-yellow-200">{{ $pendingSelf }}</p>
        </div>
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 border border-blue-200 dark:border-blue-800">
            <p class="text-xs text-blue-600 dark:text-blue-400 mb-1">{{ __('Pending Manager') }}</p>
            <p class="text-xl font-bold text-blue-900 dark:text-blue-200">{{ $pendingManager }}</p>
        </div>
        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4 border border-purple-200 dark:border-purple-800">
            <p class="text-xs text-purple-600 dark:text-purple-400 mb-1">{{ __('Pending 360') }}</p>
            <p class="text-xl font-bold text-purple-900 dark:text-purple-200">{{ $pending360 }}</p>
        </div>
        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4 border border-green-200 dark:border-green-800">
            <p class="text-xs text-green-600 dark:text-green-400 mb-1">{{ __('Completed') }}</p>
            <p class="text-xl font-bold text-green-900 dark:text-green-200">{{ $completed }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
        <div>
            <x-input type="text" class="w-full" wire:model.live.debounce.300ms="search" placeholder="{{ __('Cari nama atau NIP...') }}" />
        </div>
        <div>
            <x-select class="w-full" wire:model.live="cycleFilter">
                <option value="">{{ __('Semua Cycle') }}</option>
                @foreach ($cycles as $cycle)
                    <option value="{{ $cycle->id }}">{{ $cycle->name }}</option>
                @endforeach
            </x-select>
        </div>
        <div>
            <x-select class="w-full" wire:model.live="statusFilter">
                <option value="">{{ __('Semua Status') }}</option>
                <option value="pending_self">{{ __('Pending Self') }}</option>
                <option value="pending_manager">{{ __('Pending Manager') }}</option>
                <option value="pending_360">{{ __('Pending 360') }}</option>
                <option value="completed">{{ __('Completed') }}</option>
            </x-select>
        </div>
        <div>
            <x-select class="w-full" wire:model.live="divisionFilter">
                <option value="">{{ __('Semua Divisi') }}</option>
                @foreach ($divisions as $division)
                    <option value="{{ $division->id }}">{{ $division->name }}</option>
                @endforeach
            </x-select>
        </div>
    </div>

    <!-- Assessments Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th scope="col" class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        {{ __('Karyawan') }}
                    </th>
                    <th scope="col" class="hidden md:table-cell px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        {{ __('Cycle') }}
                    </th>
                    <th scope="col" class="hidden sm:table-cell px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        {{ __('Divisi') }}
                    </th>
                    <th scope="col" class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        {{ __('Status') }}
                    </th>
                    <th scope="col" class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        {{ __('Score') }}
                    </th>
                    <th scope="col" class="relative px-3 py-2 sm:px-6 sm:py-3">
                        <span class="sr-only">{{ __('Actions') }}</span>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                @forelse ($assessments as $assessment)
                    <tr>
                        <td class="px-3 py-3 sm:px-6 sm:py-4 whitespace-nowrap">
                            <div class="text-xs sm:text-sm font-medium text-gray-900 dark:text-white">
                                {{ $assessment->user->name }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $assessment->user->nip ?? '-' }}
                            </div>
                        </td>
                        <td class="hidden md:table-cell px-3 py-3 sm:px-6 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-500 dark:text-gray-300">
                            {{ $assessment->reviewCycle->name }}
                        </td>
                        <td class="hidden sm:table-cell px-3 py-3 sm:px-6 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-500 dark:text-gray-300">
                            {{ $assessment->user->division->name ?? '-' }}
                        </td>
                        <td class="px-3 py-3 sm:px-6 sm:py-4 whitespace-nowrap text-xs sm:text-sm">
                            @php
                                $statusColors = [
                                    'pending_self' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                    'pending_manager' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                    'pending_360' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
                                    'completed' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                ];
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$assessment->status] ?? 'bg-gray-100' }}">
                                {{ ucfirst(str_replace('_', ' ', $assessment->status)) }}
                            </span>
                        </td>
                        <td class="px-3 py-3 sm:px-6 sm:py-4 whitespace-nowrap text-xs sm:text-sm">
                            @if ($assessment->overall_score)
                                <span class="font-semibold 
                                    @if ($assessment->overall_score >= 80) text-green-600 dark:text-green-400
                                    @elseif ($assessment->overall_score >= 60) text-blue-600 dark:text-blue-400
                                    @elseif ($assessment->overall_score >= 40) text-yellow-600 dark:text-yellow-400
                                    @else text-red-600 dark:text-red-400 @endif">
                                    {{ number_format($assessment->overall_score, 1) }}
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-3 py-3 sm:px-6 sm:py-4 whitespace-nowrap text-right text-xs sm:text-sm font-medium">
                            <div class="flex flex-col sm:flex-row justify-end gap-1 sm:gap-2">
                                <x-button href="{{ route('performance.results', $assessment->id) }}" class="text-xs w-full sm:w-auto" target="_blank">
                                    {{ __('Detail') }}
                                </x-button>
                                @if ($assessment->status === 'pending_manager')
                                    <x-secondary-button href="{{ route('performance.manager', $assessment->review_cycle_id) }}?user={{ $assessment->user_id }}" class="text-xs w-full sm:w-auto">
                                        {{ __('Review') }}
                                    </x-secondary-button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center dark:text-gray-300">
                            {{ __('Tidak ada assessment ditemukan.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $assessments->links() }}
    </div>
</div>
