<div>
  <div class="mb-4 flex items-center justify-between">
    <h3 class="text-lg font-semibold leading-tight text-gray-800 dark:text-gray-200">
      Master Komponen Gaji
    </h3>
    <x-button wire:click="showCreating">
      <x-heroicon-o-plus class="mr-2 h-4 w-4" /> Tambah Komponen
    </x-button>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
      <thead class="bg-gray-50 dark:bg-gray-900">
        <tr>
          <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Nama Komponen</th>
          <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Tipe</th>
          <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Hitungan</th>
          <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Taxable</th>
          <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Status</th>
          <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
        @foreach ($components as $comp)
          <tr wire:key="component-{{ $comp->id }}">
            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $comp->name }}</td>
            <td class="px-4 py-3 text-sm">
              @if ($comp->type === 'earning')
                <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-300">
                  Tunjangan
                </span>
              @else
                <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:bg-red-900 dark:text-red-300">
                  Potongan
                </span>
              @endif
            </td>
            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
              @if ($comp->is_daily)
                <span class="text-blue-600 dark:text-blue-400">Harian</span>
              @else
                <span class="text-gray-600 dark:text-gray-400">Tetap</span>
              @endif
            </td>
            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
              @if ($comp->is_taxable)
                <span class="text-orange-600 dark:text-orange-400">Ya</span>
              @else
                <span class="text-gray-400">Tidak</span>
              @endif
            </td>
            <td class="px-4 py-3 text-sm">
              @if ($comp->is_active)
                <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-300">
                  Aktif
                </span>
              @else
                <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800 dark:bg-gray-900 dark:text-gray-300">
                  Nonaktif
                </span>
              @endif
            </td>
            <td class="px-4 py-3 text-sm">
              <div class="flex gap-2">
                <x-button wire:click="edit('{{ $comp->id }}')" class="text-xs" type="button">
                  Edit
                </x-button>
                <x-danger-button wire:click="confirmDeletion('{{ $comp->id }}', '{{ addslashes($comp->name) }}')" class="text-xs" type="button">
                  Hapus
                </x-danger-button>
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  @if ($components->isEmpty())
    <div class="my-4 text-center text-sm text-gray-600 dark:text-gray-400">
      Belum ada komponen gaji. Silakan tambah komponen baru.
    </div>
  @endif

  <!-- Delete Confirmation Modal -->
  <x-confirmation-modal wire:model="confirmingDeletion">
    <x-slot name="title">
      Hapus Komponen Gaji
    </x-slot>
    <x-slot name="content">
      Apakah Anda yakin ingin menghapus <b>{{ $deleteName }}</b>?
    </x-slot>
    <x-slot name="footer">
      <x-secondary-button wire:click="$toggle('confirmingDeletion')" wire:loading.attr="disabled">
        {{ __('Cancel') }}
      </x-secondary-button>
      <x-danger-button class="ml-2" wire:click="delete" wire:loading.attr="disabled">
        {{ __('Confirm') }}
      </x-danger-button>
    </x-slot>
  </x-confirmation-modal>

  <!-- Create Modal -->
  <x-dialog-modal wire:model="creating">
    <x-slot name="title">
      Tambah Komponen Gaji
    </x-slot>
    <form wire:submit="store">
      <x-slot name="content">
        <div>
          <x-label for="name">Nama Komponen</x-label>
          <x-input id="name" class="mt-1 block w-full" type="text" wire:model="name" placeholder="Contoh: Tunjangan Transport" required />
          @error('name')
            <x-input-error for="name" class="mt-2" message="{{ $message }}" />
          @enderror
        </div>
        <div class="mt-4">
          <x-label for="type">Tipe</x-label>
          <x-select id="type" class="mt-1 block w-full" wire:model="type" required>
            <option value="earning">Tunjangan (Penerimaan)</option>
            <option value="deduction">Potongan</option>
          </x-select>
          @error('type')
            <x-input-error for="type" class="mt-2" message="{{ $message }}" />
          @enderror
        </div>
        <div class="mt-4 flex flex-col gap-3">
          <label class="flex items-center">
            <input type="checkbox" wire:model="is_daily" class="mr-2">
            <span class="text-sm text-gray-700 dark:text-gray-300">Harian (Dikalikan jumlah kehadiran)</span>
          </label>
          <label class="flex items-center">
            <input type="checkbox" wire:model="is_taxable" class="mr-2">
            <span class="text-sm text-gray-700 dark:text-gray-300">Taxable (Untuk perhitungan PPh 21)</span>
          </label>
          <label class="flex items-center">
            <input type="checkbox" wire:model="is_active" class="mr-2" checked>
            <span class="text-sm text-gray-700 dark:text-gray-300">Aktif</span>
          </label>
        </div>
      </x-slot>
      <x-slot name="footer">
        <x-secondary-button wire:click="$toggle('creating')" wire:loading.attr="disabled">
          {{ __('Cancel') }}
        </x-secondary-button>
        <x-button class="ml-2" wire:click="store" wire:loading.attr="disabled">
          {{ __('Save') }}
        </x-button>
      </x-slot>
    </form>
  </x-dialog-modal>

  <!-- Edit Modal -->
  <x-dialog-modal wire:model="editing">
    <x-slot name="title">
      Edit Komponen Gaji
    </x-slot>
    <form wire:submit="update">
      <x-slot name="content">
        <div>
          <x-label for="name">Nama Komponen</x-label>
          <x-input id="name" class="mt-1 block w-full" type="text" wire:model="name" required />
          @error('name')
            <x-input-error for="name" class="mt-2" message="{{ $message }}" />
          @enderror
        </div>
        <div class="mt-4">
          <x-label for="type">Tipe</x-label>
          <x-select id="type" class="mt-1 block w-full" wire:model="type" required>
            <option value="earning">Tunjangan (Penerimaan)</option>
            <option value="deduction">Potongan</option>
          </x-select>
          @error('type')
            <x-input-error for="type" class="mt-2" message="{{ $message }}" />
          @enderror
        </div>
        <div class="mt-4 flex flex-col gap-3">
          <label class="flex items-center">
            <input type="checkbox" wire:model="is_daily" class="mr-2">
            <span class="text-sm text-gray-700 dark:text-gray-300">Harian (Dikalikan jumlah kehadiran)</span>
          </label>
          <label class="flex items-center">
            <input type="checkbox" wire:model="is_taxable" class="mr-2">
            <span class="text-sm text-gray-700 dark:text-gray-300">Taxable (Untuk perhitungan PPh 21)</span>
          </label>
          <label class="flex items-center">
            <input type="checkbox" wire:model="is_active" class="mr-2">
            <span class="text-sm text-gray-700 dark:text-gray-300">Aktif</span>
          </label>
        </div>
      </x-slot>
      <x-slot name="footer">
        <x-secondary-button wire:click="$toggle('editing')" wire:loading.attr="disabled">
          {{ __('Cancel') }}
        </x-secondary-button>
        <x-button class="ml-2" wire:click="update" wire:loading.attr="disabled">
          {{ __('Save') }}
        </x-button>
      </x-slot>
    </form>
  </x-dialog-modal>
</div>
