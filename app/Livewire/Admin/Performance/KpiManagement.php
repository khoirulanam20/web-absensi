<?php

namespace App\Livewire\Admin\Performance;

use App\Models\JobTitle;
use App\Models\Kpi;
use Livewire\Component;
use Livewire\WithPagination;
use Laravel\Jetstream\InteractsWithBanner;

class KpiManagement extends Component
{
    use WithPagination, InteractsWithBanner;

    public $search = '';
    public $showModal = false;
    public $showAssignModal = false;
    public $editingId = null;
    public $deleteId = null;
    public $confirmingDeletion = false;

    // Form properties
    public $code = '';
    public $title = '';
    public $description = '';
    public $unit = '';
    public $default_target = '';
    public $is_active = true;

    // Assign KPI to Job Title
    public $selectedKpiId = null;
    public $selectedJobTitleId = null;
    public $default_weight = '';
    public $default_target_role = '';

    protected $queryString = ['search'];

    public function mount()
    {
        $this->code = Kpi::generateCode();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function showCreating()
    {
        $this->resetForm();
        $this->code = Kpi::generateCode();
        $this->showModal = true;
        $this->editingId = null;
    }

    public function showEditing($id)
    {
        $kpi = Kpi::findOrFail($id);
        $this->editingId = $kpi->id;
        $this->code = $kpi->code;
        $this->title = $kpi->title;
        $this->description = $kpi->description;
        $this->unit = $kpi->unit;
        $this->default_target = $kpi->default_target;
        $this->is_active = $kpi->is_active;
        $this->showModal = true;
    }

    public function showAssigning($kpiId)
    {
        $this->selectedKpiId = $kpiId;
        $this->selectedJobTitleId = null;
        $this->default_weight = '';
        $this->default_target_role = '';
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
        $this->code = Kpi::generateCode();
        $this->title = '';
        $this->description = '';
        $this->unit = '';
        $this->default_target = '';
        $this->is_active = true;
    }

    public function save()
    {
        $this->validate([
            'code' => 'required|string|max:255|unique:kpis,code,' . ($this->editingId ?? ''),
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unit' => 'nullable|string|max:255',
            'default_target' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $data = [
            'code' => $this->code,
            'title' => $this->title,
            'description' => $this->description,
            'unit' => $this->unit,
            'default_target' => $this->default_target ?: null,
            'is_active' => $this->is_active,
        ];

        if ($this->editingId) {
            Kpi::findOrFail($this->editingId)->update($data);
            $this->banner(__('KPI updated successfully.'));
        } else {
            Kpi::create($data);
            $this->banner(__('KPI created successfully.'));
        }

        $this->closeModal();
    }

    public function assignToJobTitle()
    {
        $this->validate([
            'selectedKpiId' => 'required|exists:kpis,id',
            'selectedJobTitleId' => 'required|exists:job_titles,id',
            'default_weight' => 'required|numeric|min:0|max:100',
            'default_target_role' => 'nullable|numeric|min:0',
        ]);

        $kpi = Kpi::findOrFail($this->selectedKpiId);
        $kpi->jobTitles()->syncWithoutDetaching([
            $this->selectedJobTitleId => [
                'default_weight' => $this->default_weight,
                'default_target' => $this->default_target_role ?: null,
            ]
        ]);

        $this->banner(__('KPI assigned to job title successfully.'));
        $this->closeModal();
    }

    public function removeFromJobTitle($kpiId, $jobTitleId)
    {
        $kpi = Kpi::findOrFail($kpiId);
        $kpi->jobTitles()->detach($jobTitleId);
        $this->banner(__('KPI removed from job title successfully.'));
    }

    public function confirmDeletion($id)
    {
        $this->deleteId = $id;
        $this->confirmingDeletion = true;
    }

    public function delete()
    {
        if ($this->deleteId) {
            Kpi::findOrFail($this->deleteId)->delete();
            $this->confirmingDeletion = false;
            $this->deleteId = null;
            $this->banner(__('KPI deleted successfully.'));
        }
    }

    public function render()
    {
        $kpis = Kpi::query()
            ->when($this->search, function ($query) {
                $query->where('code', 'like', '%' . $this->search . '%')
                    ->orWhere('title', 'like', '%' . $this->search . '%');
            })
            ->with('jobTitles')
            ->latest()
            ->paginate(10);

        $jobTitles = JobTitle::all();

        return view('livewire.admin.performance.kpi-management', [
            'kpis' => $kpis,
            'jobTitles' => $jobTitles,
        ]);
    }
}
