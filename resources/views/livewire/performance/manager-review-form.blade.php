<div>
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">
            {{ __('Manager Review') }} - {{ $cycle->name }}
        </h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            {{ __('Periode') }}: {{ $cycle->start_date->format('d/m/Y') }} - {{ $cycle->end_date->format('d/m/Y') }}
        </p>
    </div>

    @if ($users->isEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center">
            <x-heroicon-o-information-circle class="mx-auto h-12 w-12 text-gray-400 mb-4" />
            <p class="text-gray-500 dark:text-gray-400">
                {{ __('Tidak ada assessment yang perlu di-review saat ini.') }}
            </p>
        </div>
    @else
        <div class="mb-4">
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                {{ __('Pilih karyawan yang ingin di-review:') }}
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($users as $user)
                @php
                    $assessment = $pendingAssessments->where('user_id', $user->id)->first();
                @endphp
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 hover:shadow-lg transition">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-white">
                                {{ $user->name }}
                            </h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $user->nip ?? '-' }}
                            </p>
                            @if ($user->division)
                                <p class="text-xs text-gray-400 dark:text-gray-500">
                                    {{ $user->division->name }}
                                </p>
                            @endif
                        </div>
                    </div>
                    <x-button wire:click="showReview('{{ $user->id }}')" class="w-full text-sm">
                        <x-heroicon-o-pencil class="mr-2 h-4 w-4" />
                        {{ __('Review') }}
                    </x-button>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Review Modal -->
    <x-dialog-modal wire:model.live="showReviewModal" maxWidth="5xl">
        <x-slot name="title">
            {{ __('Manager Review') }} - {{ $selectedAssessment && $selectedAssessment->user ? $selectedAssessment->user->name : '' }}
        </x-slot>

        <x-slot name="content">
            @if ($saving)
                <div class="mb-4 p-4 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded">
                    {{ __('Menyimpan...') }}
                </div>
            @endif

            <div class="space-y-6 max-h-96 overflow-y-auto">
                @foreach ($items as $item)
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <h4 class="text-base font-semibold text-gray-900 dark:text-white mb-2">
                            {{ $item['kpi_title'] }}
                        </h4>
                        @if ($item['kpi_description'])
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                                {{ $item['kpi_description'] }}
                            </p>
                        @endif
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Target') }}:</p>
                                <p class="font-medium">{{ $item['target'] }} {{ $item['unit'] ?? '' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Self Score') }}:</p>
                                <p class="font-medium text-blue-600 dark:text-blue-400">{{ $item['self_score'] ?? '-' }}</p>
                            </div>
                        </div>

                        @if ($item['comment'])
                            <div class="mb-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded border border-blue-200 dark:border-blue-800">
                                <p class="text-xs font-semibold text-blue-900 dark:text-blue-200 mb-1">{{ __('Komentar Karyawan') }}:</p>
                                <p class="text-sm text-blue-800 dark:text-blue-300">{{ $item['comment'] }}</p>
                            </div>
                        @endif

                        <div class="mb-4">
                            <x-label for="manager_score_{{ $item['assessment_item_id'] }}" value="{{ __('Manager Score (0-100)') }}" />
                            <x-input 
                                id="manager_score_{{ $item['assessment_item_id'] }}" 
                                type="number" 
                                min="0" 
                                max="100" 
                                step="0.01"
                                class="mt-1 block w-full" 
                                wire:model="items.{{ $item['assessment_item_id'] }}.manager_score" 
                            />
                            <x-input-error for="items.{{ $item['assessment_item_id'] }}.manager_score" class="mt-2" />
                            <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                <p><strong>Panduan:</strong></p>
                                <ul class="list-disc list-inside ml-2 space-y-1">
                                    <li><span class="text-red-600 dark:text-red-400">0-40:</span> Belum mencapai target</li>
                                    <li><span class="text-yellow-600 dark:text-yellow-400">41-60:</span> Mencapai sebagian target</li>
                                    <li><span class="text-green-600 dark:text-green-400">61-80:</span> Mencapai target dengan baik</li>
                                    <li><span class="text-blue-600 dark:text-blue-400">81-100:</span> Melebihi target</li>
                                </ul>
                            </div>
                        </div>

                        <div>
                            <x-label for="manager_comment_{{ $item['assessment_item_id'] }}" value="{{ __('Manager Comment / Feedback') }}" />
                            <x-textarea 
                                id="manager_comment_{{ $item['assessment_item_id'] }}" 
                                class="mt-1 block w-full" 
                                wire:model="items.{{ $item['assessment_item_id'] }}.manager_comment"
                                placeholder="Berikan feedback konstruktif untuk karyawan..."
                                rows="3"
                            ></x-textarea>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                {{ __('Berikan feedback yang konstruktif, jelaskan alasan score yang diberikan, dan saran perbaikan jika ada.') }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeModal" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ms-3" wire:click="save" wire:loading.attr="disabled">
                {{ __('Save & Submit') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
