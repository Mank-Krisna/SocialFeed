<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/', navigate: true);
    }
}; ?>

<div>
<nav x-data="{ open: false }" class="bg-[var(--nav-bg)] border-b border-[var(--card-border)] sticky top-0 z-40">
    <div class="max-w-[1280px] mx-auto px-4 sm:px-4 lg:px-8">
        <div class="flex justify-between items-center h-16 gap-4">
            
            <!-- Left: Logo & Search -->
            <div class="flex items-center gap-3 sm:gap-5 flex-1 max-w-lg min-w-0">
                <a href="{{ route('feed') }}" wire:navigate class="flex items-center gap-2.5 font-bold text-xl text-[#0058bc] shrink-0">
                    <img src="{{ asset('images/logo.png') }}" alt="SocialFeed Logo" width="36" height="36" class="w-9 h-9 rounded-xl object-cover shadow-sm border border-slate-100">
                    <span class="font-display tracking-tight hidden sm:inline-block">SocialFeed</span>
                </a>

                <!-- Search Input Bar (hide on mobile, floating replaces it) -->
                <div class="hidden sm:block w-full max-w-xs min-w-0">
                    <x-search class="w-full flex items-center gap-2" />
                </div>
            </div>

            <!-- Middle: Quick Links (Desktop) -->
            <div class="hidden md:flex items-center space-x-1 lg:space-x-2">
                <a href="{{ route('feed') }}" wire:navigate class="px-4 py-2 rounded-lg flex items-center gap-2 text-sm font-semibold {{ request()->routeIs('feed') ? 'text-[#0058bc] bg-[#0058bc]/10' : 'text-[#414754] dark:text-[#b0b4be] hover:bg-[#f3f3f7] dark:hover:bg-[#25282e]' }}">
                    <span class="material-symbols-outlined {{ request()->routeIs('feed') ? 'filled' : '' }}" aria-hidden="true">home</span>
                    <span>Feed</span>
                </a>
                <a href="{{ route('friends') }}" wire:navigate class="px-4 py-2 rounded-lg flex items-center gap-2 text-sm font-semibold {{ request()->routeIs('friends') ? 'text-[#0058bc] bg-[#0058bc]/10' : 'text-[#414754] dark:text-[#b0b4be] hover:bg-[#f3f3f7] dark:hover:bg-[#25282e]' }}">
                    <span class="material-symbols-outlined {{ request()->routeIs('friends') ? 'filled' : '' }}" aria-hidden="true">group</span>
                    <span>Teman</span>
                </a>
                <a href="{{ route('groups') }}" wire:navigate class="px-4 py-2 rounded-lg flex items-center gap-2 text-sm font-semibold {{ request()->routeIs('groups*') ? 'text-[#0058bc] bg-[#0058bc]/10' : 'text-[#414754] dark:text-[#b0b4be] hover:bg-[#f3f3f7] dark:hover:bg-[#25282e]' }}">
                    <span class="material-symbols-outlined {{ request()->routeIs('groups*') ? 'filled' : '' }}" aria-hidden="true">groups</span>
                    <span>Grup</span>
                </a>
                <a href="{{ route('messages') }}" wire:navigate class="px-4 py-2 rounded-lg flex items-center gap-2 text-sm font-semibold {{ request()->routeIs('messages') ? 'text-[#0058bc] bg-[#0058bc]/10' : 'text-[#414754] dark:text-[#b0b4be] hover:bg-[#f3f3f7] dark:hover:bg-[#25282e]' }}">
                    <span class="material-symbols-outlined {{ request()->routeIs('messages') ? 'filled' : '' }}" aria-hidden="true">chat</span>
                    <span>Pesan</span>
                </a>
                <a href="{{ route('notifications') }}" wire:navigate class="px-4 py-2 rounded-lg flex items-center gap-2 text-sm font-semibold relative {{ request()->routeIs('notifications') ? 'text-[#0058bc] bg-[#0058bc]/10' : 'text-[#414754] dark:text-[#b0b4be] hover:bg-[#f3f3f7] dark:hover:bg-[#25282e]' }}">
                    <span class="material-symbols-outlined {{ request()->routeIs('notifications') ? 'filled' : '' }}" aria-hidden="true">notifications</span>
                    <span>Notifikasi</span>
                    @if (auth()->user()->unreadNotificationsCount() > 0)
                        <span class="w-2 h-2 rounded-full bg-red-500 absolute top-2 right-2"></span>
                    @endif
                </a>
            </div>

            <!-- Right: User Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2 px-3 py-1.5 border border-[#e2e2e6] dark:border-[#2d2f34] text-sm font-medium rounded-full text-[#1a1c1f] dark:text-[#e2e2e6] bg-white dark:bg-[#1a1c1f] hover:bg-[#f3f3f7] dark:hover:bg-[#25282e] focus:outline-none transition">
                            <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" width="32" height="32" class="w-8 h-8 rounded-full object-cover" />
                            <span class="font-semibold text-xs text-[#1a1c1f] dark:text-[#e2e2e6]" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></span>
                            <span class="material-symbols-outlined text-lg text-[#727785] dark:text-[#9ca3af]" aria-hidden="true">expand_more</span>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2 border-b border-[#e2e2e6] dark:border-[#2d2f34]">
                            <div class="font-bold text-sm text-[#1a1c1f] dark:text-[#e2e2e6]">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-[#727785] dark:text-[#9ca3af] truncate">{{ auth()->user()->email }}</div>
                        </div>
                        <x-dropdown-link :href="route('profile.show', auth()->user()->username ?? auth()->id())" wire:navigate class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-lg" aria-hidden="true">account_circle</span>
                            {{ __('Lihat Profil Saya') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('profile.edit')" wire:navigate class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-lg" aria-hidden="true">settings</span>
                            {{ __('Edit Pengaturan') }}
                        </x-dropdown-link>

                        <div class="border-t border-[#e2e2e6] dark:border-[#2d2f34] mt-1 pt-1">
                            <button @click="dark = !dark" class="w-full text-start">
                                <x-dropdown-link class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-lg" x-show="!dark" aria-hidden="true">dark_mode</span>
                                    <span class="material-symbols-outlined text-lg" x-show="dark" x-cloak aria-hidden="true">light_mode</span>
                                    <span x-text="dark ? 'Mode Terang' : 'Mode Gelap'"></span>
                                </x-dropdown-link>
                            </button>
                        </div>
                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link class="flex items-center gap-2 text-red-600 hover:text-red-700 hover:bg-red-50">
                                <span class="material-symbols-outlined text-lg" aria-hidden="true">logout</span>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger / Options (Mobile) -->
            <div class="-me-1 flex items-center gap-1 sm:hidden">
                <a href="{{ route('profile.edit') }}" wire:navigate class="p-2 rounded-lg text-[#727785] dark:text-[#9ca3af] hover:text-[#1a1c1f] dark:hover:text-[#e2e2e6] hover:bg-[#f3f3f7] dark:hover:bg-[#25282e] transition" title="Pengaturan">
                    <span class="material-symbols-outlined text-xl">settings</span>
                </a>
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-lg text-[#727785] dark:text-[#9ca3af] hover:text-[#1a1c1f] dark:hover:text-[#e2e2e6] hover:bg-[#f3f3f7] dark:hover:bg-[#25282e] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#0058bc] focus-visible:ring-offset-2 transition">
                    <span class="material-symbols-outlined" x-show="!open" aria-hidden="true">menu</span>
                    <span class="material-symbols-outlined" x-show="open" x-cloak aria-hidden="true">close</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Top Drawer Menu (Mobile) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-[#e2e2e6] dark:border-[#2d2f34] bg-white dark:bg-[var(--drawer-bg)] shadow-lg">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <x-responsive-nav-link :href="route('feed')" :active="request()->routeIs('feed')" wire:navigate>
                <span class="material-symbols-outlined text-lg" aria-hidden="true">home</span>
                <span>Feed Utama</span>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('friends')" :active="request()->routeIs('friends')" wire:navigate>
                <span class="material-symbols-outlined text-lg" aria-hidden="true">group</span>
                <span>Daftar Teman</span>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('groups')" :active="request()->routeIs('groups*')" wire:navigate>
                <span class="material-symbols-outlined text-lg" aria-hidden="true">groups</span>
                <span>Grup Komunitas</span>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('search')" :active="request()->routeIs('search')" wire:navigate>
                <span class="material-symbols-outlined text-lg" aria-hidden="true">search</span>
                <span>Pencarian</span>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('notifications')" :active="request()->routeIs('notifications')" wire:navigate>
                <span class="material-symbols-outlined text-lg" aria-hidden="true">notifications</span>
                <span>Notifikasi</span>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('messages')" :active="request()->routeIs('messages')" wire:navigate>
                <span class="material-symbols-outlined text-lg" aria-hidden="true">chat</span>
                <span>Pesan</span>
            </x-responsive-nav-link>
            @if (auth()->user()->isAdmin())
                <x-responsive-nav-link :href="route('admin')" :active="request()->routeIs('admin')" wire:navigate>
                    <span class="material-symbols-outlined text-lg" aria-hidden="true">admin_panel_settings</span>
                    <span>Admin</span>
                </x-responsive-nav-link>
            @endif
            <x-responsive-nav-link :href="route('profile.show', auth()->user()->username ?? auth()->id())" :active="request()->routeIs('profile.show')" wire:navigate>
                <span class="material-symbols-outlined text-lg" aria-hidden="true">account_circle</span>
                <span>Profil Saya</span>
            </x-responsive-nav-link>
        </div>
        <div class="pt-3 pb-4 border-t border-[#e2e2e6] dark:border-[#2d2f34] px-4 space-y-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3 min-w-0">
                    <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" width="40" height="40" class="w-10 h-10 rounded-full object-cover shrink-0 border border-[#e2e2e6]" />
                    <div class="min-w-0">
                    <div class="font-bold text-sm text-[#1a1c1f] dark:text-[#e2e2e6] truncate">{{ auth()->user()->name }}</div>
                    <div class="font-medium text-xs text-[#727785] dark:text-[#9ca3af] truncate">{{ auth()->user()->email }}</div>
                    </div>
                </div>
                <button wire:click="logout" class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-bold rounded-lg transition shrink-0">
                    Log Out
                </button>
            </div>
            <button @click="dark = !dark" class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-semibold text-[#414754] hover:bg-[#f3f3f7] transition">
                <span class="material-symbols-outlined text-lg" x-show="!dark" aria-hidden="true">dark_mode</span>
                <span class="material-symbols-outlined text-lg" x-show="dark" x-cloak aria-hidden="true">light_mode</span>
                <span x-text="dark ? 'Mode Terang' : 'Mode Gelap'"></span>
            </button>
        </div>
    </div>
</nav>

<!-- Mobile Bottom Navigation Bar (Thumb Zone - Only visible on mobile < 640px) -->
<nav class="sm:hidden fixed bottom-0 left-0 right-0 z-50 bg-white/95 dark:bg-[var(--mobile-bar)] backdrop-blur-md border-t border-[#e2e2e6] dark:border-[#2d2f34] px-1 py-1 shadow-lg flex items-center justify-around">
    <a href="{{ route('feed') }}" wire:navigate class="flex flex-col items-center gap-0.5 py-1 px-2.5 rounded-xl transition {{ request()->routeIs('feed') ? 'text-[#0058bc]' : 'text-[#727785] dark:text-[#9ca3af] hover:text-[#1a1c1f] dark:hover:text-[#e2e2e6]' }}">
        <span class="material-symbols-outlined text-xl sm:text-2xl {{ request()->routeIs('feed') ? 'filled' : '' }}" aria-hidden="true">home</span>
        <span class="text-[10px] font-bold">Feed</span>
    </a>

    <a href="{{ route('friends') }}" wire:navigate class="flex flex-col items-center gap-0.5 py-1 px-2.5 rounded-xl transition {{ request()->routeIs('friends') ? 'text-[#0058bc]' : 'text-[#727785] dark:text-[#9ca3af] hover:text-[#1a1c1f] dark:hover:text-[#e2e2e6]' }}">
        <span class="material-symbols-outlined text-xl sm:text-2xl {{ request()->routeIs('friends') ? 'filled' : '' }}" aria-hidden="true">group</span>
        <span class="text-[10px] font-bold">Teman</span>
    </a>

    <a href="{{ route('groups') }}" wire:navigate class="flex flex-col items-center gap-0.5 py-1 px-2.5 rounded-xl transition {{ request()->routeIs('groups*') ? 'text-[#0058bc]' : 'text-[#727785] dark:text-[#9ca3af] hover:text-[#1a1c1f] dark:hover:text-[#e2e2e6]' }}">
        <span class="material-symbols-outlined text-xl sm:text-2xl {{ request()->routeIs('groups*') ? 'filled' : '' }}" aria-hidden="true">groups</span>
        <span class="text-[10px] font-bold">Grup</span>
    </a>

    <a href="{{ route('messages') }}" wire:navigate class="flex flex-col items-center gap-0.5 py-1 px-2.5 rounded-xl transition {{ request()->routeIs('messages') ? 'text-[#0058bc]' : 'text-[#727785] dark:text-[#9ca3af] hover:text-[#1a1c1f] dark:hover:text-[#e2e2e6]' }}">
        <span class="material-symbols-outlined text-xl sm:text-2xl {{ request()->routeIs('messages') ? 'filled' : '' }}" aria-hidden="true">chat</span>
        <span class="text-[10px] font-bold">Pesan</span>
    </a>

    <a href="{{ route('notifications') }}" wire:navigate class="flex flex-col items-center gap-0.5 py-1 px-2.5 rounded-xl transition relative {{ request()->routeIs('notifications') ? 'text-[#0058bc]' : 'text-[#727785] dark:text-[#9ca3af] hover:text-[#1a1c1f] dark:hover:text-[#e2e2e6]' }}">
        <span class="material-symbols-outlined text-xl sm:text-2xl {{ request()->routeIs('notifications') ? 'filled' : '' }}" aria-hidden="true">notifications</span>
        <span class="text-[10px] font-bold">Notif</span>
        @if (auth()->user()->unreadNotificationsCount() > 0)
            <span class="w-2.5 h-2.5 rounded-full bg-red-500 border-2 border-white dark:border-[#1a1c1f] absolute top-1 right-2.5"></span>
        @endif
    </a>

    <a href="{{ route('profile.show', auth()->user()->username ?? auth()->id()) }}" wire:navigate class="flex flex-col items-center gap-0.5 py-1 px-2.5 rounded-xl transition {{ request()->routeIs('profile.show') ? 'text-[#0058bc]' : 'text-[#727785] dark:text-[#9ca3af] hover:text-[#1a1c1f] dark:hover:text-[#e2e2e6]' }}">
        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" width="24" height="24" class="w-6 h-6 rounded-full object-cover border {{ request()->routeIs('profile.show') ? 'border-[#0058bc] ring-2 ring-[#0058bc]/20' : 'border-[#e2e2e6] dark:border-[#2d2f34]' }}" />
        <span class="text-[10px] font-bold">Profil</span>
    </a>
</nav>
</div>
