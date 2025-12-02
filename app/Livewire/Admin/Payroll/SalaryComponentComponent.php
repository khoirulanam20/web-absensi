<?php

namespace App\Livewire\Admin\Payroll;

use App\Models\SalaryComponent;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\InteractsWithBanner;
use Livewire\Component;

class SalaryComponentComponent extends Component
{
    use InteractsWithBanner;

    public $deleteName = null;
    public $confirmingDeletion = false;
    public $selectedId = null;
    public $creating = false;
    public $editing = false;

    // Form fields
    public $name = '';
    public $type = 'earning';
    public $is_daily = false;
    public $is_taxable = false;
    public $is_active = true;

    public function showCreating()
    {
        $this->resetForm();
        $this->creating = true;
    }

    public function edit($id)
    {
        $component = SalaryComponent::findOrFail($id);
        $this->selectedId = $component->id;
        $this->name = $component->name;
        $this->type = $component->type;
        $this->is_daily = (bool) $component->is_daily;
        $this->is_taxable = (bool) $component->is_taxable;
        $this->is_active = (bool) $component->is_active;
        $this->editing = true;
    }

    public function store()
    {
        if (Auth::user()->isNotAdmin) {
            return abort(403);
        }

        $this->validate([
            'name' => 'required|string|max:255|unique:salary_components,name',
            'type' => 'required|in:earning,deduction',
            'is_daily' => 'boolean',
            'is_taxable' => 'boolean',
            'is_active' => 'boolean',
        ]);

        SalaryComponent::create([
            'name' => $this->name,
            'type' => $this->type,
            'is_daily' => $this->is_daily,
            'is_taxable' => $this->is_taxable,
            'is_active' => $this->is_active,
        ]);

        $this->resetForm();
        $this->creating = false;
        $this->banner(__('Komponen gaji berhasil ditambahkan.'));
    }

    public function update()
    {
        if (Auth::user()->isNotAdmin) {
            return abort(403);
        }

        $this->validate([
            'name' => 'required|string|max:255|unique:salary_components,name,' . $this->selectedId,
            'type' => 'required|in:earning,deduction',
            'is_daily' => 'boolean',
            'is_taxable' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $component = SalaryComponent::findOrFail($this->selectedId);
        $component->update([
            'name' => $this->name,
            'type' => $this->type,
            'is_daily' => $this->is_daily,
            'is_taxable' => $this->is_taxable,
            'is_active' => $this->is_active,
        ]);

        $this->resetForm();
        $this->editing = false;
        $this->banner(__('Komponen gaji berhasil diperbarui.'));
    }

    public function confirmDeletion($id, $name)
    {
        $this->deleteName = $name;
        $this->confirmingDeletion = true;
        $this->selectedId = $id;
    }

    public function delete()
    {
        if (Auth::user()->isNotAdmin) {
            return abort(403);
        }

        $component = SalaryComponent::findOrFail($this->selectedId);
        $component->delete();

        $this->confirmingDeletion = false;
        $this->selectedId = null;
        $this->deleteName = null;
        $this->banner(__('Komponen gaji berhasil dihapus.'));
    }

    private function resetForm()
    {
        $this->name = '';
        $this->type = 'earning';
        $this->is_daily = false;
        $this->is_taxable = false;
        $this->is_active = true;
        $this->selectedId = null;
    }

    public function render()
    {
        $components = SalaryComponent::orderBy('type')->orderBy('name')->get();
        return view('livewire.admin.payroll.salary-component-component', [
            'components' => $components
        ]);
    }
}
