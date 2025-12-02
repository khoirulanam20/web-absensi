@if (!Auth::user()->isAdmin)
  <nav class="fixed bottom-0 left-0 right-0 z-50 border-t border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800 sm:hidden">
    <div class="flex h-16 items-center justify-around">
      <a href="{{ route('home') }}"
        class="flex flex-col items-center justify-center px-4 py-2 text-xs {{ request()->routeIs('home') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}">
        <x-heroicon-o-home class="h-6 w-6" />
        <span class="mt-1">Home</span>
      </a>

      <a href="{{ route('attendance-history') }}"
        class="flex flex-col items-center justify-center px-4 py-2 text-xs {{ request()->routeIs('attendance-history') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}">
        <x-heroicon-o-clock class="h-6 w-6" />
        <span class="mt-1">Riwayat</span>
      </a>

      <a href="{{ route('apply-leave') }}"
        class="flex flex-col items-center justify-center px-4 py-2 text-xs {{ request()->routeIs('apply-leave') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}">
        <x-heroicon-o-calendar class="h-6 w-6" />
        <span class="mt-1">Cuti</span>
      </a>

      <a href="{{ route('payroll.index') }}"
        class="flex flex-col items-center justify-center px-4 py-2 text-xs {{ request()->routeIs('payroll.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}">
        <x-heroicon-o-calculator class="h-6 w-6" />
        <span class="mt-1">Gaji</span>
      </a>

      <a href="{{ route('profile.show') }}"
        class="flex flex-col items-center justify-center px-4 py-2 text-xs {{ request()->routeIs('profile.show') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}">
        <x-heroicon-o-user class="h-6 w-6" />
        <span class="mt-1">Profil</span>
      </a>
    </div>
  </nav>
@endif

