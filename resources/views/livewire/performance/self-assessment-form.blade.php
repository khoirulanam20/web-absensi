<div>
    <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                {{ __('Self Assessment') }} - {{ $cycle->name }}
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('Period') }}: {{ $cycle->start_date->format('d/m/Y') }} - {{ $cycle->end_date->format('d/m/Y') }}
            </p>
        </div>
        <x-secondary-button href="{{ route('performance.tutorial') }}" class="w-full sm:w-auto text-sm" target="_blank">
            <x-heroicon-o-question-mark-circle class="mr-2 h-4 w-4" />
            {{ __('Tutorial') }}
        </x-secondary-button>
    </div>

    @if ($saving)
        <div class="mb-4 p-4 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded">
            {{ __('Saving...') }}
        </div>
    @endif

    <form wire:submit.prevent="submit">
        <div class="space-y-6">
            @foreach ($items as $item)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6">
                    <h4 class="text-base font-semibold text-gray-900 dark:text-white mb-2">
                        {{ $item['kpi_title'] }}
                    </h4>
                    @if ($item['kpi_description'])
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            {{ $item['kpi_description'] }}
                        </p>
                    @endif
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Target') }}:</p>
                            <p class="font-medium">{{ $item['target'] }} {{ $item['unit'] ?? '' }}</p>
                        </div>
                    </div>
                    <div class="mb-4">
                        <x-label for="self_score_{{ $item['kpi_assignment_id'] }}" value="{{ __('Self Score (0-100)') }}" />
                        <x-input 
                            id="self_score_{{ $item['kpi_assignment_id'] }}" 
                            type="number" 
                            min="0" 
                            max="100" 
                            step="0.01"
                            class="mt-1 block w-full" 
                            wire:model="items.{{ $item['kpi_assignment_id'] }}.self_score" 
                        />
                        <x-input-error for="items.{{ $item['kpi_assignment_id'] }}.self_score" class="mt-2" />
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
                        <x-label for="comment_{{ $item['kpi_assignment_id'] }}" value="{{ __('Comment / Deskripsi Pencapaian') }}" />
                        <x-textarea 
                            id="comment_{{ $item['kpi_assignment_id'] }}" 
                            class="mt-1 block w-full" 
                            wire:model="items.{{ $item['kpi_assignment_id'] }}.comment"
                            placeholder="Jelaskan pencapaian Anda dengan detail, sertakan data/bukti jika ada..."
                            rows="4"
                        ></x-textarea>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            {{ __('Jelaskan: Apa yang dicapai? Berapa nilai/angka pencapaiannya? Apakah ada bukti?') }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button wire:click="save" wire:loading.attr="disabled">
                {{ __('Save Draft') }}
            </x-secondary-button>
            <x-button type="submit" wire:loading.attr="disabled">
                {{ __('Submit') }}
            </x-button>
        </div>
    </form>
</div>
