<div class="space-y-3">
    <!-- User Quick Card -->
        <a href="{{ route('profile.show', auth()->user()->username ?? auth()->id()) }}" wire:navigate class="card-elevation p-2.5 flex items-center gap-2 hover:bg-[var(--bg-page)] dark:hover:bg-[#1c1e24] transition block">
        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" width="36" height="36" class="w-9 h-9 rounded-full object-cover shrink-0 border border-[var(--card-border)]" />
        <div class="min-w-0 flex-1">
            <h3 class="font-semibold text-sm text-[var(--text-primary)] truncate">{{ auth()->user()->name }}</h3>
            <p class="text-[11px] text-[var(--text-secondary)] truncate">{{ '@' . (auth()->user()->username ?? Str::slug(auth()->user()->name)) }}</p>
        </div>
    </a>

    <!-- Navigation List -->
    <div class="card-elevation p-1.5 space-y-0.5">
        <p class="px-2.5 pt-1 pb-1 text-[10px] font-semibold text-[var(--text-secondary)] uppercase tracking-wider">Menu</p>

        <a href="{{ route('feed') }}" wire:navigate class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-lg text-sm font-semibold transition {{ request()->routeIs('feed') ? 'bg-[var(--accent)]/10 text-[var(--accent)]' : 'text-[var(--text-variant)] hover:bg-[var(--surface-hover)]' }}">
            <span aria-hidden="true" class="material-symbols-outlined text-[20px] {{ request()->routeIs('feed') ? 'filled' : '' }}">dynamic_feed</span>
            <span>Feed Utama</span>
        </a>

        <a href="{{ route('friends') }}" wire:navigate class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-lg text-sm font-semibold transition {{ request()->routeIs('friends') ? 'bg-[var(--accent)]/10 text-[var(--accent)]' : 'text-[var(--text-variant)] hover:bg-[var(--surface-hover)]' }}">
            <span aria-hidden="true" class="material-symbols-outlined text-[20px] {{ request()->routeIs('friends') ? 'filled' : '' }}">group</span>
            <span>Teman &amp; Koneksi</span>
        </a>

        <a href="{{ route('groups') }}" wire:navigate class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-lg text-sm font-semibold transition {{ request()->routeIs('groups*') ? 'bg-[var(--accent)]/10 text-[var(--accent)]' : 'text-[var(--text-variant)] hover:bg-[var(--surface-hover)]' }}">
            <span aria-hidden="true" class="material-symbols-outlined text-[20px] {{ request()->routeIs('groups*') ? 'filled' : '' }}">groups</span>
            <span>Grup Diskusi</span>
        </a>

        <a href="{{ route('search') }}" wire:navigate class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-lg text-sm font-semibold transition {{ request()->routeIs('search') ? 'bg-[var(--accent)]/10 text-[var(--accent)]' : 'text-[var(--text-variant)] hover:bg-[var(--surface-hover)]' }}">
            <span aria-hidden="true" class="material-symbols-outlined text-[20px] {{ request()->routeIs('search') ? 'filled' : '' }}">search</span>
            <span>Pencarian</span>
        </a>

        <a href="{{ route('messages') }}" wire:navigate class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-lg text-sm font-semibold transition {{ request()->routeIs('messages') ? 'bg-[var(--accent)]/10 text-[var(--accent)]' : 'text-[var(--text-variant)] hover:bg-[var(--surface-hover)]' }}">
            <span aria-hidden="true" class="material-symbols-outlined text-[20px] {{ request()->routeIs('messages') ? 'filled' : '' }}">chat</span>
            <span>Pesan</span>
            @if (auth()->check() && auth()->user()->unreadMessagesCount() > 0)
                <span class="ms-auto px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-[var(--accent)] text-white">
                    {{ auth()->user()->unreadMessagesCount() > 9 ? '9+' : auth()->user()->unreadMessagesCount() }}
                </span>
            @endif
        </a>

        <a href="{{ route('notifications') }}" wire:navigate class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-lg text-sm font-semibold transition {{ request()->routeIs('notifications') ? 'bg-[var(--accent)]/10 text-[var(--accent)]' : 'text-[var(--text-variant)] hover:bg-[var(--surface-hover)]' }}">
            <span aria-hidden="true" class="material-symbols-outlined text-[20px] {{ request()->routeIs('notifications') ? 'filled' : '' }}">notifications</span>
            <span>Notifikasi</span>
            @if (auth()->user()->unreadNotificationsCount() > 0)
                <span class="ms-auto px-2 py-0.5 rounded-full text-[10px] font-bold bg-[var(--accent)] text-white">
                    {{ auth()->user()->unreadNotificationsCount() }}
                </span>
            @endif
        </a>
    </div>

    <!-- Mini Footer -->
    @auth
        @if (auth()->user()->isAdmin())
            <a href="{{ route('admin') }}" wire:navigate class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-lg text-sm font-semibold transition {{ request()->routeIs('admin') ? 'bg-[var(--accent)]/10 text-[var(--accent)]' : 'text-[var(--text-variant)] hover:bg-[var(--surface-hover)]' }}">
                <span aria-hidden="true" class="material-symbols-outlined text-[20px] {{ request()->routeIs('admin') ? 'filled' : '' }}">admin_panel_settings</span>
                <span>Admin</span>
            </a>
        @endif
    @endauth
    <div class="px-3 text-xs text-[var(--text-secondary)] space-y-1">
        <p>&copy; {{ date('Y') }} SocialFeed v3.0</p>
        <p>Built with Laravel 11 &amp; Livewire 3</p>
    </div>
</div>
