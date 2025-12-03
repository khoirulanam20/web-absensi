<?php

namespace App\Livewire\Admin\Asset;

use App\Models\Asset;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\InteractsWithBanner;
use Livewire\Component;
use Livewire\WithPagination;

class AssetComponent extends Component
{
    use WithPagination, InteractsWithBanner;

    public $search = '';
    public $showModal = false;
    public $editingId = null;
    public $confirmingDeletion = false;
    public $deleteId = null;
    public $deleteName = null;

    // Form properties
    public $asset_code = '';
    public $name = '';
    public $description = '';
    public $category = 'other';
    public $brand = '';
    public $model = '';
    public $serial_number = '';
    public $purchase_date = '';
    public $purchase_price = '';
    public $current_value = '';
    public $status = 'available';
    public $location = '';
    public $assigned_to = null;
    public $assigned_date = '';
    public $notes = '';

    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function showCreating()
    {
        $this->resetForm();
        $this->asset_code = Asset::generateAssetCode();
        $this->editingId = null;
        $this->showModal = true;
    }

    public function showEditing($id)
    {
        $asset = Asset::findOrFail($id);
        $this->editingId = $asset->id;
        $this->asset_code = $asset->asset_code;
        $this->name = $asset->name;
        $this->description = $asset->description ?? '';
        $this->category = $asset->category;
        $this->brand = $asset->brand ?? '';
        $this->model = $asset->model ?? '';
        $this->serial_number = $asset->serial_number ?? '';
        $this->purchase_date = $asset->purchase_date ? $asset->purchase_date->format('Y-m-d') : '';
        $this->purchase_price = $asset->purchase_price ?? '';
        $this->current_value = $asset->current_value ?? '';
        $this->status = $asset->status;
        $this->location = $asset->location ?? '';
        $this->assigned_to = $asset->assigned_to;
        $this->assigned_date = $asset->assigned_date ? $asset->assigned_date->format('Y-m-d') : '';
        $this->notes = $asset->notes ?? '';
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->resetForm();
        $this->editingId = null;
        $this->showModal = false;
    }

    public function resetForm()
    {
        $this->resetValidation();
        $this->asset_code = '';
        $this->name = '';
        $this->description = '';
        $this->category = 'other';
        $this->brand = '';
        $this->model = '';
        $this->serial_number = '';
        $this->purchase_date = '';
        $this->purchase_price = '';
        $this->current_value = '';
        $this->status = 'available';
        $this->location = '';
        $this->assigned_to = null;
        $this->assigned_date = '';
        $this->notes = '';
    }

    public function save()
    {
        $this->validate([
            'asset_code' => 'required|string|max:255|unique:assets,asset_code,' . ($this->editingId ?? ''),
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:hardware,software,furniture,vehicle,equipment,other',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'serial_number' => [
                'nullable',
                'string',
                'max:255',
                \Illuminate\Validation\Rule::unique('assets', 'serial_number')->ignore($this->editingId),
            ],
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'current_value' => 'nullable|numeric|min:0',
            'status' => 'required|in:available,in_use,maintenance,damaged,disposed',
            'location' => 'nullable|string|max:255',
            'assigned_to' => 'nullable|exists:users,id',
            'assigned_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $data = [
            'asset_code' => $this->asset_code,
            'name' => $this->name,
            'description' => $this->description,
            'category' => $this->category,
            'brand' => $this->brand,
            'model' => $this->model,
            'serial_number' => $this->serial_number ?: null,
            'purchase_date' => $this->purchase_date ?: null,
            'purchase_price' => $this->purchase_price ?: null,
            'current_value' => $this->current_value ?: null,
            'status' => $this->status,
            'location' => $this->location,
            'assigned_to' => $this->assigned_to ?: null,
            'assigned_date' => $this->assigned_date ?: null,
            'notes' => $this->notes,
        ];

        if ($this->editingId) {
            $asset = Asset::findOrFail($this->editingId);
            $asset->update($data);
            $this->banner(__('Aset berhasil diperbarui.'));
        } else {
            Asset::create($data);
            $this->banner(__('Aset berhasil ditambahkan.'));
        }

        $this->closeModal();
    }

    public function confirmDeletion($id, $name)
    {
        $this->deleteId = $id;
        $this->deleteName = $name;
        $this->confirmingDeletion = true;
    }

    public function delete()
    {
        if (Auth::user()->isNotAdmin) {
            return abort(403);
        }
        Asset::findOrFail($this->deleteId)->delete();
        $this->confirmingDeletion = false;
        $this->deleteId = null;
        $this->deleteName = null;
        $this->banner(__('Aset berhasil dihapus.'));
    }

    public function render()
    {
        $assets = Asset::query()
            ->with('assignedUser')
            ->when($this->search, function ($query) {
                $query->where('asset_code', 'like', '%' . $this->search . '%')
                    ->orWhere('name', 'like', '%' . $this->search . '%')
                    ->orWhere('serial_number', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        $users = User::where('group', 'user')->orderBy('name')->get();
        $categories = [
            'hardware' => 'Hardware',
            'software' => 'Software',
            'furniture' => 'Furniture',
            'vehicle' => 'Kendaraan',
            'equipment' => 'Peralatan',
            'other' => 'Lainnya',
        ];
        $statuses = [
            'available' => 'Tersedia',
            'in_use' => 'Digunakan',
            'maintenance' => 'Maintenance',
            'damaged' => 'Rusak',
            'disposed' => 'Dibuang',
        ];

        return view('livewire.admin.asset.asset-component', [
            'assets' => $assets,
            'users' => $users,
            'categories' => $categories,
            'statuses' => $statuses,
        ]);
    }
}
