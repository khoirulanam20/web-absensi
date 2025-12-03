@if (Auth::user()->isAdmin)
  <nav class="fixed bottom-0 left-0 right-0 z-50 border-t border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800 sm:hidden">
    <div class="flex h-16 items-center justify-around">
      <a href="{{ route('admin.dashboard') }}"
        class="flex flex-col items-center justify-center px-2 py-2 text-xs {{ request()->routeIs('admin.dashboard') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}">
        <x-heroicon-o-home class="h-5 w-5" />
        <span class="mt-1">Dashboard</span>
      </a>

      <a href="{{ route('admin.attendances') }}"
        class="flex flex-col items-center justify-center px-2 py-2 text-xs {{ request()->routeIs('admin.attendances') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}">
        <x-heroicon-o-clock class="h-5 w-5" />
        <span class="mt-1">Absensi</span>
      </a>

      <a href="{{ route('admin.employees') }}"
        class="flex flex-col items-center justify-center px-2 py-2 text-xs {{ request()->routeIs('admin.employees') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}">
        <x-heroicon-o-users class="h-5 w-5" />
        <span class="mt-1">Karyawan</span>
      </a>

      <a href="{{ route('admin.payroll.index') }}"
        class="flex flex-col items-center justify-center px-2 py-2 text-xs {{ request()->routeIs('admin.payroll.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}">
        <x-heroicon-o-calculator class="h-5 w-5" />
        <span class="mt-1">Payroll</span>
      </a>

      <a href="{{ route('admin.invoice.index') }}"
        class="flex flex-col items-center justify-center px-2 py-2 text-xs {{ request()->routeIs('admin.invoice.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}">
        <x-heroicon-o-document-text class="h-5 w-5" />
        <span class="mt-1">Invoice</span>
      </a>

      <a href="{{ route('admin.performance.index') }}"
        class="flex flex-col items-center justify-center px-2 py-2 text-xs {{ request()->routeIs('admin.performance.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}">
        <x-heroicon-o-chart-bar class="h-5 w-5" />
        <span class="mt-1">KPI</span>
      </a>
    </div>
  </nav>
@endif

