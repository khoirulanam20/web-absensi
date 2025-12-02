<div class="p-6 lg:p-8">
  <x-button class="mb-4 mr-2" href="{{ route('admin.location-settings.create') }}">
    Tambah Lokasi Baru
  </x-button>
  
  <div class="grid grid-cols-1 gap-4 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
    @foreach ($locationSettings as $location)
      <div
        class="flex flex-col rounded-lg bg-white p-4 shadow hover:bg-gray-100 dark:bg-gray-800 dark:shadow-gray-600 hover:dark:bg-gray-700">
        
        <h3 class="mb-3 text-center text-lg font-semibold leading-tight text-gray-800 dark:text-white">
          {{ $location->name }}
          @if ($location->is_active)
            <span class="ml-2 inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-300">
              Aktif
            </span>
          @else
            <span class="ml-2 inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800 dark:bg-gray-900 dark:text-gray-300">
              Nonaktif
            </span>
          @endif
        </h3>
        
        <ul class="mb-4 list-disc pl-4 dark:text-gray-400">
          <li>
            <a href="https://www.google.com/maps/search/?api=1&query={{ $location->latitude }},{{ $location->longitude }}"
              target="_blank" class="hover:text-blue-500 hover:underline">
              {{ __('Koordinat') }}: {{ $location->latitude }}, {{ $location->longitude }}
            </a>
          </li>
          <li> {{ __('Radius (meter)') }}: {{ $location->radius }}m</li>
        </ul>

        <div class="mt-auto flex items-center justify-center gap-2">
          <x-button href="{{ route('admin.location-settings.edit', $location->id) }}">
            Edit
          </x-button>
          <x-danger-button wire:click="confirmDeletion({{ $location->id }}, '{{ $location->name }}')">
            Hapus
          </x-danger-button>
        </div>
      </div>
    @endforeach
  </div>

  @if ($locationSettings->isEmpty())
    <div class="text-center py-8 text-gray-600 dark:text-gray-400">
      Belum ada pengaturan lokasi. Silakan tambah lokasi baru.
    </div>
  @endif

  <x-confirmation-modal wire:model="confirmingDeletion">
    <x-slot name="title">
      Hapus Pengaturan Lokasi
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
</div>
