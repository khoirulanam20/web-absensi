<?php

namespace App\Livewire\Payroll;

use App\Models\Payroll;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class MyPayrollComponent extends Component
{
    use WithPagination;

    public $period = '';

    public function mount()
    {
        $this->period = date('Y-m');
    }

    public function render()
    {
        $payrolls = Payroll::where('user_id', Auth::id())
            ->when($this->period, function ($query) {
                $query->where('period', $this->period);
            })
            ->where('status', '!=', 'draft')
            ->orderBy('period', 'desc')
            ->paginate(10);

        return view('livewire.payroll.my-payroll-component', [
            'payrolls' => $payrolls
        ]);
    }
}
