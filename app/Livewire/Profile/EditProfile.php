<?php

namespace App\Livewire\Profile;

use App\Livewire\Forms\UserForm;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\InteractsWithBanner;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditProfile extends Component
{
    use InteractsWithBanner, WithFileUploads;

    public UserForm $form;

    public function mount()
    {
        $this->form->setUser(Auth::user());
    }

    public function update()
    {
        // User can only update their own profile
        if ($this->form->user->id !== Auth::id()) {
            return abort(403);
        }

        $this->form->update();
        $this->banner(__('Profile updated successfully.'));
    }

    public function deleteProfilePhoto()
    {
        $this->form->deleteProfilePhoto();
    }

    public function render()
    {
        return view('livewire.profile.edit-profile');
    }
}
