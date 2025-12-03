<?php

namespace App\Livewire\Performance;

use App\Models\Assessment;
use App\Models\AssessmentItem;
use App\Models\ReviewCycle;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Laravel\Jetstream\InteractsWithBanner;

class ManagerReviewForm extends Component
{
    use WithPagination, InteractsWithBanner;

    public $cycleId;
    public $cycle;
    public $selectedUserId = null;
    public $selectedAssessment = null;
    public $items = [];
    public $saving = false;
    public $showReviewModal = false;

    public function mount($cycleId)
    {
        $this->cycleId = $cycleId;
        $this->cycle = ReviewCycle::findOrFail($cycleId);
        
        if (!$this->cycle->isOpen()) {
            abort(403, 'Review cycle is not open.');
        }
    }

    public function boot()
    {
        // Check if there's a user parameter in query string after component is booted
        if (request()->has('user')) {
            $userId = request()->get('user');
            $this->showReview($userId);
        }
    }

    public function showReview($userId)
    {
        $this->selectedUserId = $userId;
        $this->selectedAssessment = Assessment::where('review_cycle_id', $this->cycleId)
            ->where('user_id', $userId)
            ->with('items.kpiAssignment.kpi')
            ->first();

        if (!$this->selectedAssessment) {
            $this->banner(__('Assessment not found for this user.'), 'error');
            return;
        }

        if ($this->selectedAssessment->status !== 'pending_manager') {
            $this->banner(__('This assessment is not ready for manager review.'), 'error');
            return;
        }

        $this->loadItems();
        $this->showReviewModal = true;
    }

    public function loadItems()
    {
        if (!$this->selectedAssessment) {
            return;
        }

        $this->items = [];
        foreach ($this->selectedAssessment->items as $item) {
            $this->items[$item->id] = [
                'assessment_item_id' => $item->id,
                'kpi_title' => $item->kpiAssignment->kpi->title,
                'kpi_description' => $item->kpiAssignment->kpi->description,
                'target' => $item->kpiAssignment->target,
                'unit' => $item->kpiAssignment->kpi->unit,
                'self_score' => $item->self_score,
                'comment' => $item->comment,
                'manager_score' => $item->manager_score ?? '',
                'manager_comment' => $item->manager_comment ?? '',
            ];
        }
    }

    public function closeModal()
    {
        $this->showReviewModal = false;
        $this->selectedUserId = null;
        $this->selectedAssessment = null;
        $this->items = [];
    }

    public function save()
    {
        $this->saving = true;

        $this->validate([
            'items.*.manager_score' => 'required|numeric|min:0|max:100',
            'items.*.manager_comment' => 'nullable|string',
        ]);

        foreach ($this->items as $itemData) {
            $item = AssessmentItem::findOrFail($itemData['assessment_item_id']);
            
            $item->update([
                'manager_score' => $itemData['manager_score'],
                'manager_comment' => $itemData['manager_comment'] ?? null,
            ]);

            // Calculate final score
            $item->final_score = $item->calculateFinalScore();
            $item->save();
        }

        // Update assessment status
        $nextStatus = $this->cycle->enable_360_review ? 'pending_360' : 'completed';
        $this->selectedAssessment->update([
            'status' => $nextStatus,
            'overall_score' => $this->selectedAssessment->calculateOverallScore(),
        ]);

        if ($nextStatus === 'completed') {
            $this->selectedAssessment->update(['completed_at' => now()]);
        }

        $this->saving = false;
        $this->banner(__('Manager review saved successfully.'));
        $this->closeModal();
    }

    public function render()
    {
        $managerId = auth()->id();
        
        // Get users that need manager review
        // For now, we'll get all users with pending_manager status in this cycle
        // In a real scenario, you might want to filter by division or hierarchy
        $pendingAssessments = Assessment::where('review_cycle_id', $this->cycleId)
            ->where('status', 'pending_manager')
            ->with('user')
            ->get();

        $users = $pendingAssessments->pluck('user')->unique('id');

        return view('livewire.performance.manager-review-form', [
            'users' => $users,
            'pendingAssessments' => $pendingAssessments,
        ]);
    }
}
