<?php

namespace App\Livewire\Admin;

use App\Models\LocationSetting;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\InteractsWithBanner;
use Livewire\Component;

class LocationSettingComponent extends Component
{
    use InteractsWithBanner;

    public $deleteName = null;
    public $confirmingDeletion = false;
    public $selectedId = null;

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
        $locationSetting = LocationSetting::find($this->selectedId);
        $locationSetting->delete();
        $this->confirmingDeletion = false;
        $this->selectedId = null;
        $this->deleteName = null;
        $this->banner(__('Deleted successfully.'));
    }

    public function render()
    {
        $locationSettings = LocationSetting::all();
        return view('livewire.admin.location-setting-component', [
            'locationSettings' => $locationSettings
        ]);
    }
}
