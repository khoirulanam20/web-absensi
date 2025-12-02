<div>
  <div class="mb-4 flex items-center justify-between">
    <h3 class="text-lg font-semibold leading-tight text-gray-800 dark:text-gray-200">
      Setting Gaji Karyawan
    </h3>
    <div class="flex items-center gap-3">
      <x-input type="text" class="w-64" wire:model.live="search" placeholder="Cari karyawan..." />
    </div>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
      <thead class="bg-gray-50 dark:bg-gray-900">
        <tr>
          <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Nama</th>
          <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">NIP</th>
          <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Divisi</th>
          <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Jabatan</th>
          <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
        @foreach ($users as $user)
          <tr>
            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $user->name }}</td>
            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $user->nip ?? '-' }}</td>
            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $user->division?->name ?? '-' }}</td>
            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $user->jobTitle?->name ?? '-' }}</td>
            <td class="px-4 py-3 text-sm">
              <x-button 
                type="button"
                wire:click="$set('selectedUserIdForModal', '{{ $user->id }}')">
                Set Gaji
              </x-button>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="mt-4">
    {{ $users->links() }}
  </div>

  <!-- Salary Setup Modal -->
  <x-dialog-modal wire:model="showSalaryModal">
    <x-slot name="title">
      Setting Gaji - {{ $selectedUserName }}
    </x-slot>
    <form wire:submit="save">
      <x-slot name="content">
        <div class="mb-4">
          <x-label for="basicSalary">Gaji Pokok</x-label>
          <x-input id="basicSalary" class="mt-1 block w-full" type="number" wire:model="basicSalary" min="0" step="0.01" required />
          @error('basicSalary')
            <x-input-error for="basicSalary" class="mt-2" message="{{ $message }}" />
          @enderror
        </div>

        <div class="mb-4">
          <div class="flex items-center justify-between mb-2">
            <x-label>Komponen Lain</x-label>
            <x-button type="button" wire:click="addComponent" class="text-xs">
              + Tambah Komponen
            </x-button>
          </div>

          @foreach ($salaryComponents as $index => $component)
            <div class="mb-3 flex gap-2">
              <div class="flex-1">
                <x-select class="w-full" wire:model="salaryComponents.{{ $index }}.component_id">
                  <option value="">Pilih Komponen</option>
                  @foreach ($availableComponents as $comp)
                    <option value="{{ $comp->id }}">{{ $comp->name }} ({{ $comp->type === 'earning' ? 'Tunjangan' : 'Potongan' }})</option>
                  @endforeach
                </x-select>
                @error("salaryComponents.{$index}.component_id")
                  <x-input-error class="mt-1" message="{{ $message }}" />
                @enderror
              </div>
              <div class="w-48">
                <x-input type="number" class="w-full" wire:model="salaryComponents.{{ $index }}.amount" min="0" step="0.01" placeholder="Nominal" />
                @error("salaryComponents.{$index}.amount")
                  <x-input-error class="mt-1" message="{{ $message }}" />
                @enderror
              </div>
              <x-danger-button type="button" wire:click="removeComponent({{ $index }})">
                Hapus
              </x-danger-button>
            </div>
          @endforeach
        </div>
      </x-slot>
      <x-slot name="footer">
        <x-secondary-button wire:click="$toggle('showSalaryModal')" wire:loading.attr="disabled">
          {{ __('Cancel') }}
        </x-secondary-button>
        <x-button class="ml-2" wire:click="save" wire:loading.attr="disabled">
          {{ __('Save') }}
        </x-button>
      </x-slot>
    </form>
  </x-dialog-modal>
</div>
