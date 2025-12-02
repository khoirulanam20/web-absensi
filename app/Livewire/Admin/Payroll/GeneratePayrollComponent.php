<?php

namespace App\Livewire\Admin\Payroll;

use App\Models\Attendance;
use App\Models\EmployeeSalary;
use App\Models\Payroll;
use App\Models\PayrollDetail;
use App\Models\SalaryComponent;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Jetstream\InteractsWithBanner;
use Livewire\Component;

class GeneratePayrollComponent extends Component
{
    use InteractsWithBanner;

    public $period = '';
    public $paymentDate = '';
    public $isGenerating = false;
    public $generatedCount = 0;

    public function mount()
    {
        $this->period = date('Y-m');
        $this->paymentDate = date('Y-m-d');
        $this->generatedCount = 0;
    }

    public function generate()
    {
        if (Auth::user()->isNotAdmin) {
            return abort(403);
        }

        $this->validate([
            'period' => 'required|date_format:Y-m',
            'paymentDate' => 'required|date',
        ]);

        $this->isGenerating = true;
        $this->generatedCount = 0;

        try {
            DB::beginTransaction();

            $periodDate = Carbon::parse($this->period . '-01');
            $startDate = $periodDate->copy()->startOfMonth();
            $endDate = $periodDate->copy()->endOfMonth();

            // Get all active users
            $users = User::where('group', 'user')->get();

            foreach ($users as $user) {
                // Check if payroll already exists for this period
                $existingPayroll = Payroll::where('user_id', $user->id)
                    ->where('period', $this->period)
                    ->first();

                if ($existingPayroll && $existingPayroll->status !== 'draft') {
                    continue; // Skip if already published or paid
                }

                // Get employee salaries
                $employeeSalaries = EmployeeSalary::where('user_id', $user->id)
                    ->with('salaryComponent')
                    ->get();

                if ($employeeSalaries->isEmpty()) {
                    continue; // Skip if no salary setup
                }

                // Get basic salary component
                $basicSalaryComponent = SalaryComponent::where('name', 'Gaji Pokok')->first();
                $basicSalary = 0;
                if ($basicSalaryComponent) {
                    $basicSalary = EmployeeSalary::where('user_id', $user->id)
                        ->where('salary_component_id', $basicSalaryComponent->id)
                        ->value('amount') ?? 0;
                }

                // Count attendance (status: hadir, present)
                $totalAttendance = Attendance::where('user_id', $user->id)
                    ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                    ->whereIn('status', ['hadir', 'present'])
                    ->count();

                // Calculate earnings and deductions
                $totalAllowance = 0;
                $totalDeduction = 0;
                $payrollDetails = [];

                foreach ($employeeSalaries as $employeeSalary) {
                    $component = $employeeSalary->salaryComponent;
                    
                    if (!$component) {
                        continue; // Skip if component not found
                    }
                    
                    // Skip basic salary, already handled
                    if (strtolower($component->name) === 'gaji pokok') {
                        continue;
                    }

                    $amount = $employeeSalary->amount;
                    $quantity = 1;

                    // If daily component, multiply by attendance
                    if ($component->is_daily) {
                        $quantity = $totalAttendance;
                    }

                    $total = $amount * $quantity;

                    if ($component->type === 'earning') {
                        $totalAllowance += $total;
                    } else {
                        $totalDeduction += $total;
                    }

                    $payrollDetails[] = [
                        'component_name' => $component->name,
                        'type' => $component->type,
                        'amount' => $amount,
                        'quantity' => $quantity,
                        'total' => $total,
                    ];
                }

                $netSalary = $basicSalary + $totalAllowance - $totalDeduction;

                // Create or update payroll
                $payroll = Payroll::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'period' => $this->period,
                    ],
                    [
                        'payment_date' => $this->paymentDate,
                        'total_attendance' => $totalAttendance,
                        'basic_salary' => $basicSalary,
                        'total_allowance' => $totalAllowance,
                        'total_deduction' => $totalDeduction,
                        'net_salary' => $netSalary,
                        'status' => 'draft',
                    ]
                );

                // Delete existing details
                PayrollDetail::where('payroll_id', $payroll->id)->delete();

                // Create payroll details
                foreach ($payrollDetails as $detail) {
                    PayrollDetail::create([
                        'payroll_id' => $payroll->id,
                        'component_name' => $detail['component_name'],
                        'type' => $detail['type'],
                        'amount' => $detail['amount'],
                        'quantity' => $detail['quantity'],
                        'total' => $detail['total'],
                    ]);
                }

                $this->generatedCount++;
            }

            DB::commit();
            $this->banner("Payroll berhasil di-generate untuk {$this->generatedCount} karyawan.");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->banner('Terjadi kesalahan: ' . $e->getMessage(), 'danger');
        } finally {
            $this->isGenerating = false;
        }
    }

    public function render()
    {
        return view('livewire.admin.payroll.generate-payroll-component');
    }
}
