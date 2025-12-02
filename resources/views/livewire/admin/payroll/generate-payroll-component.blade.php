<div>
  <div class="mb-4">
    <h3 class="text-lg font-semibold leading-tight text-gray-800 dark:text-gray-200">
      Generate Payroll
    </h3>
    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
      Generate payroll untuk semua karyawan berdasarkan data absensi dan setting gaji.
    </p>
  </div>

  <div class="rounded-lg bg-white p-6 shadow dark:bg-gray-800">
    <form wire:submit="generate">
      <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <div>
          <x-label for="period">Periode (Bulan & Tahun)</x-label>
          <x-input id="period" class="mt-1 block w-full" type="month" wire:model="period" required />
          @error('period')
            <x-input-error for="period" class="mt-2" message="{{ $message }}" />
          @enderror
        </div>
        <div>
          <x-label for="paymentDate">Tanggal Pembayaran</x-label>
          <x-input id="paymentDate" class="mt-1 block w-full" type="date" wire:model="paymentDate" required />
          @error('paymentDate')
            <x-input-error for="paymentDate" class="mt-2" message="{{ $message }}" />
          @enderror
        </div>
      </div>

      <div class="mt-6">
        <x-button wire:click="generate" wire:loading.attr="disabled" wire:target="generate" :disabled="$isGenerating">
          <x-heroicon-o-calculator class="mr-2 h-5 w-5" />
          <span wire:loading.remove wire:target="generate">Generate Payroll</span>
          <span wire:loading wire:target="generate">Generating...</span>
        </x-button>
      </div>

      @if ($generatedCount > 0)
        <div class="mt-4 p-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 rounded-lg">
          <p class="text-green-800 dark:text-green-200">
            Payroll berhasil di-generate untuk <strong>{{ $generatedCount }}</strong> karyawan.
          </p>
        </div>
      @endif
    </form>
  </div>
</div>
