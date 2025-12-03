<div>
    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h3 class="text-base sm:text-lg font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Review Cycles') }}
        </h3>
        <x-button wire:click="showCreating" class="w-full sm:w-auto text-sm">
            <x-heroicon-o-plus class="mr-2 h-4 w-4 sm:h-5 sm:w-5" />
            {{ __('Add New Cycle') }}
        </x-button>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th scope="col" class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        {{ __('Name') }}
                    </th>
                    <th scope="col" class="hidden md:table-cell px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        {{ __('Period') }}
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
                @forelse ($cycles as $cycle)
                    <tr>
                        <td class="px-3 py-3 sm:px-6 sm:py-4 whitespace-nowrap text-xs sm:text-sm font-medium text-gray-900 dark:text-white">
                            {{ $cycle->name }}
                        </td>
                        <td class="hidden md:table-cell px-3 py-3 sm:px-6 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-500 dark:text-gray-300">
                            {{ $cycle->start_date->format('d/m/Y') }} - {{ $cycle->end_date->format('d/m/Y') }}
                        </td>
                        <td class="px-3 py-3 sm:px-6 sm:py-4 whitespace-nowrap text-xs sm:text-sm">
                            @php
                                $statusColors = [
                                    'draft' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                    'open' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                    'closed' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                ];
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$cycle->status] ?? 'bg-gray-100' }}">
                                {{ ucfirst($cycle->status) }}
                            </span>
                        </td>
                        <td class="px-3 py-3 sm:px-6 sm:py-4 whitespace-nowrap text-right text-xs sm:text-sm font-medium">
                            <div class="flex flex-col sm:flex-row justify-end gap-1 sm:gap-2">
                                <x-button wire:click="showEditing({{ $cycle->id }})" class="text-xs w-full sm:w-auto">
                                    {{ __('Edit') }}
                                </x-button>
                                <x-secondary-button wire:click="showAssigning({{ $cycle->id }})" class="text-xs w-full sm:w-auto">
                                    {{ __('Assign Users') }}
                                </x-secondary-button>
                                @if ($cycle->status === 'draft')
                                    <x-button wire:click="openCycle({{ $cycle->id }})" class="text-xs w-full sm:w-auto bg-green-600">
                                        {{ __('Open') }}
                                    </x-button>
                                @elseif ($cycle->status === 'open')
                                    <x-button wire:click="closeCycle({{ $cycle->id }})" class="text-xs w-full sm:w-auto bg-red-600">
                                        {{ __('Close') }}
                                    </x-button>
                                @endif
                                <x-danger-button wire:click="confirmDeletion({{ $cycle->id }})" class="text-xs w-full sm:w-auto">
                                    {{ __('Delete') }}
                                </x-danger-button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center dark:text-gray-300">
                            {{ __('No review cycles found.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $cycles->links() }}
    </div>

    <!-- Create/Edit Cycle Modal -->
    <x-dialog-modal wire:model.live="showModal" maxWidth="4xl">
        <x-slot name="title">
            {{ $editingId ? __('Edit Review Cycle') : __('Add New Review Cycle') }}
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4">
                <div>
                    <x-label for="name" value="{{ __('Name') }}" />
                    <x-input id="name" type="text" class="mt-1 block w-full" wire:model="name" placeholder="Q1-2025, Semester 1 2025" />
                    <x-input-error for="name" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-label for="start_date" value="{{ __('Start Date') }}" />
                        <x-input id="start_date" type="date" class="mt-1 block w-full" wire:model="start_date" />
                        <x-input-error for="start_date" class="mt-2" />
                    </div>

                    <div>
                        <x-label for="end_date" value="{{ __('End Date') }}" />
                        <x-input id="end_date" type="date" class="mt-1 block w-full" wire:model="end_date" />
                        <x-input-error for="end_date" class="mt-2" />
                    </div>
                </div>

                <div>
                    <x-label for="status" value="{{ __('Status') }}" />
                    <x-select id="status" class="mt-1 block w-full" wire:model="status">
                        <option value="draft">{{ __('Draft') }}</option>
                        <option value="open">{{ __('Open') }}</option>
                        <option value="closed">{{ __('Closed') }}</option>
                    </x-select>
                    <x-input-error for="status" class="mt-2" />
                </div>

                <div>
                    <x-label for="description" value="{{ __('Description') }}" />
                    <x-textarea id="description" class="mt-1 block w-full" wire:model="description"></x-textarea>
                    <x-input-error for="description" class="mt-2" />
                </div>

                <div>
                    <label class="flex items-center">
                        <input type="checkbox" wire:model="enable_360_review" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Enable 360 Review') }}</span>
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

    <!-- Assign Users Modal -->
    <x-dialog-modal wire:model.live="showAssignModal" maxWidth="4xl">
        <x-slot name="title">
            {{ __('Assign Users to Review Cycle') }}
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4">
                <div>
                    <x-label value="{{ __('Select by Job Title') }}" />
                    <x-select class="mt-1 block w-full" wire:model="selectedJobTitleIds" multiple>
                        @foreach ($jobTitles as $jobTitle)
                            <option value="{{ $jobTitle->id }}">{{ $jobTitle->name }}</option>
                        @endforeach
                    </x-select>
                </div>

                <div>
                    <x-label value="{{ __('Or Select Users Directly') }}" />
                    <x-select class="mt-1 block w-full" wire:model="selectedUserIds" multiple>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->nip }})</option>
                        @endforeach
                    </x-select>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeModal" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ms-3" wire:click="assignUsers" wire:loading.attr="disabled">
                {{ __('Assign') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>

    <!-- Delete Confirmation Modal -->
    <x-confirmation-modal wire:model.live="confirmingDeletion">
        <x-slot name="title">
            {{ __('Delete Review Cycle') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to delete this review cycle? This action cannot be undone.') }}
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
