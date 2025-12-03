<?php

namespace App\Livewire\Performance;

use App\Models\Assessment;
use App\Models\ReviewCycle;
use Livewire\Component;
use Livewire\WithPagination;

class ManagerHistory extends Component
{
    use WithPagination;

    public $search = '';
    public $cycleFilter = '';

    protected $queryString = ['search', 'cycleFilter'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCycleFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $managerId = auth()->id();
        
        // Get all assessments that have been reviewed by this manager
        // We check if assessment items have manager_score filled
        $assessments = Assessment::query()
            ->whereHas('items', function ($query) {
                $query->whereNotNull('manager_score');
            })
            ->where('user_id', '!=', $managerId) // Exclude own assessments
            ->when($this->search, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('nip', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->cycleFilter, function ($query) {
                $query->where('review_cycle_id', $this->cycleFilter);
            })
            ->with(['user', 'reviewCycle', 'items'])
            ->latest()
            ->paginate(10);

        $cycles = ReviewCycle::where('status', '!=', 'draft')
            ->latest()
            ->get();

        return view('livewire.performance.manager-history', [
            'assessments' => $assessments,
            'cycles' => $cycles,
        ]);
    }
}
