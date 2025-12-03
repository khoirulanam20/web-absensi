<?php

namespace App\Livewire\Performance;

use App\Models\Assessment;
use Livewire\Component;

class AssessmentResult extends Component
{
    public $assessmentId;
    public $assessment;

    public function mount($assessmentId)
    {
        $this->assessmentId = $assessmentId;
        $this->assessment = Assessment::with([
            'reviewCycle',
            'user',
            'items.kpiAssignment.kpi',
            'items.reviews.reviewer'
        ])->findOrFail($assessmentId);

        // Check authorization
        $user = auth()->user();
        
        // Admin can see all assessments
        if ($user->isAdmin) {
            return;
        }
        
        // User can see their own assessment
        if ($this->assessment->user_id === $user->id) {
            return;
        }
        
        // Manager can see assessments they have reviewed
        $hasReviewed = $this->assessment->items()
            ->whereNotNull('manager_score')
            ->exists();
        
        if ($hasReviewed) {
            return;
        }
        
        // If none of the above, deny access
        abort(403, 'Unauthorized access.');
    }

    public function render()
    {
        // Calculate scores per KPI
        $kpiScores = [];
        foreach ($this->assessment->items as $item) {
            $kpiScores[] = [
                'kpi_title' => $item->kpiAssignment->kpi->title,
                'self_score' => $item->self_score,
                'manager_score' => $item->manager_score,
                'final_score' => $item->final_score,
                'weight' => $item->kpiAssignment->weight,
            ];
        }

        return view('livewire.performance.assessment-result', [
            'kpiScores' => $kpiScores,
        ]);
    }
}
