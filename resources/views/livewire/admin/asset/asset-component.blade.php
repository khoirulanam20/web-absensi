<div>
  <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h3 class="text-base sm:text-lg font-semibold leading-tight text-gray-800 dark:text-gray-200">
      Data Aset Perusahaan
    </h3>
    <x-button wire:click="showCreating" class="w-full sm:w-auto text-sm">
      <x-heroicon-o-plus class="mr-2 h-4 w-4" /> Tambah Aset
    </x-button>
  </div>

  <div class="mb-4">
    <x-input type="text" class="w-full sm:w-64" wire:model.live.debounce.300ms="search" placeholder="Cari Kode, Nama, atau Serial Number..." />
  </div>

  <div class="overflow-x-auto">
    <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
      <thead class="bg-gray-50 dark:bg-gray-900">
        <tr>
          <th scope="col" class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">
            Kode
          </th>
          <th scope="col" class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">
            Nama
          </th>
          <th scope="col" class="hidden sm:table-cell px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">
            Kategori
          </th>
          <th scope="col" class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">
            Status
          </th>
          <th scope="col" class="hidden md:table-cell px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">
            Lokasi
          </th>
          <th scope="col" class="hidden md:table-cell px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">
            Diberikan Kepada
          </th>
          <th scope="col" class="relative px-6 py-3">
            <span class="sr-only">Actions</span>
          </th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
        @forelse ($assets as $asset)
          <tr>
            <td class="px-3 py-3 sm:px-6 sm:py-4 text-xs sm:text-sm font-medium text-gray-900 dark:text-white">
              {{ $asset->asset_code }}
            </td>
            <td class="px-3 py-3 sm:px-6 sm:py-4 text-xs sm:text-sm text-gray-900 dark:text-white">
              {{ $asset->name }}
            </td>
            <td class="hidden sm:table-cell px-3 py-3 sm:px-6 sm:py-4 text-xs sm:text-sm text-gray-500 dark:text-gray-300">
              {{ $categories[$asset->category] ?? $asset->category }}
            </td>
            <td class="px-3 py-3 sm:px-6 sm:py-4 text-xs sm:text-sm">
              @php
                $statusColors = [
                  'available' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                  'in_use' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                  'maintenance' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                  'damaged' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                  'disposed' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
                ];
              @endphp
              <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$asset->status] ?? 'bg-gray-100' }}">
                {{ $statuses[$asset->status] ?? $asset->status }}
              </span>
            </td>
            <td class="hidden md:table-cell px-3 py-3 sm:px-6 sm:py-4 text-xs sm:text-sm text-gray-500 dark:text-gray-300">
              {{ $asset->location ?? '-' }}
            </td>
            <td class="hidden md:table-cell px-3 py-3 sm:px-6 sm:py-4 text-xs sm:text-sm text-gray-500 dark:text-gray-300">
              {{ $asset->assignedUser ? $asset->assignedUser->name : '-' }}
            </td>
            <td class="relative flex flex-col sm:flex-row justify-end gap-1 sm:gap-2 px-3 py-3 sm:px-6 sm:py-4">
              <x-button wire:click="showEditing({{ $asset->id }})" class="text-xs w-full sm:w-auto">
                Edit
              </x-button>
              <x-danger-button wire:click="confirmDeletion({{ $asset->id }}, '{{ $asset->name }}')" class="text-xs w-full sm:w-auto">
                Delete
              </x-danger-button>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="px-6 py-4 text-sm text-center text-gray-500 dark:text-gray-300">
              Tidak ada aset ditemukan.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-4">
    {{ $assets->links() }}
  </div>

  <!-- Create/Edit Modal -->
  <x-dialog-modal wire:model="showModal" maxWidth="4xl">
    <x-slot name="title">
      {{ $editingId ? 'Edit Aset' : 'Tambah Aset Baru' }}
    </x-slot>

    <x-slot name="content">
      <div class="space-y-4">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
          <div>
            <x-label for="asset_code" value="Kode Aset" />
            <x-input id="asset_code" type="text" class="mt-1 block w-full" wire:model="asset_code" />
            <x-input-error for="asset_code" class="mt-2" />
          </div>
          <div>
            <x-label for="name" value="Nama Aset *" />
            <x-input id="name" type="text" class="mt-1 block w-full" wire:model="name" />
            <x-input-error for="name" class="mt-2" />
          </div>
        </div>

        <div>
          <x-label for="description" value="Deskripsi" />
          <x-textarea id="description" class="mt-1 block w-full" wire:model="description" rows="3" />
          <x-input-error for="description" class="mt-2" />
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
          <div>
            <x-label for="category" value="Kategori *" />
            <x-select id="category" class="mt-1 block w-full" wire:model="category">
              @foreach ($categories as $key => $label)
                <option value="{{ $key }}">{{ $label }}</option>
              @endforeach
            </x-select>
            <x-input-error for="category" class="mt-2" />
          </div>
          <div>
            <x-label for="brand" value="Merek" />
            <x-input id="brand" type="text" class="mt-1 block w-full" wire:model="brand" />
            <x-input-error for="brand" class="mt-2" />
          </div>
          <div>
            <x-label for="model" value="Model" />
            <x-input id="model" type="text" class="mt-1 block w-full" wire:model="model" />
            <x-input-error for="model" class="mt-2" />
          </div>
        </div>

        <div>
          <x-label for="serial_number" value="Nomor Seri" />
          <x-input id="serial_number" type="text" class="mt-1 block w-full" wire:model="serial_number" />
          <x-input-error for="serial_number" class="mt-2" />
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
          <div>
            <x-label for="purchase_date" value="Tanggal Pembelian" />
            <x-input id="purchase_date" type="date" class="mt-1 block w-full" wire:model="purchase_date" />
            <x-input-error for="purchase_date" class="mt-2" />
          </div>
          <div>
            <x-label for="purchase_price" value="Harga Pembelian" />
            <x-input id="purchase_price" type="number" step="0.01" class="mt-1 block w-full" wire:model="purchase_price" />
            <x-input-error for="purchase_price" class="mt-2" />
          </div>
          <div>
            <x-label for="current_value" value="Nilai Saat Ini" />
            <x-input id="current_value" type="number" step="0.01" class="mt-1 block w-full" wire:model="current_value" />
            <x-input-error for="current_value" class="mt-2" />
          </div>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
          <div>
            <x-label for="status" value="Status *" />
            <x-select id="status" class="mt-1 block w-full" wire:model="status">
              @foreach ($statuses as $key => $label)
                <option value="{{ $key }}">{{ $label }}</option>
              @endforeach
            </x-select>
            <x-input-error for="status" class="mt-2" />
          </div>
          <div>
            <x-label for="location" value="Lokasi" />
            <x-input id="location" type="text" class="mt-1 block w-full" wire:model="location" />
            <x-input-error for="location" class="mt-2" />
          </div>
          <div>
            <x-label for="assigned_to" value="Diberikan Kepada" />
            <x-select id="assigned_to" class="mt-1 block w-full" wire:model="assigned_to">
              <option value="">Pilih Karyawan</option>
              @foreach ($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
              @endforeach
            </x-select>
            <x-input-error for="assigned_to" class="mt-2" />
          </div>
        </div>

        <div>
          <x-label for="assigned_date" value="Tanggal Penugasan" />
          <x-input id="assigned_date" type="date" class="mt-1 block w-full" wire:model="assigned_date" />
          <x-input-error for="assigned_date" class="mt-2" />
        </div>

        <div>
          <x-label for="notes" value="Catatan" />
          <x-textarea id="notes" class="mt-1 block w-full" wire:model="notes" rows="3" />
          <x-input-error for="notes" class="mt-2" />
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

  <!-- Delete Confirmation Modal -->
  <x-confirmation-modal wire:model.live="confirmingDeletion">
    <x-slot name="title">
      {{ __('Hapus Aset') }}
    </x-slot>

    <x-slot name="content">
      {{ __('Apakah Anda yakin ingin menghapus aset') }} <strong>{{ $deleteName }}</strong>? {{ __('Tindakan ini tidak dapat dibatalkan.') }}
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
