<?php

namespace App\Livewire\Admin\Performance;

use App\Models\Assessment;
use App\Models\ReviewCycle;
use Livewire\Component;
use Livewire\WithPagination;

class AdminPerformanceReview extends Component
{
    use WithPagination;

    public $search = '';
    public $cycleFilter = '';
    public $statusFilter = '';
    public $divisionFilter = '';

    protected $queryString = ['search', 'cycleFilter', 'statusFilter', 'divisionFilter'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCycleFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingDivisionFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $assessments = Assessment::query()
            ->when($this->search, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('nip', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->cycleFilter, function ($query) {
                $query->where('review_cycle_id', $this->cycleFilter);
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->divisionFilter, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('division_id', $this->divisionFilter);
                });
            })
            ->with(['user.division', 'reviewCycle', 'items'])
            ->latest()
            ->paginate(15);

        $cycles = ReviewCycle::latest()->get();
        $divisions = \App\Models\Division::all();

        // Statistics
        $totalAssessments = Assessment::count();
        $pendingSelf = Assessment::where('status', 'pending_self')->count();
        $pendingManager = Assessment::where('status', 'pending_manager')->count();
        $pending360 = Assessment::where('status', 'pending_360')->count();
        $completed = Assessment::where('status', 'completed')->count();

        return view('livewire.admin.performance.admin-performance-review', [
            'assessments' => $assessments,
            'cycles' => $cycles,
            'divisions' => $divisions,
            'totalAssessments' => $totalAssessments,
            'pendingSelf' => $pendingSelf,
            'pendingManager' => $pendingManager,
            'pending360' => $pending360,
            'completed' => $completed,
        ]);
    }
}
