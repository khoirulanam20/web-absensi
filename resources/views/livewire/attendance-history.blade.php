<div>
  @pushOnce('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
  @endpushOnce
  <h3 class="col-span-2 mb-4 text-base sm:text-lg font-semibold leading-tight text-gray-800 dark:text-gray-200">
    Data Absensi
  </h3>
  <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
    <x-label for="month_filter" value="Bulan" class="text-sm sm:text-base"></x-label>
    <x-input type="month" name="month_filter" id="month_filter" wire:model.live="month" class="w-full sm:w-auto" />
  </div>
  <h5 class="mt-3 text-xs sm:text-sm dark:text-gray-200">Klik pada tanggal untuk melihat detail</h5>
  <div class="mt-4 flex w-full flex-col gap-3 lg:flex-row">
    <div class="grid w-full max-w-full grid-cols-7 overflow-x-auto dark:text-white sm:w-96 lg:w-[36rem]">
      @foreach (['M', 'S', 'S', 'R', 'K', 'J', 'S'] as $day)
        <div
          class="{{ $day === 'M' ? 'text-red-500' : '' }} {{ $day === 'J' ? 'text-green-600 dark:text-green-500' : '' }} flex h-10 items-center justify-center border border-gray-300 text-center dark:border-gray-600">
          {{ $day }}
        </div>
      @endforeach
      @if ($start->dayOfWeek !== 0)
        @foreach (range(1, $start->dayOfWeek) as $i)
          <div class="h-14 border border-gray-300 bg-gray-100 dark:border-gray-600 dark:bg-gray-700">
          </div>
        @endforeach
      @endif
      @php
        $presentCount = 0;
        $lateCount = 0;
        $izinCount = 0;
        $sickCount = 0;
        $absentCount = 0;
      @endphp
      @foreach ($dates as $date)
        @php
          $isWeekend = $date->isWeekend();
          $attendance = $attendances->firstWhere(fn($v, $k) => $v['date'] === $date->format('Y-m-d'));
          $status = $attendance ? $attendance['status'] : ($isWeekend || !$date->isPast() ? '-' : 'absent');

          // Support both new status (hadir, izin, sakit, cuti) and old status (present, late, excused, sick) for backward compatibility
          switch ($status) {
              case 'hadir':
              case 'present':
                  $shortStatus = 'H';
                  $displayStatus = 'Hadir';
                  $bgColor =
                      'bg-green-200 dark:bg-green-800 hover:bg-green-300 dark:hover:bg-green-700 border border-green-600';
                  $presentCount++;
                  break;
              case 'late':
                  $shortStatus = 'T';
                  $displayStatus = 'Terlambat';
                  $bgColor =
                      'bg-amber-200 dark:bg-amber-800 hover:bg-amber-300 dark:hover:bg-amber-700 border border-amber-600';
                  $lateCount++;
                  break;
              case 'izin':
              case 'cuti':
              case 'excused':
                  $shortStatus = $status === 'cuti' ? 'C' : 'I';
                  $displayStatus = ucfirst($status);
                  $bgColor =
                      'bg-blue-200 dark:bg-blue-800 hover:bg-blue-300 dark:hover:bg-blue-700 border border-blue-600';
                  $izinCount++;
                  break;
              case 'sakit':
              case 'sick':
                  $shortStatus = 'S';
                  $displayStatus = 'Sakit';
                  $bgColor =
                      'bg-purple-200 dark:bg-purple-950 hover:bg-purple-100 dark:hover:bg-purple-700 border border-purple-600';
                  $sickCount++;
                  break;
              case 'absent':
                  $shortStatus = 'A';
                  $displayStatus = 'Tidak Hadir';
                  $bgColor =
                      'bg-red-200 dark:bg-red-950 text-red-500 dark:text-red-200 border border-red-300 dark:border-red-700';
                  $absentCount++;
                  break;
              default:
                  $shortStatus = '-';
                  $displayStatus = '-';
                  $bgColor =
                      'bg-slate-200 text-slate-600 dark:text-slate-200 dark:bg-slate-800 border border-gray-400 dark:border-gray-700';
                  break;
          }
        @endphp
        @if ($attendance && ($attendance['attachment'] || $attendance['note'] || $attendance['coordinates']))
          <button class="{{ $bgColor }} h-14 w-full py-1 text-center" wire:click="show({{ $attendance['id'] }})"
            onclick="setLocation({{ $attendance['lat'] ?? 0 }}, {{ $attendance['lng'] ?? 0 }})">
            <span
              class="{{ $date->isSunday() ? 'text-red-500' : '' }} {{ $date->isFriday() ? 'text-green-600 dark:text-green-500' : '' }}">
              {{ $date->format('d') }}
            </span>
            <br>
            {{ $shortStatus }}
          </button>
        @else
          <div class="{{ $bgColor }} h-14 py-1 text-center">
            <span
              class="{{ $date->isSunday() ? 'text-red-500' : '' }} {{ $date->isFriday() ? 'text-green-600 dark:text-green-500' : '' }}">
              {{ $date->format('d') }}
            </span>
            <br>
            {{ $shortStatus }}
          </div>
        @endif
      @endforeach
      @if ($end->dayOfWeek !== 6)
        @foreach (range(5, $end->dayOfWeek) as $i)
          <div class="h-14 border border-gray-300 bg-gray-100 dark:border-gray-600 dark:bg-gray-700"></div>
        @endforeach
      @endif
    </div>
    <div class="grid h-fit w-full grid-cols-2 gap-2 sm:gap-3 md:grid-cols-4">
      <div
        class="flex flex-col items-start justify-center rounded-md bg-green-200 px-3 py-2 text-gray-800 dark:bg-green-900 dark:text-white dark:shadow-gray-700 sm:px-4">
        <div>
          <h4 class="text-sm font-semibold sm:text-base md:text-lg lg:text-xl">Hadir: {{ $presentCount + $lateCount }}</h4>
          <p class="text-xs sm:text-sm">Terlambat: {{ $lateCount }}</p>
        </div>
      </div>
      <div
        class="flex flex-col items-start justify-center rounded-md bg-blue-200 px-3 py-2 text-gray-800 dark:bg-blue-900 dark:text-white dark:shadow-gray-700 sm:px-4">
        <div>
          <h4 class="text-sm font-semibold sm:text-base md:text-lg lg:text-xl">Izin: {{ $izinCount }}</h4>
        </div>
      </div>
      <div
        class="flex flex-col items-start justify-center rounded-md bg-purple-200 px-3 py-2 text-gray-800 dark:bg-purple-900 dark:text-white dark:shadow-gray-700 sm:px-4">
        <div>
          <h4 class="text-sm font-semibold sm:text-base md:text-lg lg:text-xl">Sakit: {{ $sickCount }}</h4>
        </div>
      </div>
      <div
        class="flex flex-col items-start justify-center rounded-md bg-red-200 px-3 py-2 text-gray-800 dark:bg-red-900 dark:text-white dark:shadow-gray-700 sm:px-4">
        <div>
          <h4 class="text-sm font-semibold sm:text-base md:text-lg lg:text-xl">Absen: {{ $absentCount }}</h4>
        </div>
      </div>
    </div>
  </div>

  <x-attendance-detail-modal :current-attendance="$currentAttendance" />
  @stack('attendance-detail-scripts')
</div>
