<x-app-layout>
  <x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
      {{ __('Payroll Management') }}
    </h2>
  </x-slot>

  <div class="py-4 sm:py-6 md:py-12">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 gap-4 sm:gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <a href="{{ route('admin.payroll.salary-components') }}" class="rounded-lg bg-white p-6 shadow dark:bg-gray-800">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Master Komponen</h3>
          <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Kelola komponen gaji</p>
        </a>
        <a href="{{ route('admin.payroll.employee-salaries') }}" class="rounded-lg bg-white p-6 shadow dark:bg-gray-800">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Setting Gaji</h3>
          <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Atur gaji karyawan</p>
        </a>
        <a href="{{ route('admin.payroll.generate') }}" class="rounded-lg bg-white p-6 shadow dark:bg-gray-800">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Generate Payroll</h3>
          <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Generate gaji bulanan</p>
        </a>
        <a href="{{ route('admin.payroll.index') }}?tab=list" class="rounded-lg bg-white p-6 shadow dark:bg-gray-800">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar Payroll</h3>
          <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Lihat daftar payroll</p>
        </a>
      </div>

      <div class="mt-8">
        @livewire('admin.payroll.payroll-list-component')
      </div>
    </div>
  </div>
</x-app-layout>

