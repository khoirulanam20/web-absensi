<?php

namespace App\Livewire\Performance;

use App\Models\Assessment;
use App\Models\ReviewCycle;
use Livewire\Component;

class PerformanceIndex extends Component
{
    public function render()
    {
        $userId = auth()->id();
        $user = auth()->user();
        
        // Check if user is manager (has subordinates or is admin)
        $isManager = $user->isAdmin || $this->hasSubordinates($userId);
        
        // Get open review cycles for self-assessment
        $openCycles = ReviewCycle::where('status', 'open')
            ->whereHas('assignments', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->with(['assessments' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }])
            ->latest()
            ->get();

        // Get all assessments for this user
        $assessments = Assessment::where('user_id', $userId)
            ->with(['reviewCycle', 'items'])
            ->latest()
            ->get();

        // Get completed assessments
        $completedAssessments = $assessments->where('status', 'completed');

        // Get cycles where user needs to review as manager
        $managerCycles = collect();
        if ($isManager) {
            $managerCycles = ReviewCycle::where('status', 'open')
                ->whereHas('assessments', function ($query) use ($userId) {
                    $query->where('status', 'pending_manager')
                        ->where('user_id', '!=', $userId);
                })
                ->with(['assessments' => function ($query) use ($userId) {
                    $query->where('status', 'pending_manager')
                        ->where('user_id', '!=', $userId)
                        ->with('user');
                }])
                ->latest()
                ->get();
        }

        return view('livewire.performance.performance-index', [
            'openCycles' => $openCycles,
            'assessments' => $assessments,
            'completedAssessments' => $completedAssessments,
            'isManager' => $isManager,
            'managerCycles' => $managerCycles,
        ]);
    }

    private function hasSubordinates($userId)
    {
        // Simple check: if user has job title that suggests management role
        // Or you can implement a more complex hierarchy check
        // For now, we'll check if there are any assessments pending manager review
        return Assessment::where('status', 'pending_manager')
            ->whereHas('user', function ($query) use ($userId) {
                // You can add more complex logic here based on division, job title, etc.
                $query->where('id', '!=', $userId);
            })
            ->exists();
    }
}
