<div>
  <form wire:submit="update">
    <!-- Profile Photo -->
    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
      <div x-data="{ photoName: null, photoPreview: null }" class="mb-6">
        <input type="file" id="photo" class="hidden" wire:model.live="form.photo" x-ref="photo"
          x-on:change="
            photoName = $refs.photo.files[0]?.name;
            const reader = new FileReader();
            reader.onload = (e) => {
              photoPreview = e.target.result;
            };
            if ($refs.photo.files[0]) {
              reader.readAsDataURL($refs.photo.files[0]);
            }
          " />

        <x-label for="photo" value="{{ __('Photo') }}" />

        <div class="mt-2 flex items-center gap-4">
          <div x-show="! photoPreview">
            <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}"
              class="h-20 w-20 rounded-full object-cover">
          </div>
          <div x-show="photoPreview" style="display: none;">
            <span class="block h-20 w-20 rounded-full bg-cover bg-center bg-no-repeat"
              x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
            </span>
          </div>
          <div>
            <x-secondary-button type="button" x-on:click.prevent="$refs.photo.click()">
              {{ __('Select A New Photo') }}
            </x-secondary-button>
            @if (Auth::user()->profile_photo_path)
              <x-secondary-button type="button" class="mt-2" wire:click="deleteProfilePhoto">
                {{ __('Remove Photo') }}
              </x-secondary-button>
            @endif
          </div>
        </div>

        @error('form.photo')
          <x-input-error for="form.photo" message="{{ $message }}" class="mt-2" />
        @enderror
      </div>
    @endif

    <!-- Basic Information -->
    <div class="mb-6">
      <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-gray-200">Informasi Dasar</h3>
      
      <div class="mb-4">
        <x-label for="name">Nama</x-label>
        <x-input id="name" class="mt-1 block w-full" type="text" wire:model="form.name" />
        @error('form.name')
          <x-input-error for="form.name" class="mt-2" message="{{ $message }}" />
        @enderror
      </div>

      <div class="mb-4 flex flex-col gap-4 sm:flex-row sm:gap-3">
        <div class="w-full">
          <x-label for="email">{{ __('Email') }}</x-label>
          <x-input id="email" class="mt-1 block w-full" type="email" wire:model="form.email" />
          @error('form.email')
            <x-input-error for="form.email" class="mt-2" message="{{ $message }}" />
          @enderror
        </div>
        @if (Auth::user()->isAdmin)
          <div class="w-full">
            <x-label for="nip">NIP</x-label>
            <x-input id="nip" class="mt-1 block w-full" type="text" wire:model="form.nip" />
            @error('form.nip')
              <x-input-error for="form.nip" class="mt-2" message="{{ $message }}" />
            @enderror
          </div>
        @else
          <div class="w-full">
            <x-label for="nip">NIP</x-label>
            <x-input id="nip" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700" type="text" value="{{ Auth::user()->nip }}" disabled />
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">NIP tidak dapat diubah</p>
          </div>
        @endif
      </div>

      <div class="mb-4 flex flex-col gap-4 sm:flex-row sm:gap-3">
        <div class="w-full">
          <x-label for="phone">{{ __('Phone') }}</x-label>
          <x-input id="phone" class="mt-1 block w-full" type="text" wire:model="form.phone" />
          @error('form.phone')
            <x-input-error for="form.phone" class="mt-2" message="{{ $message }}" />
          @enderror
        </div>
        <div class="w-full">
          <x-label for="gender">{{ __('Gender') }}</x-label>
          <x-select id="gender" class="mt-1 block w-full" wire:model="form.gender">
            <option value="">{{ __('Select Gender') }}</option>
            <option value="male">{{ __('Male') }}</option>
            <option value="female">{{ __('Female') }}</option>
          </x-select>
          @error('form.gender')
            <x-input-error for="form.gender" class="mt-2" message="{{ $message }}" />
          @enderror
        </div>
      </div>

      <div class="mb-4 flex flex-col gap-4 sm:flex-row sm:gap-3">
        <div class="w-full">
          <x-label for="birth_date">{{ __('Birth Date') }}</x-label>
          <x-input id="birth_date" class="mt-1 block w-full" type="date" wire:model="form.birth_date" />
          @error('form.birth_date')
            <x-input-error for="form.birth_date" class="mt-2" message="{{ $message }}" />
          @enderror
        </div>
        <div class="w-full">
          <x-label for="birth_place">{{ __('Birth Place') }}</x-label>
          <x-input id="birth_place" class="mt-1 block w-full" type="text" wire:model="form.birth_place" />
          @error('form.birth_place')
            <x-input-error for="form.birth_place" class="mt-2" message="{{ $message }}" />
          @enderror
        </div>
      </div>

      <div class="mb-4 flex flex-col gap-4 sm:flex-row sm:gap-3">
        <div class="w-full">
          <x-label for="city">{{ __('City') }}</x-label>
          <x-input id="city" class="mt-1 block w-full" type="text" wire:model="form.city" />
          @error('form.city')
            <x-input-error for="form.city" class="mt-2" message="{{ $message }}" />
          @enderror
        </div>
        <div class="w-full">
          <x-label for="address">{{ __('Address') }}</x-label>
          <x-input id="address" class="mt-1 block w-full" type="text" wire:model="form.address" />
          @error('form.address')
            <x-input-error for="form.address" class="mt-2" message="{{ $message }}" />
          @enderror
        </div>
      </div>

      <!-- Display only (read-only) fields for employees -->
      @if (Auth::user()->group === 'user')
        <div class="mb-4 rounded-lg bg-gray-50 p-4 dark:bg-gray-700">
          <h4 class="mb-3 text-sm font-medium text-gray-700 dark:text-gray-300">Informasi Perusahaan</h4>
          <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
            <div>
              <span class="text-xs text-gray-500 dark:text-gray-400">Divisi</span>
              <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                {{ Auth::user()->division?->name ?? '-' }}
              </p>
            </div>
            <div>
              <span class="text-xs text-gray-500 dark:text-gray-400">Jabatan</span>
              <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                {{ Auth::user()->jobTitle?->name ?? '-' }}
              </p>
            </div>
            <div>
              <span class="text-xs text-gray-500 dark:text-gray-400">Pendidikan Terakhir</span>
              <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                {{ Auth::user()->education?->name ?? '-' }}
              </p>
            </div>
            @if (Auth::user()->employeeDetail)
              <div>
                <span class="text-xs text-gray-500 dark:text-gray-400">Status Karyawan</span>
                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                  @if (Auth::user()->employeeDetail->employment_status === 'probation')
                    Probation
                  @elseif (Auth::user()->employeeDetail->employment_status === 'contract')
                    Kontrak
                  @else
                    Tetap
                  @endif
                </p>
              </div>
              @if (Auth::user()->employeeDetail->join_date)
                <div>
                  <span class="text-xs text-gray-500 dark:text-gray-400">Tanggal Bergabung</span>
                  <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                    {{ \Carbon\Carbon::parse(Auth::user()->employeeDetail->join_date)->format('d/m/Y') }}
                  </p>
                </div>
              @endif
            @endif
          </div>
        </div>
      @endif
    </div>

    <!-- Employee Details -->
    <div class="mb-6 border-t border-gray-200 pt-6 dark:border-gray-700">
      <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-gray-200">Data Karyawan Detail</h3>
      
      <!-- Data Pribadi -->
      <div class="mb-4">
        <h4 class="mb-3 text-sm font-medium text-gray-700 dark:text-gray-300">Data Pribadi</h4>
        <div class="mb-4 flex flex-col gap-4 sm:flex-row sm:gap-3">
          <div class="w-full">
            <x-label for="nik">NIK</x-label>
            <x-input id="nik" class="mt-1 block w-full" type="text" wire:model="form.nik" />
            @error('form.nik')
              <x-input-error for="form.nik" class="mt-2" message="{{ $message }}" />
            @enderror
          </div>
          <div class="w-full">
            <x-label for="npwp">NPWP</x-label>
            <x-input id="npwp" class="mt-1 block w-full" type="text" wire:model="form.npwp" />
            @error('form.npwp')
              <x-input-error for="form.npwp" class="mt-2" message="{{ $message }}" />
            @enderror
          </div>
        </div>
        <div class="mb-4 flex flex-col gap-4 sm:flex-row sm:gap-3">
          <div class="w-full">
            <x-label for="bpjs_tk">BPJS Ketenagakerjaan</x-label>
            <x-input id="bpjs_tk" class="mt-1 block w-full" type="text" wire:model="form.bpjs_tk" />
            @error('form.bpjs_tk')
              <x-input-error for="form.bpjs_tk" class="mt-2" message="{{ $message }}" />
            @enderror
          </div>
          <div class="w-full">
            <x-label for="bpjs_kes">BPJS Kesehatan</x-label>
            <x-input id="bpjs_kes" class="mt-1 block w-full" type="text" wire:model="form.bpjs_kes" />
            @error('form.bpjs_kes')
              <x-input-error for="form.bpjs_kes" class="mt-2" message="{{ $message }}" />
            @enderror
          </div>
        </div>
        <div class="mb-4 flex flex-col gap-4 sm:flex-row sm:gap-3">
          <div class="w-full">
            <x-label for="marital_status">Status Pernikahan</x-label>
            <x-select id="marital_status" class="mt-1 block w-full" wire:model="form.marital_status">
              <option value="">{{ __('Select Status') }}</option>
              <option value="single">Belum Menikah</option>
              <option value="married">Menikah</option>
              <option value="divorced">Cerai</option>
              <option value="widowed">Cerai Mati</option>
            </x-select>
            @error('form.marital_status')
              <x-input-error for="form.marital_status" class="mt-2" message="{{ $message }}" />
            @enderror
          </div>
          <div class="w-full">
            <x-label for="phone_emergency">Kontak Darurat</x-label>
            <x-input id="phone_emergency" class="mt-1 block w-full" type="text" wire:model="form.phone_emergency" />
            @error('form.phone_emergency')
              <x-input-error for="form.phone_emergency" class="mt-2" message="{{ $message }}" />
            @enderror
          </div>
        </div>
      </div>

      <!-- Data Bank -->
      <div class="mb-4">
        <h4 class="mb-3 text-sm font-medium text-gray-700 dark:text-gray-300">Data Bank</h4>
        <div class="mb-4 flex flex-col gap-4 sm:flex-row sm:gap-3">
          <div class="w-full">
            <x-label for="bank_name">Nama Bank</x-label>
            <x-input id="bank_name" class="mt-1 block w-full" type="text" wire:model="form.bank_name" />
            @error('form.bank_name')
              <x-input-error for="form.bank_name" class="mt-2" message="{{ $message }}" />
            @enderror
          </div>
          <div class="w-full">
            <x-label for="bank_account_number">Nomor Rekening</x-label>
            <x-input id="bank_account_number" class="mt-1 block w-full" type="text" wire:model="form.bank_account_number" />
            @error('form.bank_account_number')
              <x-input-error for="form.bank_account_number" class="mt-2" message="{{ $message }}" />
            @enderror
          </div>
        </div>
      </div>
    </div>

    <!-- Submit Button -->
    <div class="flex justify-end">
      <x-button wire:click="update" wire:loading.attr="disabled" wire:target="form.photo">
        {{ __('Update Profile') }}
      </x-button>
    </div>
  </form>
</div>
