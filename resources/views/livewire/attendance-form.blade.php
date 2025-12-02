<div class="w-full">
  @php
    use Illuminate\Support\Carbon;
  @endphp

  @pushOnce('scripts')
    <script>
      // Get user location
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
          function(position) {
            @this.set('currentLiveCoords', [position.coords.latitude, position.coords.longitude]);
            @this.set('latitude', position.coords.latitude);
            @this.set('longitude', position.coords.longitude);
          },
          function(error) {
            console.error('Error getting location:', error);
            @this.set('currentLiveCoords', null);
          }
        );
      } else {
        console.error('Geolocation is not supported by this browser.');
        @this.set('currentLiveCoords', null);
      }

      // Listen for location error events (Livewire 3)
      document.addEventListener('livewire:init', () => {
        Livewire.on('show-location-error', (data) => {
          const message = typeof data === 'string' ? data : (data?.message || 'Terjadi kesalahan pada lokasi');
          alert(message);
        });
      });
    </script>
  @endPushOnce

  <div class="flex flex-col gap-6">
    <!-- Status Card -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
      <div class="p-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
            {{ __('Status Kehadiran') }}
          </h3>
          <span class="text-sm text-gray-600 dark:text-gray-400">
            {{ Carbon::now()->format('d/m/Y H:i') }}
          </span>
        </div>

        @if ($successMsg)
          <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 rounded-md">
            {{ $successMsg }}
          </div>
        @endif

        @if ($attendance)
          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 sm:gap-4 mb-4">
            <div class="bg-blue-100 dark:bg-blue-900 p-4 rounded-lg">
              <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                {{ __('Absen Masuk') }}
              </h4>
              <p class="text-lg font-semibold text-gray-900 dark:text-white">
                {{ $attendance->time_in ? Carbon::parse($attendance->time_in)->format('H:i:s') : '-' }}
              </p>
            </div>
            @php
              $statusLabels = [
                'hadir' => 'Hadir',
                'izin' => 'Izin',
                'sakit' => 'Sakit',
                'cuti' => 'Cuti',
                'present' => 'Hadir',
                'late' => 'Terlambat',
                'excused' => 'Izin',
                'sick' => 'Sakit',
                'absent' => 'Tidak Hadir'
              ];
              $statusLabel = $statusLabels[$attendance->status] ?? ucfirst($attendance->status);
              $statusBgColor = match($attendance->status) {
                'hadir', 'present' => 'bg-green-100 dark:bg-green-900',
                'izin', 'cuti', 'excused' => 'bg-purple-100 dark:bg-purple-900',
                'sakit', 'sick' => 'bg-gray-100 dark:bg-gray-700',
                'late' => 'bg-amber-100 dark:bg-amber-900',
                default => 'bg-red-100 dark:bg-red-900'
              };
            @endphp
            <div class="{{ $statusBgColor }} p-4 rounded-lg">
              <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                {{ __('Status') }}
              </h4>
              <p class="text-lg font-semibold text-gray-900 dark:text-white">
                {{ $statusLabel }}
                @if ($attendance->is_wfh && in_array($attendance->status, ['hadir', 'present']))
                  <span class="text-xs">(WFH)</span>
                @endif
              </p>
            </div>
            <div class="bg-red-100 dark:bg-red-900 p-4 rounded-lg">
              <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                {{ __('Absen Keluar') }}
              </h4>
              <p class="text-lg font-semibold text-gray-900 dark:text-white">
                {{ $attendance->time_out ? Carbon::parse($attendance->time_out)->format('H:i:s') : '-' }}
              </p>
            </div>
          </div>

          @if ($attendance->notes)
            <div class="mb-4 p-4 bg-gray-100 dark:bg-gray-700 rounded-lg">
              <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                {{ __('Catatan') }}
              </h4>
              <p class="text-gray-900 dark:text-white">{{ $attendance->notes }}</p>
            </div>
          @endif
        @else
          <div class="text-center py-8 text-gray-600 dark:text-gray-400">
            {{ __('Belum ada absensi hari ini') }}
          </div>
        @endif
      </div>
    </div>

    <!-- Action Card -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
      <div class="p-4 sm:p-6">
        @if (!$attendance)
          <!-- Belum Absen - Show Check In Button -->
          <div class="text-center">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
              {{ __('Form Absensi') }}
            </h3>
            <button
              wire:click="openCheckInModal"
              class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-base text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
              <x-heroicon-o-clock class="mr-2 h-5 w-5" />
              {{ __('Check In') }}
            </button>
          </div>
        @elseif ($attendance && !$attendance->time_out)
          <!-- Sudah In, Belum Out -->
          <div class="text-center">
            @php
              $isLeaveStatus = in_array($attendance->status, ['izin', 'cuti', 'sakit']);
              $statusLabels = [
                'izin' => 'IZIN',
                'cuti' => 'CUTI',
                'sakit' => 'SAKIT',
              ];
              $statusLabel = $isLeaveStatus ? ($statusLabels[$attendance->status] ?? strtoupper($attendance->status)) : 'SEDANG BEKERJA';
            @endphp
            
            @if ($isLeaveStatus)
              <!-- Status Izin/Cuti/Sakit - Tidak perlu Check Out -->
              <div class="mb-4 p-4 bg-purple-100 dark:bg-purple-900 border border-purple-400 dark:border-purple-700 rounded-lg">
                <p class="text-purple-800 dark:text-purple-200 font-semibold text-lg">
                  {{ __('Status: ') . $statusLabel }}
                </p>
                <p class="text-sm text-purple-700 dark:text-purple-300 mt-2">
                  {{ __('Masuk') }}: {{ $attendance->time_in ? Carbon::parse($attendance->time_in)->format('H:i:s') : '-' }}
                </p>
                @if ($attendance->notes)
                  <div class="mt-3 p-3 bg-purple-50 dark:bg-purple-800 rounded-lg">
                    <p class="text-xs font-medium text-purple-600 dark:text-purple-300 mb-1">
                      {{ __('Catatan') }}:
                    </p>
                    <p class="text-sm text-purple-800 dark:text-purple-200">
                      {{ $attendance->notes }}
                    </p>
                  </div>
                @endif
              </div>
            @else
              <!-- Status Hadir - Bisa Check Out -->
              <div class="mb-4 p-4 bg-yellow-100 dark:bg-yellow-900 border border-yellow-400 dark:border-yellow-700 rounded-lg">
                <p class="text-yellow-800 dark:text-yellow-200 font-semibold">
                  {{ __('Status: SEDANG BEKERJA') }}
                </p>
                <p class="text-sm text-yellow-700 dark:text-yellow-300 mt-1">
                  {{ __('Masuk') }}: {{ $attendance->time_in ? Carbon::parse($attendance->time_in)->format('H:i:s') : '-' }}
                  @if ($attendance->is_wfh)
                    | {{ __('WFH: Ya') }}
                  @else
                    | {{ __('WFO: Ya') }}
                  @endif
                </p>
              </div>
              <button
                wire:click="openCheckOutModal"
                class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-red-600 border border-transparent rounded-md font-semibold text-base text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                <x-heroicon-o-arrow-right-on-rectangle class="mr-2 h-5 w-5" />
                {{ __('Check Out') }}
              </button>
            @endif
          </div>
        @else
          <!-- Sudah Out - Show Summary -->
          <div class="text-center">
            <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 rounded-lg">
              <p class="text-green-800 dark:text-green-200 font-semibold text-lg">
                {{ __('Selesai Bekerja') }}
              </p>
              <p class="text-sm text-green-700 dark:text-green-300 mt-2">
                {{ __('Masuk') }}: {{ $attendance->time_in ? Carbon::parse($attendance->time_in)->format('H:i:s') : '-' }}
                | {{ __('Keluar') }}: {{ $attendance->time_out ? Carbon::parse($attendance->time_out)->format('H:i:s') : '-' }}
              </p>
              @if ($attendance->notes)
                <div class="mt-3 p-3 bg-green-50 dark:bg-green-800 rounded-lg text-left">
                  <p class="text-xs font-medium text-green-600 dark:text-green-300 mb-1">
                    {{ __('Catatan') }}:
                  </p>
                  <p class="text-sm text-green-800 dark:text-green-200 whitespace-pre-line">
                    {{ $attendance->notes }}
                  </p>
                </div>
              @endif
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>

  <!-- Check In Modal -->
  @if ($showModal)
    <x-dialog-modal wire:model="showModal">
      <x-slot name="title">
        {{ __('Form Check In') }}
      </x-slot>

      <x-slot name="content">
        <div class="space-y-4">
          <!-- Shift Selection -->
          <div>
            <x-label for="shift_id" value="{{ __('Shift') }}" />
            <x-select id="shift_id" class="mt-1 block w-full" wire:model="shift_id">
              <option value="">{{ __('Pilih Shift') }}</option>
              @foreach ($shifts as $shift)
                <option value="{{ $shift->id }}">
                  {{ $shift->name . ' | ' . $shift->start_time . ' - ' . $shift->end_time }}
                </option>
              @endforeach
            </x-select>
            <x-input-error for="shift_id" class="mt-2" />
          </div>

          <!-- Status Selection -->
          <div>
            <x-label for="status" value="{{ __('Status Kehadiran') }}" />
            <x-select id="status" class="mt-1 block w-full" wire:model="status">
              <option value="hadir">{{ __('Hadir') }}</option>
              <option value="izin">{{ __('Izin') }}</option>
              <option value="sakit">{{ __('Sakit') }}</option>
              <option value="cuti">{{ __('Cuti') }}</option>
            </x-select>
            <x-input-error for="status" class="mt-2" />
          </div>

          <!-- Lokasi Kerja (Only if Status = Hadir) -->
          @if ($status === 'hadir')
            <div>
              <x-label value="{{ __('Lokasi Kerja') }}" />
              <div class="mt-2 space-y-2">
                <label class="flex items-center">
                  <input type="radio" wire:model="is_wfh" value="0" class="mr-2">
                  <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('WFO (Kantor)') }}</span>
                  <span class="ml-2 text-xs text-orange-600 dark:text-orange-400">* Harus dalam radius</span>
                </label>
                <label class="flex items-center">
                  <input type="radio" wire:model="is_wfh" value="1" class="mr-2">
                  <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('WFH (Rumah)') }}</span>
                  <span class="ml-2 text-xs text-green-600 dark:text-green-400">* Radius tidak berlaku</span>
                </label>
              </div>
              <x-input-error for="is_wfh" class="mt-2" />
              @error('location')
                <div class="mt-2 p-3 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 rounded-md">
                  <p class="text-sm text-red-800 dark:text-red-200 whitespace-pre-line">{{ $message }}</p>
                </div>
              @enderror
            </div>
          @endif

          <!-- Catatan -->
          <div>
            <x-label for="notes" value="{{ __('Catatan') }}" />
            <x-textarea
              id="notes"
              class="mt-1 block w-full"
              wire:model="notes"
              rows="3"
              placeholder="{{ in_array($status, ['izin', 'cuti']) ? __('Alasan ' . $status . ' wajib diisi...') : __('Keterangan tugas hari ini atau alasan...') }}"
            />
            <x-input-error for="notes" class="mt-2" />
            @if ($status === 'izin')
              <p class="mt-1 text-sm font-medium text-red-600 dark:text-red-400">
                {{ __('Catatan wajib diisi untuk status Izin') }}
              </p>
            @elseif ($status === 'cuti')
              <p class="mt-1 text-sm font-medium text-red-600 dark:text-red-400">
                {{ __('Catatan wajib diisi untuk status Cuti') }}
              </p>
            @elseif ($status === 'sakit')
              <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ __('Catatan wajib diisi untuk status Sakit') }}
              </p>
            @endif
          </div>

          <!-- Location Info -->
          @if ($currentLiveCoords)
            <div class="p-3 bg-gray-100 dark:bg-gray-700 rounded-lg">
              <p class="text-sm text-gray-600 dark:text-gray-400">
                <strong>{{ __('Lokasi') }}:</strong> {{ $currentLiveCoords[0] }}, {{ $currentLiveCoords[1] }}
              </p>
            </div>
          @else
            <div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
              <p class="text-sm text-yellow-800 dark:text-yellow-200">
                {{ __('Mengambil lokasi...') }}
              </p>
            </div>
          @endif
        </div>
      </x-slot>

      <x-slot name="footer">
        <x-secondary-button wire:click="closeModal" wire:loading.attr="disabled">
          {{ __('Batal') }}
        </x-secondary-button>

        <x-button
          wire:click="checkIn"
          wire:loading.attr="disabled"
          class="ml-3 bg-green-600 hover:bg-green-700">
          {{ __('Check In') }}
        </x-button>
      </x-slot>
    </x-dialog-modal>
  @endif

  <!-- Check Out Modal -->
  @if ($showCheckOutModal)
    <x-dialog-modal wire:model="showCheckOutModal">
      <x-slot name="title">
        {{ __('Form Check Out') }}
      </x-slot>

      <x-slot name="content">
        <div class="space-y-4">
          <div class="p-4 bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg">
            <p class="text-sm text-blue-800 dark:text-blue-200">
              <strong>{{ __('Masuk') }}:</strong> {{ $attendance->time_in ? Carbon::parse($attendance->time_in)->format('H:i:s') : '-' }}
            </p>
            @if ($attendance->notes)
              <p class="text-sm text-blue-700 dark:text-blue-300 mt-2">
                <strong>{{ __('Catatan Check In') }}:</strong> {{ $attendance->notes }}
              </p>
            @endif
          </div>

          <div>
            <x-label for="checkOutNotes" value="{{ __('Catatan Check Out') }}" />
            <x-textarea 
              id="checkOutNotes" 
              class="mt-1 block w-full" 
              wire:model="checkOutNotes" 
              rows="4"
              placeholder="Tambahkan catatan untuk check out (opsional). Contoh: Menyelesaikan laporan harian, meeting dengan klien, dll." />
            @error('checkOutNotes')
              <x-input-error for="checkOutNotes" class="mt-2" message="{{ $message }}" />
            @enderror
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
              Catatan ini akan ditambahkan ke catatan yang sudah ada dari check in.
            </p>
          </div>
        </div>
      </x-slot>

      <x-slot name="footer">
        <x-secondary-button wire:click="closeCheckOutModal" wire:loading.attr="disabled">
          {{ __('Batal') }}
        </x-secondary-button>

        <x-button
          wire:click="checkOut"
          wire:loading.attr="disabled"
          class="ml-3 bg-red-600 hover:bg-red-700">
          {{ __('Check Out') }}
        </x-button>
      </x-slot>
    </x-dialog-modal>
  @endif
</div>
