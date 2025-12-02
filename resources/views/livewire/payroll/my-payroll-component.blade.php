<div>
  <div class="mb-4 flex items-center justify-between">
    <h3 class="text-lg font-semibold leading-tight text-gray-800 dark:text-gray-200">
      Riwayat Gaji
    </h3>
    <x-input type="month" class="w-48" wire:model.live="period" />
  </div>

  <div class="grid grid-cols-1 gap-4">
    @foreach ($payrolls as $payroll)
      <div class="rounded-lg bg-white p-6 shadow dark:bg-gray-800">
        <div class="flex items-center justify-between">
          <div>
            <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
              {{ \Carbon\Carbon::parse($payroll->period . '-01')->format('F Y') }}
            </h4>
            <p class="text-sm text-gray-600 dark:text-gray-400">
              Kehadiran: {{ $payroll->total_attendance }} hari
            </p>
          </div>
          <div class="text-right">
            <p class="text-sm text-gray-600 dark:text-gray-400">Gaji Bersih</p>
            <p class="text-2xl font-bold text-green-600 dark:text-green-400">
              Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}
            </p>
            <x-button href="{{ route('payroll.show', $payroll->id) }}" class="mt-2 text-xs" target="_blank">
              Lihat Slip Gaji
            </x-button>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  @if ($payrolls->isEmpty())
    <div class="my-4 text-center text-sm text-gray-600 dark:text-gray-400">
      Belum ada data gaji untuk periode ini.
    </div>
  @endif

  <div class="mt-4">
    {{ $payrolls->links() }}
  </div>
</div>
