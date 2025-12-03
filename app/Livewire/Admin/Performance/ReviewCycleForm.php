<?php

namespace App\Livewire\Admin\Performance;

use App\Models\JobTitle;
use App\Models\Kpi;
use App\Models\KpiAssignment;
use App\Models\ReviewCycle;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Laravel\Jetstream\InteractsWithBanner;

class ReviewCycleForm extends Component
{
    use WithPagination, InteractsWithBanner;

    public $showModal = false;
    public $editingId = null;
    public $deleteId = null;
    public $confirmingDeletion = false;
    public $showAssignModal = false;

    // Form properties
    public $name = '';
    public $start_date = '';
    public $end_date = '';
    public $status = 'draft';
    public $description = '';
    public $enable_360_review = false;

    // Assign users
    public $selectedCycleId = null;
    public $selectedJobTitleIds = [];
    public $selectedUserIds = [];

    public function showCreating()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->editingId = null;
    }

    public function showEditing($id)
    {
        $cycle = ReviewCycle::findOrFail($id);
        $this->editingId = $cycle->id;
        $this->name = $cycle->name;
        $this->start_date = $cycle->start_date->format('Y-m-d');
        $this->end_date = $cycle->end_date->format('Y-m-d');
        $this->status = $cycle->status;
        $this->description = $cycle->description;
        $this->enable_360_review = $cycle->enable_360_review;
        $this->showModal = true;
    }

    public function showAssigning($cycleId)
    {
        $this->selectedCycleId = $cycleId;
        $this->selectedJobTitleIds = [];
        $this->selectedUserIds = [];
        $this->showAssignModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->showAssignModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->resetValidation();
        $this->editingId = null;
        $this->name = '';
        $this->start_date = '';
        $this->end_date = '';
        $this->status = 'draft';
        $this->description = '';
        $this->enable_360_review = false;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:draft,open,closed',
            'description' => 'nullable|string',
            'enable_360_review' => 'boolean',
        ]);

        $data = [
            'name' => $this->name,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
            'description' => $this->description,
            'enable_360_review' => $this->enable_360_review,
            'created_by' => auth()->id(),
        ];

        if ($this->editingId) {
            ReviewCycle::findOrFail($this->editingId)->update($data);
            $this->banner(__('Review cycle updated successfully.'));
        } else {
            ReviewCycle::create($data);
            $this->banner(__('Review cycle created successfully.'));
        }

        $this->closeModal();
    }

    public function assignUsers()
    {
        $this->validate([
            'selectedCycleId' => 'required|exists:review_cycles,id',
            'selectedJobTitleIds' => 'nullable|array',
            'selectedUserIds' => 'nullable|array',
        ]);

        $cycle = ReviewCycle::findOrFail($this->selectedCycleId);
        $userIds = [];

        // Get users from selected job titles
        if (!empty($this->selectedJobTitleIds)) {
            $usersFromJobTitles = User::whereIn('job_title_id', $this->selectedJobTitleIds)
                ->where('group', 'user')
                ->pluck('id')
                ->toArray();
            $userIds = array_merge($userIds, $usersFromJobTitles);
        }

        // Add directly selected users
        if (!empty($this->selectedUserIds)) {
            $userIds = array_merge($userIds, $this->selectedUserIds);
        }

        $userIds = array_unique($userIds);

        // Assign KPIs to each user based on their job title
        foreach ($userIds as $userId) {
            $user = User::find($userId);
            if (!$user || !$user->jobTitle) {
                continue;
            }

            // Get KPIs assigned to user's job title
            $kpis = $user->jobTitle->kpis()->where('is_active', true)->get();

            foreach ($kpis as $kpi) {
                $pivot = $kpi->jobTitles()->where('job_title_id', $user->job_title_id)->first();
                $defaultWeight = $pivot->pivot->default_weight ?? 0;
                $defaultTarget = $pivot->pivot->default_target ?? $kpi->default_target;

                // Check if assignment already exists
                $existing = KpiAssignment::where('review_cycle_id', $cycle->id)
                    ->where('user_id', $userId)
                    ->where('kpi_id', $kpi->id)
                    ->first();

                if (!$existing) {
                    KpiAssignment::create([
                        'review_cycle_id' => $cycle->id,
                        'user_id' => $userId,
                        'kpi_id' => $kpi->id,
                        'target' => $defaultTarget,
                        'weight' => $defaultWeight,
                    ]);
                }
            }
        }

        $this->banner(__('Users assigned to review cycle successfully.'));
        $this->closeModal();
    }

    public function openCycle($id)
    {
        $cycle = ReviewCycle::findOrFail($id);
        $cycle->update(['status' => 'open']);
        $this->banner(__('Review cycle opened successfully.'));
    }

    public function closeCycle($id)
    {
        $cycle = ReviewCycle::findOrFail($id);
        $cycle->update(['status' => 'closed']);
        $this->banner(__('Review cycle closed successfully.'));
    }

    public function confirmDeletion($id)
    {
        $this->deleteId = $id;
        $this->confirmingDeletion = true;
    }

    public function delete()
    {
        if ($this->deleteId) {
            ReviewCycle::findOrFail($this->deleteId)->delete();
            $this->confirmingDeletion = false;
            $this->deleteId = null;
            $this->banner(__('Review cycle deleted successfully.'));
        }
    }

    public function render()
    {
        $cycles = ReviewCycle::with('creator')
            ->latest()
            ->paginate(10);

        $jobTitles = JobTitle::all();
        $users = User::where('group', 'user')->get();

        return view('livewire.admin.performance.review-cycle-form', [
            'cycles' => $cycles,
            'jobTitles' => $jobTitles,
            'users' => $users,
        ]);
    }
}
