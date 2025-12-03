<nav x-data="{ open: false }" class="sticky top-0 z-40 border-b border-gray-100 bg-white dark:border-gray-700 dark:bg-gray-800">
  <!-- Primary Navigation Menu -->
  <div class="mx-auto max-w-7xl px-2 sm:px-4 lg:px-8">
    <div class="flex h-14 sm:h-16 justify-between">
      <div class="flex">
        <!-- Logo -->
        <div class="flex shrink-0 items-center">
          <a href="{{ Auth::user()->isAdmin ? route('admin.dashboard') : route('home') }}" class="flex items-center">
            <x-application-mark class="block h-7 w-auto sm:h-9 sm:w-auto" />
          </a>
        </div>

        <!-- Navigation Links -->
        <div class="hidden space-x-2 sm:-my-px sm:ms-6 sm:flex md:ms-10 md:space-x-5 lg:space-x-8">
          @if (Auth::user()->isAdmin)
            {{-- Menu utama yang sering dipakai --}}
            <x-nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">
              {{ __('Dashboard') }}
            </x-nav-link>

            {{-- Menu Karyawan --}}
            <x-nav-dropdown :active="request()->routeIs('admin.employees') || request()->routeIs('admin.masters.division') || request()->routeIs('admin.masters.job-title') || request()->routeIs('admin.masters.education') || request()->routeIs('admin.import-export.users')" triggerClasses="text-nowrap">
              <x-slot name="trigger">
                {{ __('Karyawan') }}
                <x-heroicon-o-chevron-down class="ms-2 h-5 w-5 text-gray-400" />
              </x-slot>
              <x-slot name="content">
                <x-dropdown-link href="{{ route('admin.employees') }}" :active="request()->routeIs('admin.employees')">
                  {{ __('Karyawan') }}
                </x-dropdown-link>
                <x-dropdown-link href="{{ route('admin.masters.division') }}" :active="request()->routeIs('admin.masters.division')">
                  {{ __('Divisi') }}
                </x-dropdown-link>
                <x-dropdown-link href="{{ route('admin.masters.job-title') }}" :active="request()->routeIs('admin.masters.job-title')">
                  {{ __('Jabatan') }}
                </x-dropdown-link>
                <x-dropdown-link href="{{ route('admin.masters.education') }}" :active="request()->routeIs('admin.masters.education')">
                  {{ __('Pendidikan') }}
                </x-dropdown-link>
                <x-dropdown-link href="{{ route('admin.import-export.users') }}" :active="request()->routeIs('admin.import-export.users')">
                  {{ __('Import & Export Karyawan/Admin') }}
                </x-dropdown-link>
              </x-slot>
            </x-nav-dropdown>

            {{-- Menu Absensi --}}
            <x-nav-dropdown :active="request()->routeIs('admin.attendances') || request()->routeIs('admin.masters.shift') || request()->routeIs('admin.import-export.attendances')" triggerClasses="text-nowrap">
              <x-slot name="trigger">
                {{ __('Absensi') }}
                <x-heroicon-o-chevron-down class="ms-2 h-5 w-5 text-gray-400" />
              </x-slot>
              <x-slot name="content">
                <x-dropdown-link href="{{ route('admin.attendances') }}" :active="request()->routeIs('admin.attendances')">
                  {{ __('Absensi') }}
                </x-dropdown-link>
                <x-dropdown-link href="{{ route('admin.masters.shift') }}" :active="request()->routeIs('admin.masters.shift')">
                  {{ __('Shift') }}
                </x-dropdown-link>
                <x-dropdown-link href="{{ route('admin.import-export.attendances') }}" :active="request()->routeIs('admin.import-export.attendances')">
                  {{ __('Import & Export Absensi') }}
                </x-dropdown-link>
              </x-slot>
            </x-nav-dropdown>

            {{-- Menu Payroll --}}
            <x-nav-dropdown :active="request()->routeIs('admin.payroll.*')" triggerClasses="text-nowrap">
              <x-slot name="trigger">
                {{ __('Payroll') }}
                <x-heroicon-o-chevron-down class="ms-2 h-5 w-5 text-gray-400" />
              </x-slot>
              <x-slot name="content">
                <x-dropdown-link href="{{ route('admin.payroll.index') }}" :active="request()->routeIs('admin.payroll.index')">
                  {{ __('Dashboard Payroll') }}
                </x-dropdown-link>
                <x-dropdown-link href="{{ route('admin.payroll.salary-components') }}" :active="request()->routeIs('admin.payroll.salary-components')">
                  {{ __('Master Komponen') }}
                </x-dropdown-link>
                <x-dropdown-link href="{{ route('admin.payroll.employee-salaries') }}" :active="request()->routeIs('admin.payroll.employee-salaries')">
                  {{ __('Setting Gaji') }}
                </x-dropdown-link>
                <x-dropdown-link href="{{ route('admin.payroll.generate') }}" :active="request()->routeIs('admin.payroll.generate')">
                  {{ __('Generate Gaji') }}
                </x-dropdown-link>
              </x-slot>
            </x-nav-dropdown>

            {{-- Menu Invoice --}}
            <x-nav-link href="{{ route('admin.invoice.index') }}" :active="request()->routeIs('admin.invoice.*')">
              {{ __('Invoice') }}
            </x-nav-link>

            {{-- Performance / KPI --}}
            <x-nav-dropdown :active="request()->routeIs('admin.performance.*')" triggerClasses="text-nowrap">
              <x-slot name="trigger">
                {{ __('Performance') }}
                <x-heroicon-o-chevron-down class="ms-2 h-5 w-5 text-gray-400" />
              </x-slot>
              <x-slot name="content">
                <x-dropdown-link href="{{ route('admin.performance.index') }}" :active="request()->routeIs('admin.performance.index')">
                  {{ __('Dashboard') }}
                </x-dropdown-link>
                <x-dropdown-link href="{{ route('admin.performance.kpi') }}" :active="request()->routeIs('admin.performance.kpi')">
                  {{ __('KPI Management') }}
                </x-dropdown-link>
                <x-dropdown-link href="{{ route('admin.performance.cycles') }}" :active="request()->routeIs('admin.performance.cycles')">
                  {{ __('Review Cycles') }}
                </x-dropdown-link>
                <x-dropdown-link href="{{ route('admin.performance.review') }}" :active="request()->routeIs('admin.performance.review')">
                  {{ __('Review Management') }}
                </x-dropdown-link>
              </x-slot>
            </x-nav-dropdown>
            
            {{-- Lainnya --}}
            <x-nav-dropdown :active="request()->routeIs('admin.masters.*') || request()->routeIs('admin.location-settings') || request()->routeIs('admin.assets.*') || request()->routeIs('admin.tools.*') || request()->routeIs('tools.prd-generator')" triggerClasses="text-nowrap">
              <x-slot name="trigger">
                {{ __('Lainnya') }}
                <x-heroicon-o-chevron-down class="ms-2 h-5 w-5 text-gray-400" />
              </x-slot>
              <x-slot name="content">
                {{-- Pengaturan Lokasi --}}
                <x-dropdown-link href="{{ route('admin.location-settings') }}" :active="request()->routeIs('admin.location-settings')">
                  {{ __('Pengaturan Lokasi') }}
                </x-dropdown-link>
                
                {{-- Master Data --}}
                <x-dropdown-link href="{{ route('admin.masters.admin') }}" :active="request()->routeIs('admin.masters.admin')">
                  {{ __('Admin') }}
                </x-dropdown-link>
                
                {{-- Aset --}}
                <x-dropdown-link href="{{ route('admin.assets.index') }}" :active="request()->routeIs('admin.assets.*')">
                  {{ __('Aset') }}
                </x-dropdown-link>

                {{-- Budget Calculator --}}
                <x-dropdown-link href="{{ route('admin.tools.budget-calculator') }}" :active="request()->routeIs('admin.tools.budget-calculator')">
                  {{ __('Budget Calculator') }}
                </x-dropdown-link>

                {{-- PRD Generator --}}
                <x-dropdown-link href="{{ route('tools.prd-generator') }}" :active="request()->routeIs('tools.prd-generator')">
                  {{ __('PRD Generator') }}
                </x-dropdown-link>
              </x-slot>
            </x-nav-dropdown>
            
        @else
          <x-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')">
            {{ __('Home') }}
          </x-nav-link>
          <x-nav-link href="{{ route('payroll.index') }}" :active="request()->routeIs('payroll.*')">
            {{ __('Riwayat Gaji') }}
          </x-nav-link>
          <x-nav-dropdown :active="request()->routeIs('performance.*')" triggerClasses="text-nowrap">
            <x-slot name="trigger">
              {{ __('Performance') }}
              <x-heroicon-o-chevron-down class="ms-2 h-5 w-5 text-gray-400" />
            </x-slot>
            <x-slot name="content">
              <x-dropdown-link href="{{ route('performance.index') }}" :active="request()->routeIs('performance.index')">
                {{ __('My Performance') }}
              </x-dropdown-link>
              <x-dropdown-link href="{{ route('performance.tutorial') }}" :active="request()->routeIs('performance.tutorial')">
                {{ __('Tutorial KPI') }}
              </x-dropdown-link>
              @php
                // Check if user has reviewed any assessments (simple check for manager)
                $hasReviewed = \App\Models\AssessmentItem::whereHas('assessment', function ($q) {
                  $q->where('user_id', '!=', auth()->id());
                })->whereNotNull('manager_score')->exists();
                $isManager = auth()->user()->isAdmin || $hasReviewed;
              @endphp
              @if ($isManager)
                <x-dropdown-link href="{{ route('performance.manager-history') }}" :active="request()->routeIs('performance.manager-history')">
                  {{ __('History Review') }}
                </x-dropdown-link>
              @endif
            </x-slot>
          </x-nav-dropdown>

          {{-- Lainnya (User) --}}
          <x-nav-dropdown :active="request()->routeIs('tools.*')" triggerClasses="text-nowrap">
            <x-slot name="trigger">
              {{ __('Lainnya') }}
              <x-heroicon-o-chevron-down class="ms-2 h-5 w-5 text-gray-400" />
            </x-slot>
            <x-slot name="content">
              <x-dropdown-link href="{{ route('tools.prd-generator') }}" :active="request()->routeIs('tools.prd-generator')">
                {{ __('PRD Generator') }}
              </x-dropdown-link>
            </x-slot>
          </x-nav-dropdown>
        @endif
        </div>
      </div>

      <div class="flex gap-2">
        <div class="hidden sm:ms-6 sm:flex sm:items-center">
          <x-theme-toggle />

          <!-- Settings Dropdown -->
          <div class="relative ms-3">
            <x-dropdown align="right" width="48">
              <x-slot name="trigger">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                  <button
                    class="flex rounded-full border-2 border-transparent text-sm transition focus:border-gray-300 focus:outline-none">
                    <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}"
                      alt="{{ Auth::user()->name }}" />
                  </button>
                @else
                  <span class="inline-flex rounded-md">
                    <button type="button"
                      class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:bg-gray-50 focus:outline-none active:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:hover:text-gray-300 dark:focus:bg-gray-700 dark:active:bg-gray-700">
                      {{ Auth::user()->name }}

                      <svg class="-me-0.5 ms-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                      </svg>
                    </button>
                  </span>
                @endif
              </x-slot>

              <x-slot name="content">
                <!-- Account Management -->
                <div class="block px-4 py-2 text-xs text-gray-400">
                  {{ __('Manage Account') }}
                </div>

                <x-dropdown-link href="{{ route('profile.show') }}">
                  {{ __('Profile') }}
                </x-dropdown-link>

                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                  <x-dropdown-link href="{{ route('api-tokens.index') }}">
                    {{ __('API Tokens') }}
                  </x-dropdown-link>
                @endif

                <div class="border-t border-gray-200 dark:border-gray-600"></div>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}" x-data>
                  @csrf

                  <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                    {{ __('Log Out') }}
                  </x-dropdown-link>
                </form>
              </x-slot>
            </x-dropdown>
          </div>
        </div>

        <x-theme-toggle class="sm:hidden" />

        <!-- Hamburger -->
        <div class="-me-2 flex items-center sm:hidden">
          <button @click="open = ! open"
            class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none dark:text-gray-500 dark:hover:bg-gray-900 dark:hover:text-gray-400 dark:focus:bg-gray-900 dark:focus:text-gray-400">
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
              <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round"
                stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Responsive Navigation Menu -->
  <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
    <div class="space-y-1 pb-3 pt-2">
      @if (Auth::user()->isAdmin)
        {{-- Dashboard --}}
        <x-responsive-nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">
          {{ __('Dashboard') }}
        </x-responsive-nav-link>

        {{-- Menu Karyawan --}}
        <div class="mt-3 border-t border-gray-200 pt-2 text-xs font-semibold uppercase tracking-wide text-gray-400 dark:border-gray-600">
          {{ __('Menu Karyawan') }}
        </div>
        <x-responsive-nav-link href="{{ route('admin.employees') }}" :active="request()->routeIs('admin.employees')">
          {{ __('Karyawan') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link href="{{ route('admin.masters.division') }}" :active="request()->routeIs('admin.masters.division')">
          {{ __('Divisi') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link href="{{ route('admin.masters.job-title') }}" :active="request()->routeIs('admin.masters.job-title')">
          {{ __('Jabatan') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link href="{{ route('admin.masters.education') }}" :active="request()->routeIs('admin.masters.education')">
          {{ __('Pendidikan') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link href="{{ route('admin.import-export.users') }}" :active="request()->routeIs('admin.import-export.users')">
          {{ __('Import & Export Karyawan/Admin') }}
        </x-responsive-nav-link>

        {{-- Menu Absensi --}}
        <div class="mt-3 border-t border-gray-200 pt-2 text-xs font-semibold uppercase tracking-wide text-gray-400 dark:border-gray-600">
          {{ __('Menu Absensi') }}
        </div>
        <x-responsive-nav-link href="{{ route('admin.attendances') }}" :active="request()->routeIs('admin.attendances')">
          {{ __('Absensi') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link href="{{ route('admin.masters.shift') }}" :active="request()->routeIs('admin.masters.shift')">
          {{ __('Shift') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link href="{{ route('admin.import-export.attendances') }}" :active="request()->routeIs('admin.import-export.attendances')">
          {{ __('Import & Export Absensi') }}
        </x-responsive-nav-link>

        {{-- Menu Payroll --}}
        <div class="mt-3 border-t border-gray-200 pt-2 text-xs font-semibold uppercase tracking-wide text-gray-400 dark:border-gray-600">
          {{ __('Menu Payroll') }}
        </div>
        <x-responsive-nav-link href="{{ route('admin.payroll.index') }}" :active="request()->routeIs('admin.payroll.index')">
          {{ __('Dashboard Payroll') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link href="{{ route('admin.payroll.salary-components') }}" :active="request()->routeIs('admin.payroll.salary-components')">
          {{ __('Master Komponen') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link href="{{ route('admin.payroll.employee-salaries') }}" :active="request()->routeIs('admin.payroll.employee-salaries')">
          {{ __('Setting Gaji') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link href="{{ route('admin.payroll.generate') }}" :active="request()->routeIs('admin.payroll.generate')">
          {{ __('Generate Gaji') }}
        </x-responsive-nav-link>

        {{-- Menu Invoice --}}
        <div class="mt-3 border-t border-gray-200 pt-2 text-xs font-semibold uppercase tracking-wide text-gray-400 dark:border-gray-600">
          {{ __('Menu Invoice') }}
        </div>
        <x-responsive-nav-link href="{{ route('admin.invoice.index') }}" :active="request()->routeIs('admin.invoice.*')">
          {{ __('Invoice') }}
        </x-responsive-nav-link>

        {{-- Performance --}}
        <div class="mt-3 border-t border-gray-200 pt-2 text-xs font-semibold uppercase tracking-wide text-gray-400 dark:border-gray-600">
          {{ __('Performance') }}
        </div>
        <x-responsive-nav-link href="{{ route('admin.performance.index') }}" :active="request()->routeIs('admin.performance.index')">
          {{ __('Performance Dashboard') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link href="{{ route('admin.performance.review') }}" :active="request()->routeIs('admin.performance.review')">
          {{ __('Review Management') }}
        </x-responsive-nav-link>

        {{-- Lainnya --}}
        <div class="mt-3 border-t border-gray-200 pt-2 text-xs font-semibold uppercase tracking-wide text-gray-400 dark:border-gray-600">
          {{ __('Lainnya') }}
        </div>
        <x-responsive-nav-link href="{{ route('admin.location-settings') }}" :active="request()->routeIs('admin.location-settings')">
          {{ __('Pengaturan Lokasi') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link href="{{ route('admin.assets.index') }}" :active="request()->routeIs('admin.assets.*')">
          {{ __('Aset') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link href="{{ route('admin.tools.budget-calculator') }}" :active="request()->routeIs('admin.tools.budget-calculator')">
          {{ __('Budget Calculator') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link href="{{ route('tools.prd-generator') }}" :active="request()->routeIs('tools.prd-generator')">
          {{ __('PRD Generator') }}
        </x-responsive-nav-link>
      @else
        <x-responsive-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')">
          {{ __('Home') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link href="{{ route('payroll.index') }}" :active="request()->routeIs('payroll.*')">
          {{ __('Riwayat Gaji') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link href="{{ route('performance.index') }}" :active="request()->routeIs('performance.index')">
          {{ __('My Performance') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link href="{{ route('performance.tutorial') }}" :active="request()->routeIs('performance.tutorial')">
          {{ __('Tutorial KPI') }}
        </x-responsive-nav-link>
        @php
          // Check if user has reviewed any assessments (simple check for manager)
          $hasReviewed = \App\Models\AssessmentItem::whereHas('assessment', function ($q) {
            $q->where('user_id', '!=', auth()->id());
          })->whereNotNull('manager_score')->exists();
          $isManager = auth()->user()->isAdmin || $hasReviewed;
        @endphp
        @if ($isManager)
          <x-responsive-nav-link href="{{ route('performance.manager-history') }}" :active="request()->routeIs('performance.manager-history')">
            {{ __('History Review') }}
          </x-responsive-nav-link>
        @endif

        {{-- Lainnya (User) --}}
        <div class="mt-3 border-t border-gray-200 pt-2 text-xs font-semibold uppercase tracking-wide text-gray-400 dark:border-gray-600">
          {{ __('Lainnya') }}
        </div>
        <x-responsive-nav-link href="{{ route('tools.prd-generator') }}" :active="request()->routeIs('tools.prd-generator')">
          {{ __('PRD Generator') }}
        </x-responsive-nav-link>
      @endif
    </div>

    <!-- Responsive Settings Options -->
    <div class="border-t border-gray-200 pb-1 pt-4 dark:border-gray-600">
      <div class="flex items-center px-4">
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
          <div class="me-3 shrink-0">
            <img class="h-10 w-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}"
              alt="{{ Auth::user()->name }}" />
          </div>
        @endif

        <div>
          <div class="text-base font-medium text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
          <div class="text-sm font-medium text-gray-500">{{ Auth::user()->email }}</div>
        </div>
      </div>

      <div class="mt-3 space-y-1">
        <!-- Account Management -->
        <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
          {{ __('Profile') }}
        </x-responsive-nav-link>

        @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
          <x-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')">
            {{ __('API Tokens') }}
          </x-responsive-nav-link>
        @endif

        <!-- Authentication -->
        <form method="POST" action="{{ route('logout') }}" x-data>
          @csrf

          <x-responsive-nav-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
            {{ __('Log Out') }}
          </x-responsive-nav-link>
        </form>
      </div>
    </div>
  </div>
</nav>
