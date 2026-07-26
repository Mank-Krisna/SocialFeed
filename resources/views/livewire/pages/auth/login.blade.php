<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('feed', absolute: false), navigate: true);
    }
}; ?>

<div class="space-y-6">
    <div class="text-center space-y-1">
        <h2 class="text-2xl font-bold text-[#1a1c1f]">Masuk ke Akun</h2>
        <p class="text-xs text-[#727785]">Masukkan email dan kata sandi Anda untuk melanjutkan.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login" class="space-y-4">
        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-bold text-[#1a1c1f] mb-1">Email</label>
            <input 
                wire:model="form.email" 
                id="email" 
                type="email" 
                name="email" 
                placeholder="nama@email.com" 
                required 
                autofocus 
                class="w-full px-4 py-2.5 bg-[#f3f3f7] focus:bg-white text-sm text-[#1a1c1f] rounded-xl border border-[#e2e2e6] focus:border-[#0058bc] focus:ring-1 focus:ring-[#0058bc] outline-none transition"
            />
            <x-input-error :messages="$errors->get('form.email')" class="mt-1 text-xs text-red-600" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-1">
                <label for="password" class="block text-xs font-bold text-[#1a1c1f]">Kata Sandi</label>
                @if (Route::has('password.request'))
                    <a class="text-xs text-[#0058bc] font-semibold hover:underline" href="{{ route('password.request') }}" wire:navigate>
                        Lupa sandi?
                    </a>
                @endif
            </div>

            <input 
                wire:model="form.password" 
                id="password" 
                type="password" 
                name="password" 
                placeholder="••••••••" 
                required 
                class="w-full px-4 py-2.5 bg-[#f3f3f7] focus:bg-white text-sm text-[#1a1c1f] rounded-xl border border-[#e2e2e6] focus:border-[#0058bc] focus:ring-1 focus:ring-[#0058bc] outline-none transition"
            />
            <x-input-error :messages="$errors->get('form.password')" class="mt-1 text-xs text-red-600" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <label for="remember" class="inline-flex items-center cursor-pointer">
                <input wire:model="form.remember" id="remember" type="checkbox" class="rounded border-[#e2e2e6] text-[#0058bc] focus:ring-[#0058bc]" name="remember">
                <span class="ms-2 text-xs text-[#727785]">Ingat saya di perangkat ini</span>
            </label>
        </div>

        <button 
            type="submit" 
            class="w-full py-3 bg-[#0058bc] hover:bg-[#004493] text-white text-sm font-bold rounded-xl transition shadow-sm active:scale-[0.99]"
        >
            Masuk
        </button>
    </form>

    <div class="text-center pt-4 border-t border-[#f3f3f7]">
        <p class="text-xs text-[#727785]">
            Belum punya akun? 
            <a href="{{ route('register') }}" wire:navigate class="font-bold text-[#0058bc] hover:underline">
                Daftar Akun Baru
            </a>
        </p>
    </div>
</div>
