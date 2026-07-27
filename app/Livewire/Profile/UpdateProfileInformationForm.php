<?php

namespace App\Livewire\Profile;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class UpdateProfileInformationForm extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $username = '';
    public string $email = '';
    public string $bio = '';
    public $avatar = null;
    public $cover_photo = null;

    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name ?? '';
        $this->username = $user->username ?? '';
        $this->email = $user->email ?? '';
        $this->bio = $user->bio ?? '';
    }

    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:50', 'alpha_dash', Rule::unique(User::class)->ignore($user->id)],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'bio' => ['nullable', 'string', 'max:300'],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'cover_photo' => ['nullable', 'image', 'max:3048'],
        ]);

        if ($this->avatar) {
            $validated['avatar'] = $this->avatar->store('avatars', 'public');
        } else {
            unset($validated['avatar']);
        }

        if ($this->cover_photo) {
            if ($user->cover_photo) {
                Storage::disk('public')->delete($user->cover_photo);
            }
            $validated['cover_photo'] = $this->cover_photo->store('covers', 'public');
        } else {
            unset($validated['cover_photo']);
        }

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    public function render()
    {
        return view('livewire.profile.update-profile-information-form');
    }
}
