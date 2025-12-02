<?php

namespace App\Livewire;

use App\ExtendedCarbon;
use App\Models\Attendance;
use App\Models\LocationSetting;
use App\Models\Shift;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Carbon;
use Ballen\Distical\Calculator as DistanceCalculator;
use Ballen\Distical\Entities\LatLong;

class AttendanceForm extends Component
{
    public ?Attendance $attendance = null;
    public $shift_id = null;
    public $shifts = null;
    public ?array $currentLiveCoords = null;
    public string $successMsg = '';
    
    // Form properties
    public $status = 'hadir';
    public $is_wfh = false;
    public $notes = '';
    public $latitude = null;
    public $longitude = null;
    public $showModal = false;
    public $showCheckOutModal = false;
    public $checkOutNotes = '';

    public function mount()
    {
        $this->shifts = Shift::all();

        /** @var Attendance */
        $attendance = Attendance::where('user_id', Auth::user()->id)
            ->where('date', date('Y-m-d'))->first();
            
        if ($attendance) {
            $this->attendance = $attendance;
            $this->shift_id = $attendance->shift_id;
        } else {
            // get closest shift from current time
            $closest = ExtendedCarbon::now()
                ->closestFromDateArray($this->shifts->pluck('start_time')->toArray());

            $this->shift_id = $this->shifts
                ->where(fn (Shift $shift) => $shift->start_time == $closest->format('H:i:s'))
                ->first()->id ?? null;
        }
    }

    public function openCheckInModal()
    {
        $this->showModal = true;
        $this->reset(['status', 'is_wfh', 'notes']);
        $this->status = 'hadir';
        $this->is_wfh = false;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['status', 'is_wfh', 'notes']);
    }

    public function checkIn()
    {
        // Validasi berbeda untuk status izin dan cuti
        if ($this->status === 'izin' || $this->status === 'cuti') {
            $statusLabel = $this->status === 'izin' ? 'Izin' : 'Cuti';
            $this->validate([
                'status' => 'required|in:hadir,izin,sakit,cuti',
                'notes' => 'required|string|max:500',
                'shift_id' => 'required|exists:shifts,id',
            ], [
                'status.required' => 'Status kehadiran wajib diisi.',
                'status.in' => 'Status kehadiran tidak valid.',
                'notes.required' => "Catatan wajib diisi untuk status {$statusLabel}.",
                'shift_id.required' => 'Shift wajib dipilih.',
                'shift_id.exists' => 'Shift yang dipilih tidak valid.',
            ]);
        } else {
            $this->validate([
                'status' => 'required|in:hadir,izin,sakit,cuti',
                'is_wfh' => 'required_if:status,hadir|boolean',
                'notes' => 'required_if:status,sakit|nullable|string|max:500',
                'shift_id' => 'required|exists:shifts,id',
            ], [
                'status.required' => 'Status kehadiran wajib diisi.',
                'status.in' => 'Status kehadiran tidak valid.',
                'is_wfh.required_if' => 'Lokasi kerja wajib diisi jika status Hadir.',
                'notes.required_if' => 'Catatan wajib diisi jika status Sakit.',
                'shift_id.required' => 'Shift wajib dipilih.',
                'shift_id.exists' => 'Shift yang dipilih tidak valid.',
            ]);
        }

        // Get current location
        if (is_null($this->currentLiveCoords)) {
            $this->addError('location', 'Lokasi tidak dapat dideteksi. Pastikan browser mengizinkan akses lokasi.');
            return;
        }

        // Validasi radius geolokasi hanya jika status = hadir DAN WFO (is_wfh = false atau "0")
        // Jika WFH (is_wfh = true atau "1"), radius tidak berpengaruh
        $isWfh = filter_var($this->is_wfh, FILTER_VALIDATE_BOOLEAN);
        if ($this->status === 'hadir' && !$isWfh) {
            $locationSettings = LocationSetting::where('is_active', true)->get();
            
            if ($locationSettings->isEmpty()) {
                $errorMessage = 'Tidak ada pengaturan lokasi aktif. Silakan hubungi admin.';
                $this->addError('location', $errorMessage);
                $this->dispatch('show-location-error', $errorMessage);
                return;
            }

            $userLocation = new LatLong($this->currentLiveCoords[0], $this->currentLiveCoords[1]);
            $isWithinRadius = false;
            $nearestLocation = null;
            $minDistance = PHP_INT_MAX;

            foreach ($locationSettings as $locationSetting) {
                $locationPoint = new LatLong($locationSetting->latitude, $locationSetting->longitude);
                $distanceCalculator = new DistanceCalculator($userLocation, $locationPoint);
                $distanceInMeter = floor($distanceCalculator->get()->asKilometres() * 1000);

                if ($distanceInMeter <= $locationSetting->radius) {
                    $isWithinRadius = true;
                    break;
                }

                if ($distanceInMeter < $minDistance) {
                    $minDistance = $distanceInMeter;
                    $nearestLocation = $locationSetting;
                }
            }

            if (!$isWithinRadius) {
                $nearestName = $nearestLocation ? $nearestLocation->name : 'lokasi terdekat';
                $radiusMax = $nearestLocation ? $nearestLocation->radius : 0;
                $errorMessage = "⚠️ Lokasi Anda berada di luar radius yang diizinkan untuk WFO.\n\nJarak terdekat: {$minDistance}m dari {$nearestName}\nRadius maksimal: {$radiusMax}m\n\nSilakan pilih WFH jika Anda bekerja dari rumah, atau pastikan Anda berada dalam radius yang ditentukan.";
                
                $this->addError('location', $errorMessage);
                $this->dispatch('show-location-error', $errorMessage);
                return;
            }
        }

        $now = Carbon::now();
        $date = $now->format('Y-m-d');
        $timeIn = $now->format('H:i:s');
        
        /** @var Shift */
        $shift = Shift::find($this->shift_id);
        
        // Determine final status: if status is 'hadir' and check-in time is after shift start time, mark as 'late'
        $finalStatus = $this->status;
        if ($this->status === 'hadir' && $shift && $shift->start_time) {
            $shiftStartTime = Carbon::parse($shift->start_time);
            $checkInTime = Carbon::parse($timeIn);
            
            // Compare only time (ignore date)
            $shiftStartToday = Carbon::today()->setTimeFromTimeString($shift->start_time);
            $checkInToday = Carbon::today()->setTimeFromTimeString($timeIn);
            
            // If check-in time is after shift start time, mark as late
            if ($checkInToday->gt($shiftStartToday)) {
                $finalStatus = 'late';
            }
        }
        
        // Create attendance record
        $isWfh = filter_var($this->is_wfh, FILTER_VALIDATE_BOOLEAN);
        $attendance = Attendance::create([
            'user_id' => Auth::user()->id,
            'date' => $date,
            'time_in' => $timeIn,
            'time_out' => null,
            'shift_id' => $shift->id,
            'latitude' => doubleval($this->currentLiveCoords[0]),
            'longitude' => doubleval($this->currentLiveCoords[1]),
            'status' => $finalStatus,
            'is_wfh' => ($finalStatus === 'hadir' || $finalStatus === 'late') ? $isWfh : false,
            'notes' => $this->notes ?: null,
            'note' => $this->notes ?: null, // Keep for backward compatibility
        ]);

        $this->attendance = $attendance->fresh();
        $this->showModal = false;
        $this->successMsg = __('Attendance In Successful');
        $this->reset(['status', 'is_wfh', 'notes']);
        
        Attendance::clearUserAttendanceCache(Auth::user(), Carbon::parse($attendance->date));
        
        $this->dispatch('attendance-updated');
    }

    public function openCheckOutModal()
    {
        if (!$this->attendance) {
            $this->addError('attendance', 'Tidak ada data absensi masuk.');
            return;
        }

        // Reset check out notes (jangan load notes yang sudah ada, biarkan user input baru)
        $this->checkOutNotes = '';
        $this->showCheckOutModal = true;
    }

    public function closeCheckOutModal()
    {
        $this->showCheckOutModal = false;
        $this->checkOutNotes = '';
    }

    public function checkOut()
    {
        // Ensure checkOutNotes is initialized
        if (!isset($this->checkOutNotes)) {
            $this->checkOutNotes = '';
        }

        // Reload attendance to ensure it's fresh
        $attendance = Attendance::where('user_id', Auth::user()->id)
            ->where('date', date('Y-m-d'))
            ->first();

        if (!$attendance) {
            $this->addError('attendance', 'Tidak ada data absensi masuk.');
            $this->showCheckOutModal = false;
            return;
        }

        // Manual validation instead of using $this->validate()
        $checkOutNotes = $this->checkOutNotes ?? '';
        if (strlen($checkOutNotes) > 500) {
            $this->addError('checkOutNotes', 'Catatan maksimal 500 karakter.');
            return;
        }

        $now = Carbon::now();
        $timeOut = $now->format('H:i:s');

        // Update notes: jika sudah ada notes dari check-in, gabungkan dengan notes checkout
        $existingNotes = $attendance->notes ?? '';
        $updatedNotes = '';
        
        if ($existingNotes && $checkOutNotes) {
            // Jika ada notes check-in dan check-out, gabungkan dengan separator
            $updatedNotes = $existingNotes . "\n\n--- Check Out ---\n" . trim($checkOutNotes);
        } elseif ($checkOutNotes) {
            // Jika hanya ada notes check-out
            $updatedNotes = '[Check Out] ' . trim($checkOutNotes);
        } else {
            // Jika tidak ada notes baru, tetap gunakan notes yang sudah ada
            $updatedNotes = $existingNotes;
        }

        $attendance->update([
            'time_out' => $timeOut,
            'notes' => $updatedNotes ?: null,
            'note' => $updatedNotes ?: null, // Keep for backward compatibility
        ]);

        $this->attendance = $attendance->fresh();
        $this->showCheckOutModal = false;
        $this->checkOutNotes = '';
        $this->successMsg = __('Attendance Out Successful');
        
        Attendance::clearUserAttendanceCache(Auth::user(), Carbon::parse($attendance->date));
        
        $this->dispatch('attendance-updated');
    }

    public function render()
    {
        return view('livewire.attendance-form');
    }
}
