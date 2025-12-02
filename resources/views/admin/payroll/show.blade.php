<x-app-layout>
  <x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
      {{ __('Slip Gaji') }} - {{ \Carbon\Carbon::parse($payroll->period . '-01')->format('F Y') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
      <div class="rounded-lg bg-white p-8 shadow dark:bg-gray-800">
        <div class="mb-6 text-center">
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white">SLIP GAJI</h1>
          <p class="mt-2 text-gray-600 dark:text-gray-400">
            Periode: {{ \Carbon\Carbon::parse($payroll->period . '-01')->format('F Y') }}
          </p>
        </div>

        <div class="mb-6">
          <h3 class="mb-2 font-semibold text-gray-900 dark:text-white">Data Karyawan</h3>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400">Nama</p>
              <p class="font-medium text-gray-900 dark:text-white">{{ $payroll->user->name }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400">NIP</p>
              <p class="font-medium text-gray-900 dark:text-white">{{ $payroll->user->nip ?? '-' }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400">Divisi</p>
              <p class="font-medium text-gray-900 dark:text-white">{{ $payroll->user->division?->name ?? '-' }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400">Jabatan</p>
              <p class="font-medium text-gray-900 dark:text-white">{{ $payroll->user->jobTitle?->name ?? '-' }}</p>
            </div>
          </div>
        </div>

        <div class="mb-6 grid grid-cols-2 gap-6">
          <div>
            <h3 class="mb-3 font-semibold text-gray-900 dark:text-white">Penerimaan</h3>
            <div class="space-y-2">
              <div class="flex justify-between">
                <span class="text-gray-600 dark:text-gray-400">Gaji Pokok</span>
                <span class="font-medium text-gray-900 dark:text-white">
                  Rp {{ number_format($payroll->basic_salary, 0, ',', '.') }}
                </span>
              </div>
              @foreach ($payroll->details->where('type', 'earning') as $detail)
                <div class="flex justify-between">
                  <span class="text-gray-600 dark:text-gray-400">{{ $detail->component_name }}</span>
                  <span class="font-medium text-gray-900 dark:text-white">
                    Rp {{ number_format($detail->total, 0, ',', '.') }}
                    @if ($detail->quantity > 1)
                      <span class="text-xs text-gray-500">({{ $detail->quantity }}x)</span>
                    @endif
                  </span>
                </div>
              @endforeach
              <div class="border-t pt-2">
                <div class="flex justify-between font-semibold">
                  <span>Total Penerimaan</span>
                  <span>
                    Rp {{ number_format($payroll->basic_salary + $payroll->total_allowance, 0, ',', '.') }}
                  </span>
                </div>
              </div>
            </div>
          </div>

          <div>
            <h3 class="mb-3 font-semibold text-gray-900 dark:text-white">Potongan</h3>
            <div class="space-y-2">
              @foreach ($payroll->details->where('type', 'deduction') as $detail)
                <div class="flex justify-between">
                  <span class="text-gray-600 dark:text-gray-400">{{ $detail->component_name }}</span>
                  <span class="font-medium text-red-600 dark:text-red-400">
                    Rp {{ number_format($detail->total, 0, ',', '.') }}
                    @if ($detail->quantity > 1)
                      <span class="text-xs text-gray-500">({{ $detail->quantity }}x)</span>
                    @endif
                  </span>
                </div>
              @endforeach
              @if ($payroll->details->where('type', 'deduction')->isEmpty())
                <p class="text-sm text-gray-500">Tidak ada potongan</p>
              @endif
              <div class="border-t pt-2">
                <div class="flex justify-between font-semibold">
                  <span>Total Potongan</span>
                  <span class="text-red-600 dark:text-red-400">
                    Rp {{ number_format($payroll->total_deduction, 0, ',', '.') }}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="border-t-2 border-gray-300 pt-4 dark:border-gray-600">
          <div class="flex justify-between text-xl font-bold">
            <span>Gaji Bersih (Take Home Pay)</span>
            <span class="text-green-600 dark:text-green-400">
              Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}
            </span>
          </div>
        </div>

        <div class="mt-6 flex gap-3">
          <a href="{{ Auth::user()->isAdmin ? route('admin.payroll.pdf', $payroll->id) : route('payroll.pdf', $payroll->id) }}" download>
            <x-button type="button">
              Download PDF
            </x-button>
          </a>
          <x-secondary-button onclick="window.print()">
            Print
          </x-secondary-button>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>

