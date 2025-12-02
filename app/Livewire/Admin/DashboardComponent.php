<?php

namespace App\Livewire\Admin;

use App\Livewire\Traits\AttendanceDetailTrait;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class DashboardComponent extends Component
{
    use AttendanceDetailTrait;

    public function render()
    {
        /** @var Collection<Attendance>  */
        $attendances = Attendance::where('date', date('Y-m-d'))->get();

        /** @var Collection<User>  */
        $employees = User::where('group', 'user')
            ->paginate(20)
            ->through(function (User $user) use ($attendances) {
                return $user->setAttribute(
                    'attendance',
                    $attendances
                        ->where(fn (Attendance $attendance) => $attendance->user_id === $user->id)
                        ->first(),
                );
            });

        $employeesCount = User::where('group', 'user')->count();
        
        // Count by new status (hadir, izin, sakit, cuti) and also support old status (present, late, excused, sick) for backward compatibility
        // Late is counted separately but also included in present count (hadir + late = total hadir)
        $presentCount = $attendances->where(fn ($attendance) => in_array($attendance->status, ['hadir', 'present']))->count();
        $lateCount = $attendances->where(fn ($attendance) => $attendance->status === 'late')->count();
        
        // Total hadir = hadir tepat waktu + terlambat
        $totalHadir = $presentCount + $lateCount;
        
        // Izin includes both 'izin' and 'cuti' status
        $izinCount = $attendances->where(fn ($attendance) => in_array($attendance->status, ['izin', 'cuti', 'excused']))->count();
        
        $sickCount = $attendances->where(fn ($attendance) => in_array($attendance->status, ['sakit', 'sick']))->count();
        
        // Count employees who have attendance record today (any status)
        $employeesWithAttendance = $attendances->pluck('user_id')->unique()->count();
        
        // Absent = total employees - employees who have attendance record (regardless of status)
        // This means employees who haven't checked in at all today
        $absentCount = max(0, $employeesCount - $employeesWithAttendance);

        return view('livewire.admin.dashboard', [
            'employees' => $employees,
            'employeesCount' => $employeesCount,
            'presentCount' => $presentCount,
            'lateCount' => $lateCount,
            'totalHadir' => $totalHadir,
            'izinCount' => $izinCount,
            'sickCount' => $sickCount,
            'absentCount' => $absentCount,
        ]);
    }
}
