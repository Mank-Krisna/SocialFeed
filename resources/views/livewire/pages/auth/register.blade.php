<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('feed', absolute: false), navigate: true);
    }
}; ?>

<div class="space-y-6">
    <div class="text-center space-y-1">
        <h2 class="text-2xl font-bold text-[#1a1c1f]">Buat Akun Baru</h2>
        <p class="text-xs text-[#727785]">Daftar gratis untuk mulai berbagi status dan terhubung.</p>
    </div>

    <form wire:submit="register" class="space-y-4">
        <!-- Name -->
        <div>
            <label for="name" class="block text-xs font-bold text-[#1a1c1f] mb-1">Nama Lengkap</label>
            <input 
                wire:model="name" 
                id="name" 
                type="text" 
                name="name" 
                placeholder="Nama Anda" 
                required 
                autofocus 
                class="w-full px-4 py-2.5 bg-[#f3f3f7] focus:bg-white text-sm text-[#1a1c1f] rounded-xl border border-[#e2e2e6] focus:border-[#0058bc] focus:ring-1 focus:ring-[#0058bc] outline-none transition"
            />
            <x-input-error :messages="$errors->get('name')" class="mt-1 text-xs text-red-600" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-bold text-[#1a1c1f] mb-1">Alamat Email</label>
            <input 
                wire:model="email" 
                id="email" 
                type="email" 
                name="email" 
                placeholder="nama@email.com" 
                required 
                class="w-full px-4 py-2.5 bg-[#f3f3f7] focus:bg-white text-sm text-[#1a1c1f] rounded-xl border border-[#e2e2e6] focus:border-[#0058bc] focus:ring-1 focus:ring-[#0058bc] outline-none transition"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-red-600" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-bold text-[#1a1c1f] mb-1">Kata Sandi</label>
            <input 
                wire:model="password" 
                id="password" 
                type="password" 
                name="password" 
                placeholder="Minimal 8 karakter" 
                required 
                class="w-full px-4 py-2.5 bg-[#f3f3f7] focus:bg-white text-sm text-[#1a1c1f] rounded-xl border border-[#e2e2e6] focus:border-[#0058bc] focus:ring-1 focus:ring-[#0058bc] outline-none transition"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-red-600" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-xs font-bold text-[#1a1c1f] mb-1">Konfirmasi Kata Sandi</label>
            <input 
                wire:model="password_confirmation" 
                id="password_confirmation" 
                type="password" 
                name="password_confirmation" 
                placeholder="Ulangi kata sandi" 
                required 
                class="w-full px-4 py-2.5 bg-[#f3f3f7] focus:bg-white text-sm text-[#1a1c1f] rounded-xl border border-[#e2e2e6] focus:border-[#0058bc] focus:ring-1 focus:ring-[#0058bc] outline-none transition"
            />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-xs text-red-600" />
        </div>

        <button 
            type="submit" 
            class="w-full py-3 bg-[#0058bc] hover:bg-[#004493] text-white text-sm font-bold rounded-xl transition shadow-sm active:scale-[0.99]"
        >
            Daftar Akun
        </button>
    </form>

    <div class="text-center pt-4 border-t border-[#f3f3f7]">
        <p class="text-xs text-[#727785]">
            Sudah punya akun? 
            <a href="{{ route('login') }}" wire:navigate class="font-bold text-[#0058bc] hover:underline">
                Masuk di Sini
            </a>
        </p>
    </div>
</div>
