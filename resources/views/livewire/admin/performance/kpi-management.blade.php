<div>
    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h3 class="text-base sm:text-lg font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('KPI Management') }}
        </h3>
        <x-button wire:click="showCreating" class="w-full sm:w-auto text-sm">
            <x-heroicon-o-plus class="mr-2 h-4 w-4 sm:h-5 sm:w-5" />
            {{ __('Add New KPI') }}
        </x-button>
    </div>

    <div class="mb-4">
        <x-input type="text" class="w-full sm:w-64" wire:model.live.debounce.300ms="search" placeholder="{{ __('Search KPIs...') }}" />
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th scope="col" class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        {{ __('Code') }}
                    </th>
                    <th scope="col" class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        {{ __('Title') }}
                    </th>
                    <th scope="col" class="hidden md:table-cell px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        {{ __('Unit') }}
                    </th>
                    <th scope="col" class="hidden sm:table-cell px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        {{ __('Target') }}
                    </th>
                    <th scope="col" class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        {{ __('Status') }}
                    </th>
                    <th scope="col" class="relative px-3 py-2 sm:px-6 sm:py-3">
                        <span class="sr-only">{{ __('Actions') }}</span>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                @forelse ($kpis as $kpi)
                    <tr>
                        <td class="px-3 py-3 sm:px-6 sm:py-4 whitespace-nowrap text-xs sm:text-sm font-medium text-gray-900 dark:text-white">
                            {{ $kpi->code }}
                        </td>
                        <td class="px-3 py-3 sm:px-6 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-500 dark:text-gray-300">
                            {{ $kpi->title }}
                        </td>
                        <td class="hidden md:table-cell px-3 py-3 sm:px-6 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-500 dark:text-gray-300">
                            {{ $kpi->unit ?? '-' }}
                        </td>
                        <td class="hidden sm:table-cell px-3 py-3 sm:px-6 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-500 dark:text-gray-300">
                            {{ $kpi->default_target ? number_format($kpi->default_target, 2) : '-' }}
                        </td>
                        <td class="px-3 py-3 sm:px-6 sm:py-4 whitespace-nowrap text-xs sm:text-sm">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if ($kpi->is_active) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif">
                                {{ $kpi->is_active ? __('Active') : __('Inactive') }}
                            </span>
                        </td>
                        <td class="px-3 py-3 sm:px-6 sm:py-4 whitespace-nowrap text-right text-xs sm:text-sm font-medium">
                            <div class="flex flex-col sm:flex-row justify-end gap-1 sm:gap-2">
                                <x-button wire:click="showEditing({{ $kpi->id }})" class="text-xs w-full sm:w-auto">
                                    {{ __('Edit') }}
                                </x-button>
                                <x-secondary-button wire:click="showAssigning({{ $kpi->id }})" class="text-xs w-full sm:w-auto">
                                    {{ __('Assign') }}
                                </x-secondary-button>
                                <x-danger-button wire:click="confirmDeletion({{ $kpi->id }})" class="text-xs w-full sm:w-auto">
                                    {{ __('Delete') }}
                                </x-danger-button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center dark:text-gray-300">
                            {{ __('No KPIs found.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $kpis->links() }}
    </div>

    <!-- Create/Edit KPI Modal -->
    <x-dialog-modal wire:model.live="showModal">
        <x-slot name="title">
            {{ $editingId ? __('Edit KPI') : __('Add New KPI') }}
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4">
                <div>
                    <x-label for="code" value="{{ __('Code') }}" />
                    <x-input id="code" type="text" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700" wire:model="code" disabled />
                    <x-input-error for="code" class="mt-2" />
                </div>

                <div>
                    <x-label for="title" value="{{ __('Title') }}" />
                    <x-input id="title" type="text" class="mt-1 block w-full" wire:model="title" />
                    <x-input-error for="title" class="mt-2" />
                </div>

                <div>
                    <x-label for="description" value="{{ __('Description') }}" />
                    <x-textarea id="description" class="mt-1 block w-full" wire:model="description"></x-textarea>
                    <x-input-error for="description" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-label for="unit" value="{{ __('Unit') }}" />
                        <x-input id="unit" type="text" class="mt-1 block w-full" wire:model="unit" placeholder="%, jumlah, hari" />
                        <x-input-error for="unit" class="mt-2" />
                    </div>

                    <div>
                        <x-label for="default_target" value="{{ __('Default Target') }}" />
                        <x-input id="default_target" type="number" step="0.01" class="mt-1 block w-full" wire:model="default_target" />
                        <x-input-error for="default_target" class="mt-2" />
                    </div>
                </div>

                <div>
                    <label class="flex items-center">
                        <input type="checkbox" wire:model="is_active" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Active') }}</span>
                    </label>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeModal" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ms-3" wire:click="save" wire:loading.attr="disabled">
                {{ $editingId ? __('Update') : __('Create') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>

    <!-- Assign KPI to Job Title Modal -->
    <x-dialog-modal wire:model.live="showAssignModal">
        <x-slot name="title">
            {{ __('Assign KPI to Job Title') }}
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4">
                <div>
                    <x-label for="selectedJobTitleId" value="{{ __('Job Title') }}" />
                    <x-select id="selectedJobTitleId" class="mt-1 block w-full" wire:model="selectedJobTitleId">
                        <option value="">{{ __('Select Job Title') }}</option>
                        @foreach ($jobTitles as $jobTitle)
                            <option value="{{ $jobTitle->id }}">{{ $jobTitle->name }}</option>
                        @endforeach
                    </x-select>
                    <x-input-error for="selectedJobTitleId" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-label for="default_weight" value="{{ __('Default Weight (%)') }}" />
                        <x-input id="default_weight" type="number" step="0.01" min="0" max="100" class="mt-1 block w-full" wire:model="default_weight" />
                        <x-input-error for="default_weight" class="mt-2" />
                    </div>

                    <div>
                        <x-label for="default_target_role" value="{{ __('Default Target') }}" />
                        <x-input id="default_target_role" type="number" step="0.01" class="mt-1 block w-full" wire:model="default_target_role" />
                        <x-input-error for="default_target_role" class="mt-2" />
                    </div>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeModal" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ms-3" wire:click="assignToJobTitle" wire:loading.attr="disabled">
                {{ __('Assign') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>

    <!-- Delete Confirmation Modal -->
    <x-confirmation-modal wire:model.live="confirmingDeletion">
        <x-slot name="title">
            {{ __('Delete KPI') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to delete this KPI? This action cannot be undone.') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('confirmingDeletion', false)" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ms-3" wire:click="delete" wire:loading.attr="disabled">
                {{ __('Delete') }}
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>
</div>
