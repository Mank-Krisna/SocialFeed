<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component
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
}; ?>

<section class="space-y-6">
    <header>
        <h2 class="text-lg font-bold text-[var(--text-primary)]">
            Informasi Profil
        </h2>
        <p class="mt-1 text-xs text-[var(--text-secondary)]">
            Perbarui data profil, foto profil, foto sampul, dan bio akun Anda.
        </p>
    </header>

    <form wire:submit.prevent="updateProfileInformation" class="space-y-4">
        <!-- Avatar Preview & Upload -->
        <div class="flex items-center gap-4">
            <img 
                src="{{ $avatar ? $avatar->temporaryUrl() : auth()->user()->avatar_url }}" 
                alt="Avatar" 
                class="w-16 h-16 rounded-full object-cover border-2 border-[var(--accent)]"
            />
            <div class="space-y-1">
                <x-input-label for="avatar" value="Foto Profil" />
                <input type="file" wire:model="avatar" id="avatar" accept="image/*" class="text-xs text-[var(--text-secondary)] file:mr-2 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-[var(--accent)]/10 file:text-[var(--accent)] hover:file:bg-[var(--accent)]/20" />
                <x-input-error class="mt-1" :messages="$errors->get('avatar')" />
            </div>
        </div>

        <!-- Cover Photo Upload -->
        <div>
            <x-input-label for="cover_photo" value="Foto Sampul (Cover)" />
            <input type="file" wire:model="cover_photo" id="cover_photo" accept="image/*" class="mt-1 text-xs text-[var(--text-secondary)] file:mr-2 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-[var(--accent)]/10 file:text-[var(--accent)] hover:file:bg-[var(--accent)]/20" />
            <x-input-error class="mt-1" :messages="$errors->get('cover_photo')" />
        </div>

        <!-- Name -->
        <div>
            <x-input-label for="name" value="Nama Lengkap" />
            <x-text-input wire:model="name" id="name" type="text" class="mt-1 block w-full text-sm" required autocomplete="name" />
            <x-input-error class="mt-1" :messages="$errors->get('name')" />
        </div>

        <!-- Username -->
        <div>
            <x-input-label for="username" value="Username (@handle)" />
            <x-text-input wire:model="username" id="username" type="text" class="mt-1 block w-full text-sm" placeholder="username_anda" />
            <x-input-error class="mt-1" :messages="$errors->get('username')" />
        </div>

        <!-- Bio -->
        <div>
            <x-input-label for="bio" value="Bio Singkat" />
            <textarea wire:model="bio" id="bio" rows="3" placeholder="Tuliskan sedikit tentang diri Anda..." class="mt-1 block w-full text-sm rounded-lg border-[var(--card-border)] focus:border-[var(--accent)] focus:ring-[var(--accent)]"></textarea>
            <x-input-error class="mt-1" :messages="$errors->get('bio')" />
        </div>

        <!-- Email -->
        <div>
            <x-input-label for="email" value="Alamat Email" />
            <x-text-input wire:model="email" id="email" type="email" class="mt-1 block w-full text-sm" required autocomplete="username" />
            <x-input-error class="mt-1" :messages="$errors->get('email')" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="px-5 py-2 bg-[var(--accent)] hover:bg-[var(--accent-hover)] text-white text-xs font-bold rounded-lg transition shadow-sm">
                Simpan Perubahan
            </button>

            <x-action-message class="me-3 text-xs text-emerald-600 font-semibold" on="profile-updated">
                Berhasil disimpan!
            </x-action-message>
        </div>
    </form>
</section>

