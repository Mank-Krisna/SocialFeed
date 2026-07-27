<div>
<nav x-data="{ open: false }" class="bg-[var(--nav-bg)] border-b border-[var(--card-border)] sticky top-0 z-40">
    <div class="max-w-[1280px] mx-auto px-4 sm:px-4 lg:px-8">
        <div class="flex justify-between items-center h-14 sm:h-16 gap-3 sm:gap-4">

            <!-- Left: Logo -->
            <a href="{{ route('feed') }}" wire:navigate class="flex items-center gap-2.5 font-bold text-xl text-[var(--accent)] shrink-0">
                <img src="{{ asset('images/logo.png') }}" alt="SocialFeed Logo" width="36" height="36" class="w-9 h-9 rounded-xl object-cover shadow-sm border border-slate-100">
                <span class="font-display tracking-tight hidden sm:inline-block">SocialFeed</span>
            </a>

            <!-- Center: Search (desktop) -->
            <div class="hidden sm:flex flex-1 justify-center max-w-md">
                <x-search class="w-full flex items-center gap-2" />
            </div>

            <!-- Right: Nav Links + User -->
            <div class="flex items-center gap-1 sm:gap-2">
                @auth
                {{-- Desktop: icon-only links --}}
                <a href="{{ route('feed') }}" wire:navigate class="hidden md:flex items-center justify-center w-10 h-10 rounded-xl transition {{ request()->routeIs('feed') ? 'text-[var(--accent)] bg-[var(--accent-soft)]' : 'text-[var(--text-variant)] hover:bg-[var(--surface-hover)]' }}" title="Feed">
                    <span class="material-symbols-outlined {{ request()->routeIs('feed') ? 'filled' : '' }}" aria-hidden="true">home</span>
                </a>
                <a href="{{ route('messages') }}" wire:navigate class="hidden md:flex items-center justify-center w-10 h-10 rounded-xl transition relative {{ request()->routeIs('messages*') ? 'text-[var(--accent)] bg-[var(--accent-soft)]' : 'text-[var(--text-variant)] hover:bg-[var(--surface-hover)]' }}" title="Pesan">
                    <span class="material-symbols-outlined {{ request()->routeIs('messages*') ? 'filled' : '' }}" aria-hidden="true">chat</span>
                    @if (auth()->user()->unreadMessagesCount() > 0)
                        <span class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] flex items-center justify-center px-1 text-[10px] font-bold text-white bg-red-500 rounded-full">{{ auth()->user()->unreadMessagesCount() > 99 ? '99+' : auth()->user()->unreadMessagesCount() }}</span>
                    @endif
                </a>
                <livewire:notifications-dropdown />

                {{-- Mobile: settings icon --}}
                <a href="{{ route('profile.edit') }}" wire:navigate class="md:hidden p-2 rounded-lg text-[var(--text-secondary)] hover:text-[var(--text-primary)] hover:bg-[var(--surface-hover)] transition" title="Pengaturan">
                    <span class="material-symbols-outlined text-xl" aria-hidden="true">settings</span>
                </a>

                {{-- User Dropdown --}}
                <div class="hidden sm:flex sm:items-center">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center gap-2 px-2.5 py-1.5 border border-[var(--card-border)] text-sm font-medium rounded-full text-[var(--text-primary)] bg-[var(--card-bg)] hover:bg-[var(--surface-hover)] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[var(--accent)] focus-visible:ring-offset-2 transition btn-press">
                                <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" width="32" height="32" class="w-8 h-8 rounded-full object-cover avatar-hover" />
                                <span class="material-symbols-outlined text-lg text-[var(--text-secondary)]" aria-hidden="true">expand_more</span>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="px-4 py-2 border-b border-[var(--card-border)]">
                                <div class="font-bold text-sm text-[var(--text-primary)]">{{ auth()->user()->name }}</div>
                                <div class="text-xs text-[var(--text-secondary)] truncate">{{ auth()->user()->email }}</div>
                            </div>
                            <x-dropdown-link :href="route('profile.show', auth()->user()->username ?? auth()->id())" wire:navigate class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-lg" aria-hidden="true">account_circle</span>
                                {{ __('Lihat Profil Saya') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('friends')" wire:navigate class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-lg" aria-hidden="true">group</span>
                                {{ __('Teman & Koneksi') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('groups')" wire:navigate class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-lg" aria-hidden="true">groups</span>
                                {{ __('Grup Diskusi') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('hashtags.index')" wire:navigate class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-lg" aria-hidden="true">explore</span>
                                {{ __('Jelajahi') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('bookmarks')" wire:navigate class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-lg" aria-hidden="true">bookmark</span>
                                {{ __('Bookmark') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('settings')" wire:navigate class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-lg" aria-hidden="true">tune</span>
                                {{ __('Pengaturan') }}
                            </x-dropdown-link>
                            @if (auth()->user()->isAdmin())
                                <x-dropdown-link :href="route('admin')" wire:navigate class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-lg" aria-hidden="true">admin_panel_settings</span>
                                    {{ __('Admin') }}
                                </x-dropdown-link>
                            @endif

                            <div class="border-t border-[var(--card-border)] mt-1 pt-1">
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
                @endauth

                {{-- Mobile: hamburger --}}
                @auth
                <button @click="open = ! open" class="md:hidden inline-flex items-center justify-center p-2 rounded-lg text-[var(--text-secondary)] hover:text-[var(--text-primary)] hover:bg-[var(--surface-hover)] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[var(--accent)] focus-visible:ring-offset-2 transition">
                    <span class="material-symbols-outlined" x-show="!open" aria-hidden="true">menu</span>
                    <span class="material-symbols-outlined" x-show="open" x-cloak aria-hidden="true">close</span>
                </button>
                @endauth
            </div>
        </div>
    </div>

    <!-- Responsive Top Drawer Menu (Mobile) -->
    @auth
    <div :class="{'block': open, 'hidden': ! open}" class="hidden md:hidden border-t border-[var(--card-border)] bg-[var(--drawer-bg)] shadow-lg">
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
            <x-responsive-nav-link :href="route('hashtags.index')" :active="request()->routeIs('hashtags.*')" wire:navigate>
                <span class="material-symbols-outlined text-lg" aria-hidden="true">explore</span>
                <span>Jelajahi</span>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('bookmarks')" :active="request()->routeIs('bookmarks')" wire:navigate>
                <span class="material-symbols-outlined text-lg" aria-hidden="true">bookmark</span>
                <span>Bookmark</span>
            </x-responsive-nav-link>
            @if (auth()->user()->isAdmin())
                <x-responsive-nav-link :href="route('admin')" :active="request()->routeIs('admin*')" wire:navigate>
                    <span class="material-symbols-outlined text-lg" aria-hidden="true">admin_panel_settings</span>
                    <span>Admin</span>
                </x-responsive-nav-link>
            @endif
            <x-responsive-nav-link :href="route('profile.show', auth()->user()->username ?? auth()->id())" :active="request()->routeIs('profile.show')" wire:navigate>
                <span class="material-symbols-outlined text-lg" aria-hidden="true">account_circle</span>
                <span>Profil Saya</span>
            </x-responsive-nav-link>
        </div>
        <div class="pt-3 pb-4 border-t border-[var(--card-border)] px-4 space-y-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3 min-w-0">
                    <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" width="40" height="40" class="w-10 h-10 rounded-full object-cover shrink-0 border border-[var(--card-border)]" />
                    <div class="min-w-0">
                        <div class="font-bold text-sm text-[var(--text-primary)] truncate">{{ auth()->user()->name }}</div>
                        <div class="font-medium text-xs text-[var(--text-secondary)] truncate">{{ auth()->user()->email }}</div>
                    </div>
                </div>
                <button wire:click="logout" class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-bold rounded-lg transition shrink-0">
                    Log Out
                </button>
            </div>
            <button @click="dark = !dark" class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-semibold text-[var(--text-variant)] hover:bg-[var(--surface-hover)] transition">
                <span class="material-symbols-outlined text-lg" x-show="!dark" aria-hidden="true">dark_mode</span>
                <span class="material-symbols-outlined text-lg" x-show="dark" x-cloak aria-hidden="true">light_mode</span>
                <span x-text="dark ? 'Mode Terang' : 'Mode Gelap'"></span>
            </button>
        </div>
    </div>
</nav>

<!-- Mobile Bottom Navigation Bar (Thumb Zone) -->
<nav class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-[var(--nav-bg)]/95 backdrop-blur-md border-t border-[var(--card-border)] px-1 py-1 shadow-lg flex items-center justify-around">
    <a href="{{ route('feed') }}" wire:navigate class="flex flex-col items-center gap-0.5 py-1 px-3 rounded-xl transition {{ request()->routeIs('feed') ? 'text-[var(--accent)]' : 'text-[var(--text-secondary)] hover:text-[var(--text-primary)]' }}">
        <span class="material-symbols-outlined text-xl {{ request()->routeIs('feed') ? 'filled' : '' }}" aria-hidden="true">home</span>
        <span class="text-[10px] font-bold">Feed</span>
    </a>

    <a href="{{ route('friends') }}" wire:navigate class="flex flex-col items-center gap-0.5 py-1 px-3 rounded-xl transition {{ request()->routeIs('friends') ? 'text-[var(--accent)]' : 'text-[var(--text-secondary)] hover:text-[var(--text-primary)]' }}">
        <span class="material-symbols-outlined text-xl {{ request()->routeIs('friends') ? 'filled' : '' }}" aria-hidden="true">group</span>
        <span class="text-[10px] font-bold">Teman</span>
    </a>

    <a href="{{ route('groups') }}" wire:navigate class="flex flex-col items-center gap-0.5 py-1 px-3 rounded-xl transition {{ request()->routeIs('groups*') ? 'text-[var(--accent)]' : 'text-[var(--text-secondary)] hover:text-[var(--text-primary)]' }}">
        <span class="material-symbols-outlined text-xl {{ request()->routeIs('groups*') ? 'filled' : '' }}" aria-hidden="true">groups</span>
        <span class="text-[10px] font-bold">Grup</span>
    </a>

    <a href="{{ route('messages') }}" wire:navigate class="flex flex-col items-center gap-0.5 py-1 px-3 rounded-xl transition relative {{ request()->routeIs('messages*') ? 'text-[var(--accent)]' : 'text-[var(--text-secondary)] hover:text-[var(--text-primary)]' }}">
        <span class="material-symbols-outlined text-xl {{ request()->routeIs('messages*') ? 'filled' : '' }}" aria-hidden="true">chat</span>
        <span class="text-[10px] font-bold">Pesan</span>
        @if (auth()->user()->unreadMessagesCount() > 0)
            <span class="absolute -top-0.5 right-1 min-w-[16px] h-[16px] flex items-center justify-center px-0.5 text-[9px] font-bold text-white bg-red-500 rounded-full">{{ auth()->user()->unreadMessagesCount() > 99 ? '99+' : auth()->user()->unreadMessagesCount() }}</span>
        @endif
    </a>

    <a href="{{ route('notifications') }}" wire:navigate class="flex flex-col items-center gap-0.5 py-1 px-3 rounded-xl transition relative {{ request()->routeIs('notifications*') ? 'text-[var(--accent)]' : 'text-[var(--text-secondary)] hover:text-[var(--text-primary)]' }}">
        <span class="material-symbols-outlined text-xl {{ request()->routeIs('notifications*') ? 'filled' : '' }}" aria-hidden="true">notifications</span>
        <span class="text-[10px] font-bold">Notif</span>
        @if (auth()->user()->unreadNotificationsCount() > 0)
            <span class="absolute -top-0.5 right-1 min-w-[16px] h-[16px] flex items-center justify-center px-0.5 text-[9px] font-bold text-white bg-red-500 rounded-full">{{ auth()->user()->unreadNotificationsCount() > 99 ? '99+' : auth()->user()->unreadNotificationsCount() }}</span>
        @endif
    </a>

    <a href="{{ route('profile.show', auth()->user()->username ?? auth()->id()) }}" wire:navigate class="flex flex-col items-center gap-0.5 py-1 px-3 rounded-xl transition {{ request()->routeIs('profile.show') ? 'text-[var(--accent)]' : 'text-[var(--text-secondary)] hover:text-[var(--text-primary)]' }}">
        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" width="24" height="24" class="w-6 h-6 rounded-full object-cover border {{ request()->routeIs('profile.show') ? 'border-[var(--accent)] ring-2 ring-[var(--accent)]/20' : 'border-[var(--card-border)]' }}" />
        <span class="text-[10px] font-bold">Profil</span>
    </a>
</nav>
@endauth
</div>
