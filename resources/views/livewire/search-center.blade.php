<div class="space-y-6">
    <!-- Header Search Bar Input Card -->
    <div class="card-elevation p-4 space-y-4">
        <div>
            <h2 class="text-xl font-bold text-[var(--text-primary)]">Pencarian SocialFeed</h2>
            <p class="text-xs text-[var(--text-secondary)]">Cari teman, pengguna lain, atau grup komunitas.</p>
        </div>

        <div class="relative">
            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-[var(--text-secondary)] text-xl">search</span>
            <input 
                type="text" 
                wire:model.live.debounce.300ms="query" 
                placeholder="Ketik nama orang, username, atau nama grup..." 
                class="w-full pl-11 pr-4 py-3 bg-[var(--surface-hover)] focus:bg-white text-sm text-[var(--text-primary)] rounded-xl border border-[var(--card-border)] focus:border-[var(--accent)] focus:ring-1 focus:ring-[var(--accent)] outline-none transition"
                autofocus
            />
        </div>

        <!-- Filter Tabs -->
        <div class="flex items-center gap-2 border-b border-[var(--card-border)] pt-2">
            <button wire:click="setTab('all')" class="px-4 py-2 border-b-2 font-bold text-xs transition {{ $tab === 'all' ? 'border-[var(--accent)] text-[var(--accent)]' : 'border-transparent text-[var(--text-secondary)] hover:text-[var(--text-primary)]' }}">
                Semua Hasil
            </button>
            <button wire:click="setTab('users')" class="px-4 py-2 border-b-2 font-bold text-xs transition {{ $tab === 'users' ? 'border-[var(--accent)] text-[var(--accent)]' : 'border-transparent text-[var(--text-secondary)] hover:text-[var(--text-primary)]' }}">
                Pengguna ({{ $users->count() }})
            </button>
            <button wire:click="setTab('groups')" class="px-4 py-2 border-b-2 font-bold text-xs transition {{ $tab === 'groups' ? 'border-[var(--accent)] text-[var(--accent)]' : 'border-transparent text-[var(--text-secondary)] hover:text-[var(--text-primary)]' }}">
                Grup ({{ $groups->count() }})
            </button>
            @if ($hashtagName)
                <button wire:click="setTab('posts')" class="px-4 py-2 border-b-2 font-bold text-xs transition {{ $tab === 'posts' ? 'border-[var(--accent)] text-[var(--accent)]' : 'border-transparent text-[var(--text-secondary)] hover:text-[var(--text-primary)]' }}">
                    Postingan ({{ $hashtagPosts->count() }})
                </button>
            @endif
        </div>
    </div>

    <!-- Search Results Content -->
    @if (strlen(trim($query)) === 0)
        <div class="card-elevation p-12 text-center space-y-2">
            <span class="material-symbols-outlined text-5xl text-[var(--text-secondary)]">search</span>
            <h3 class="font-bold text-base text-[var(--text-primary)]">Mulai Pencarian</h3>
            <p class="text-xs text-[var(--text-secondary)]">Ketikkan kata kunci di kolom pencarian di atas.</p>
        </div>
    @elseif ($users->count() === 0 && $groups->count() === 0 && $hashtagPosts->count() === 0)
        <div class="card-elevation p-12 text-center space-y-2">
            <span class="material-symbols-outlined text-5xl text-[var(--text-secondary)]">search_off</span>
            <h3 class="font-bold text-base text-[var(--text-primary)]">Tidak Ada Hasil</h3>
            <p class="text-xs text-[var(--text-secondary)]">Tidak ditemukan hasil untuk "{{ $query }}".</p>
        </div>
    @else
        <!-- Users Results -->
        @if (($tab === 'all' || $tab === 'users') && $users->count() > 0)
            <div class="card-elevation p-5 space-y-3">
                <h3 class="font-bold text-sm text-[var(--text-primary)] flex items-center gap-2">
                    <span class="material-symbols-outlined text-[var(--accent)]">person</span>
                    <span>Pengguna</span>
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach ($users as $u)
                        <div class="p-3 border border-[var(--card-border)] rounded-xl flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <img src="{{ $u->avatar_url }}" class="w-10 h-10 rounded-full object-cover shrink-0" />
                                <div class="min-w-0">
                                    <a href="{{ route('profile.show', $u->username ?? $u->id) }}" wire:navigate class="font-bold text-xs text-[var(--text-primary)] truncate block hover:underline">
                                        {{ $u->name }}
                                    </a>
                                    <p class="text-[11px] text-[var(--text-secondary)] truncate">{{ '@' . e($u->username_display) }}</p>
                                </div>
                            </div>
                            <a href="{{ route('profile.show', $u->username ?? $u->id) }}" wire:navigate class="px-3 py-1 bg-[var(--surface-hover)] hover:bg-[var(--accent)] hover:text-white text-[var(--accent)] text-xs font-bold rounded-lg transition shrink-0">
                                Profil
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Groups Results -->
        @if (($tab === 'all' || $tab === 'groups') && $groups->count() > 0)
            <div class="card-elevation p-5 space-y-3">
                <h3 class="font-bold text-sm text-[var(--text-primary)] flex items-center gap-2">
                    <span class="material-symbols-outlined text-emerald-600">groups</span>
                    <span>Grup Komunitas</span>
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach ($groups as $g)
                        <div class="p-3 border border-[var(--card-border)] rounded-xl flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <img src="{{ $g->photo_url }}" class="w-10 h-10 rounded-xl object-cover shrink-0" />
                                <div class="min-w-0">
                                    <a href="{{ route('groups.show', $g->slug) }}" wire:navigate class="font-bold text-xs text-[var(--text-primary)] truncate block hover:underline">
                                        {{ $g->name }}
                                    </a>
                                    <p class="text-[11px] text-[var(--text-secondary)] truncate">{{ $g->members_count }} Anggota · {{ ucfirst($g->type) }}</p>
                                </div>
                            </div>
                            <a href="{{ route('groups.show', $g->slug) }}" wire:navigate class="px-3 py-1 bg-[var(--accent)] text-white text-xs font-bold rounded-lg hover:bg-[var(--accent-hover)] transition shrink-0">
                                Buka
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if (($tab === 'all' || $tab === 'posts') && $hashtagPosts->count() > 0)
            <div class="card-elevation p-5 space-y-3">
                <h3 class="font-bold text-sm text-[var(--text-primary)] flex items-center gap-2">
                    <span class="material-symbols-outlined text-[var(--accent)]">tag</span>
                    <span>Postingan dengan #{{ $hashtagName }}</span>
                </h3>
                <div class="space-y-3">
                    @foreach ($hashtagPosts as $p)
                        <livewire:post-item :post="$p" :key="'search-post-'.$p->id" />
                    @endforeach
                </div>
            </div>
        @endif
    @endif
</div>

