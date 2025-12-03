<?php

namespace App\Livewire\Performance;

use App\Models\Assessment;
use App\Models\AssessmentItem;
use App\Models\KpiAssignment;
use App\Models\ReviewCycle;
use Livewire\Component;
use Laravel\Jetstream\InteractsWithBanner;

class SelfAssessmentForm extends Component
{
    use InteractsWithBanner;

    public $cycleId;
    public $cycle;
    public $assessment;
    public $items = [];
    public $saving = false;

    public function mount($cycleId)
    {
        $this->cycleId = $cycleId;
        $this->cycle = ReviewCycle::findOrFail($cycleId);
        
        if (!$this->cycle->isOpen()) {
            abort(403, 'Review cycle is not open.');
        }

        // Get or create assessment
        $this->assessment = Assessment::firstOrCreate(
            [
                'review_cycle_id' => $this->cycleId,
                'user_id' => auth()->id(),
            ],
            [
                'status' => 'pending_self',
            ]
        );

        // Load items
        $this->loadItems();
    }

    public function loadItems()
    {
        $assignments = KpiAssignment::where('review_cycle_id', $this->cycleId)
            ->where('user_id', auth()->id())
            ->with('kpi')
            ->get();

        foreach ($assignments as $assignment) {
            $item = AssessmentItem::firstOrNew([
                'assessment_id' => $this->assessment->id,
                'kpi_assignment_id' => $assignment->id,
            ]);

            $this->items[$assignment->id] = [
                'kpi_assignment_id' => $assignment->id,
                'kpi_title' => $assignment->kpi->title,
                'kpi_description' => $assignment->kpi->description,
                'target' => $assignment->target,
                'unit' => $assignment->kpi->unit,
                'self_score' => $item->self_score ?? '',
                'comment' => $item->comment ?? '',
                'evidence' => $item->evidence ?? ['files' => [], 'notes' => ''],
            ];
        }
    }

    public function updatedItems($value, $key)
    {
        // Autosave logic can be added here
    }

    public function save()
    {
        $this->saving = true;

        $this->validate([
            'items.*.self_score' => 'required|numeric|min:0|max:100',
            'items.*.comment' => 'nullable|string',
        ]);

        foreach ($this->items as $itemData) {
            $assignment = KpiAssignment::findOrFail($itemData['kpi_assignment_id']);
            
            AssessmentItem::updateOrCreate(
                [
                    'assessment_id' => $this->assessment->id,
                    'kpi_assignment_id' => $assignment->id,
                ],
                [
                    'self_score' => $itemData['self_score'],
                    'comment' => $itemData['comment'] ?? null,
                    'evidence' => $itemData['evidence'] ?? ['files' => [], 'notes' => ''],
                ]
            );
        }

        // Update assessment status
        $this->assessment->update(['status' => 'pending_manager']);

        $this->saving = false;
        $this->banner(__('Self-assessment saved successfully.'));
    }

    public function submit()
    {
        $this->save();
        $this->banner(__('Self-assessment submitted successfully.'));
        return redirect()->route('performance.self', $this->cycleId);
    }

    public function render()
    {
        return view('livewire.performance.self-assessment-form');
    }
}
