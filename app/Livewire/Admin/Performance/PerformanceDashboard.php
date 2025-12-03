<?php

namespace App\Livewire\Admin\Performance;

use App\Models\Assessment;
use App\Models\ReviewCycle;
use Livewire\Component;

class PerformanceDashboard extends Component
{
    public function render()
    {
        $activeCycles = ReviewCycle::where('status', 'open')->count();
        $totalCycles = ReviewCycle::count();
        $totalAssessments = Assessment::count();
        $completedAssessments = Assessment::where('status', 'completed')->count();
        
        $recentCycles = ReviewCycle::with('creator')
            ->latest()
            ->take(5)
            ->get();

        $topPerformers = Assessment::where('status', 'completed')
            ->with('user')
            ->orderBy('overall_score', 'desc')
            ->take(5)
            ->get();

        return view('livewire.admin.performance.performance-dashboard', [
            'activeCycles' => $activeCycles,
            'totalCycles' => $totalCycles,
            'totalAssessments' => $totalAssessments,
            'completedAssessments' => $completedAssessments,
            'recentCycles' => $recentCycles,
            'topPerformers' => $topPerformers,
        ]);
    }
}
