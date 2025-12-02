<?php

namespace App\Livewire\Admin\Payroll;

use App\Models\EmployeeSalary;
use App\Models\SalaryComponent;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\InteractsWithBanner;
use Livewire\Component;
use Livewire\WithPagination;

class EmployeeSalaryComponent extends Component
{
    use WithPagination, InteractsWithBanner;

    public $selectedUserId = null;
    public $selectedUserName = null;
    public $showSalaryModal = false;
    public $salaryComponents = [];
    public $basicSalary = 0;
    public $search = '';
    public $selectedUserIdForModal = null;

    public function updatedSelectedUserIdForModal()
    {
        if ($this->selectedUserIdForModal) {
            $this->openSalaryModal();
        }
    }

    public function openSalaryModal()
    {
        $userId = $this->selectedUserIdForModal;
        
        if ($userId === null) {
            return;
        }
        
        // Handle both string and ULID format
        $userId = (string) $userId;
        $user = User::findOrFail($userId);
        $this->selectedUserId = $userId;
        $this->selectedUserName = $user->name;
        
        // Load existing salaries
        $existingSalaries = EmployeeSalary::where('user_id', $userId)
            ->with('salaryComponent')
            ->get();
        $this->basicSalary = 0;
        $this->salaryComponents = [];
        
        foreach ($existingSalaries as $salary) {
            if ($salary->salaryComponent && ($salary->salaryComponent->name === 'Gaji Pokok' || strtolower($salary->salaryComponent->name) === 'gaji pokok')) {
                $this->basicSalary = $salary->amount;
            } else {
                $this->salaryComponents[] = [
                    'id' => $salary->id,
                    'component_id' => $salary->salary_component_id,
                    'amount' => $salary->amount,
                ];
            }
        }
        
        $this->showSalaryModal = true;
    }

    public function addComponent()
    {
        $this->salaryComponents[] = [
            'id' => null,
            'component_id' => null,
            'amount' => 0,
        ];
    }

    public function removeComponent($index)
    {
        $index = (int) $index;
        if (isset($this->salaryComponents[$index])) {
            unset($this->salaryComponents[$index]);
            $this->salaryComponents = array_values($this->salaryComponents);
        }
    }

    public function save()
    {
        if (Auth::user()->isNotAdmin) {
            return abort(403);
        }

        $this->validate([
            'basicSalary' => 'required|numeric|min:0',
            'salaryComponents.*.component_id' => 'required|exists:salary_components,id',
            'salaryComponents.*.amount' => 'required|numeric|min:0',
        ]);

        // Find or create Gaji Pokok component
        $basicSalaryComponent = SalaryComponent::firstOrCreate(
            ['name' => 'Gaji Pokok'],
            ['type' => 'earning', 'is_daily' => false, 'is_taxable' => true, 'is_active' => true]
        );

        // Save basic salary
        EmployeeSalary::updateOrCreate(
            [
                'user_id' => $this->selectedUserId,
                'salary_component_id' => $basicSalaryComponent->id,
            ],
            ['amount' => $this->basicSalary]
        );

        // Delete existing salaries (except basic salary)
        EmployeeSalary::where('user_id', $this->selectedUserId)
            ->where('salary_component_id', '!=', $basicSalaryComponent->id)
            ->delete();

        // Save other components
        foreach ($this->salaryComponents as $component) {
            if ($component['component_id']) {
                EmployeeSalary::updateOrCreate(
                    [
                        'user_id' => $this->selectedUserId,
                        'salary_component_id' => $component['component_id'],
                    ],
                    ['amount' => $component['amount']]
                );
            }
        }

        $this->showSalaryModal = false;
        $this->reset(['selectedUserId', 'selectedUserName', 'salaryComponents', 'basicSalary', 'selectedUserIdForModal']);
        $this->banner(__('Gaji karyawan berhasil disimpan.'));
    }

    public function render()
    {
        $users = User::with(['division', 'jobTitle'])
            ->where('group', 'user')
            ->when($this->search, function (Builder $q) {
                return $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('nip', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name')
            ->paginate(20);

        $availableComponents = SalaryComponent::where('is_active', true)
            ->where('name', '!=', 'Gaji Pokok')
            ->orderBy('type')
            ->orderBy('name')
            ->get();

        return view('livewire.admin.payroll.employee-salary-component', [
            'users' => $users,
            'availableComponents' => $availableComponents,
        ]);
    }
}
