<div>
  <div class="mb-4 flex items-center justify-between">
    <h3 class="text-lg font-semibold leading-tight text-gray-800 dark:text-gray-200">
      Daftar Payroll
    </h3>
    <div class="flex items-center gap-3">
      <x-input type="month" class="w-48" wire:model.live="period" />
      <x-select class="w-40" wire:model.live="status">
        <option value="">Semua Status</option>
        <option value="draft">Draft</option>
        <option value="published">Published</option>
        <option value="paid">Paid</option>
      </x-select>
    </div>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
      <thead class="bg-gray-50 dark:bg-gray-900">
        <tr>
          <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Periode</th>
          <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Karyawan</th>
          <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Kehadiran</th>
          <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Gaji Pokok</th>
          <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Tunjangan</th>
          <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Potongan</th>
          <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Gaji Bersih</th>
          <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Status</th>
          <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
        @foreach ($payrolls as $payroll)
          <tr>
            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
              {{ \Carbon\Carbon::parse($payroll->period . '-01')->format('M Y') }}
            </td>
            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $payroll->user->name }}</td>
            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $payroll->total_attendance }} hari</td>
            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
              Rp {{ number_format($payroll->basic_salary, 0, ',', '.') }}
            </td>
            <td class="px-4 py-3 text-sm text-green-600 dark:text-green-400">
              Rp {{ number_format($payroll->total_allowance, 0, ',', '.') }}
            </td>
            <td class="px-4 py-3 text-sm text-red-600 dark:text-red-400">
              Rp {{ number_format($payroll->total_deduction, 0, ',', '.') }}
            </td>
            <td class="px-4 py-3 text-sm font-semibold text-gray-900 dark:text-white">
              Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}
            </td>
            <td class="px-4 py-3 text-sm">
              @if ($payroll->status === 'draft')
                <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800 dark:bg-gray-900 dark:text-gray-300">
                  Draft
                </span>
              @elseif ($payroll->status === 'published')
                <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                  Published
                </span>
              @else
                <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-300">
                  Paid
                </span>
              @endif
            </td>
            <td class="px-4 py-3 text-sm">
              <div class="flex gap-2">
                <x-button href="{{ route('admin.payroll.show', $payroll->id) }}" class="text-xs" target="_blank">
                  Detail
                </x-button>
                @if ($payroll->status === 'draft')
                  <x-button wire:click="publish('{{ $payroll->id }}')" class="text-xs" type="button">
                    Publish
                  </x-button>
                @elseif ($payroll->status === 'published')
                  <x-button wire:click="markAsPaid('{{ $payroll->id }}')" class="text-xs" type="button">
                    Mark Paid
                  </x-button>
                @endif
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="mt-4">
    {{ $payrolls->links() }}
  </div>
</div>
