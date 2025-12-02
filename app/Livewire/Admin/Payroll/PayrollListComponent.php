<?php

namespace App\Livewire\Admin\Payroll;

use App\Models\Payroll;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\InteractsWithBanner;
use Livewire\Component;
use Livewire\WithPagination;

class PayrollListComponent extends Component
{
    use WithPagination, InteractsWithBanner;

    public $period = '';
    public $status = '';

    public function mount()
    {
        $this->period = date('Y-m');
    }

    public function publish($id)
    {
        if (Auth::user()->isNotAdmin) {
            return abort(403);
        }

        $payroll = Payroll::findOrFail($id);
        if ($payroll->status === 'draft') {
            $payroll->update(['status' => 'published']);
            $this->banner(__('Payroll berhasil dipublish.'));
        }
    }

    public function markAsPaid($id)
    {
        if (Auth::user()->isNotAdmin) {
            return abort(403);
        }

        $payroll = Payroll::findOrFail($id);
        if ($payroll->status === 'published') {
            $payroll->update(['status' => 'paid']);
            $this->banner(__('Payroll ditandai sebagai sudah dibayar.'));
        }
    }

    public function render()
    {
        $payrolls = Payroll::with(['user.division', 'user.jobTitle'])
            ->when($this->period, function ($query) {
                $query->where('period', $this->period);
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->orderBy('period', 'desc')
            ->orderBy('user_id')
            ->paginate(20);

        return view('livewire.admin.payroll.payroll-list-component', [
            'payrolls' => $payrolls
        ]);
    }
}
