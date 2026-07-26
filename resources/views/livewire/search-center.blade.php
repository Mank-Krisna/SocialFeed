<div class="space-y-6">
    <!-- Header Search Bar Input Card -->
    <div class="card-elevation p-4 space-y-4">
        <div>
            <h2 class="text-xl font-bold text-[#1a1c1f]">Pencarian SocialFeed</h2>
            <p class="text-xs text-[#727785]">Cari teman, pengguna lain, atau grup komunitas.</p>
        </div>

        <div class="relative">
            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-[#727785] text-xl">search</span>
            <input 
                type="text" 
                wire:model.live.debounce.300ms="query" 
                placeholder="Ketik nama orang, username, atau nama grup..." 
                class="w-full pl-11 pr-4 py-3 bg-[#f3f3f7] focus:bg-white text-sm text-[#1a1c1f] rounded-xl border border-[#e2e2e6] focus:border-[#0058bc] focus:ring-1 focus:ring-[#0058bc] outline-none transition"
                autofocus
            />
        </div>

        <!-- Filter Tabs -->
        <div class="flex items-center gap-2 border-b border-[#e2e2e6] pt-2">
            <button wire:click="setTab('all')" class="px-4 py-2 border-b-2 font-bold text-xs transition {{ $tab === 'all' ? 'border-[#0058bc] text-[#0058bc]' : 'border-transparent text-[#727785] hover:text-[#1a1c1f]' }}">
                Semua Hasil
            </button>
            <button wire:click="setTab('users')" class="px-4 py-2 border-b-2 font-bold text-xs transition {{ $tab === 'users' ? 'border-[#0058bc] text-[#0058bc]' : 'border-transparent text-[#727785] hover:text-[#1a1c1f]' }}">
                Pengguna ({{ $users->count() }})
            </button>
            <button wire:click="setTab('groups')" class="px-4 py-2 border-b-2 font-bold text-xs transition {{ $tab === 'groups' ? 'border-[#0058bc] text-[#0058bc]' : 'border-transparent text-[#727785] hover:text-[#1a1c1f]' }}">
                Grup ({{ $groups->count() }})
            </button>
            @if ($hashtagName)
                <button wire:click="setTab('posts')" class="px-4 py-2 border-b-2 font-bold text-xs transition {{ $tab === 'posts' ? 'border-[#0058bc] text-[#0058bc]' : 'border-transparent text-[#727785] hover:text-[#1a1c1f]' }}">
                    Postingan ({{ $hashtagPosts->count() }})
                </button>
            @endif
        </div>
    </div>

    <!-- Search Results Content -->
    @if (strlen(trim($query)) === 0)
        <div class="card-elevation p-12 text-center space-y-2">
            <span class="material-symbols-outlined text-5xl text-[#727785]">search</span>
            <h3 class="font-bold text-base text-[#1a1c1f]">Mulai Pencarian</h3>
            <p class="text-xs text-[#727785]">Ketikkan kata kunci di kolom pencarian di atas.</p>
        </div>
    @elseif ($users->count() === 0 && $groups->count() === 0 && $hashtagPosts->count() === 0)
        <div class="card-elevation p-12 text-center space-y-2">
            <span class="material-symbols-outlined text-5xl text-[#727785]">search_off</span>
            <h3 class="font-bold text-base text-[#1a1c1f]">Tidak Ada Hasil</h3>
            <p class="text-xs text-[#727785]">Tidak ditemukan hasil untuk "{{ $query }}".</p>
        </div>
    @else
        <!-- Users Results -->
        @if (($tab === 'all' || $tab === 'users') && $users->count() > 0)
            <div class="card-elevation p-5 space-y-3">
                <h3 class="font-bold text-sm text-[#1a1c1f] flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#0058bc]">person</span>
                    <span>Pengguna</span>
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach ($users as $u)
                        <div class="p-3 border border-[#e2e2e6] rounded-xl flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <img src="{{ $u->avatar_url }}" class="w-10 h-10 rounded-full object-cover shrink-0" />
                                <div class="min-w-0">
                                    <a href="{{ route('profile.show', $u->username ?? $u->id) }}" wire:navigate class="font-bold text-xs text-[#1a1c1f] truncate block hover:underline">
                                        {{ $u->name }}
                                    </a>
                                    <p class="text-[11px] text-[#727785] truncate">{{ '@' . e($u->username_display) }}</p>
                                </div>
                            </div>
                            <a href="{{ route('profile.show', $u->username ?? $u->id) }}" wire:navigate class="px-3 py-1 bg-[#f3f3f7] hover:bg-[#0058bc] hover:text-white text-[#0058bc] text-xs font-bold rounded-lg transition shrink-0">
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
                <h3 class="font-bold text-sm text-[#1a1c1f] flex items-center gap-2">
                    <span class="material-symbols-outlined text-emerald-600">groups</span>
                    <span>Grup Komunitas</span>
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach ($groups as $g)
                        <div class="p-3 border border-[#e2e2e6] rounded-xl flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <img src="{{ $g->photo_url }}" class="w-10 h-10 rounded-xl object-cover shrink-0" />
                                <div class="min-w-0">
                                    <a href="{{ route('groups.show', $g->slug) }}" wire:navigate class="font-bold text-xs text-[#1a1c1f] truncate block hover:underline">
                                        {{ $g->name }}
                                    </a>
                                    <p class="text-[11px] text-[#727785] truncate">{{ $g->members_count }} Anggota · {{ ucfirst($g->type) }}</p>
                                </div>
                            </div>
                            <a href="{{ route('groups.show', $g->slug) }}" wire:navigate class="px-3 py-1 bg-[#0058bc] text-white text-xs font-bold rounded-lg hover:bg-[#004493] transition shrink-0">
                                Buka
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if (($tab === 'all' || $tab === 'posts') && $hashtagPosts->count() > 0)
            <div class="card-elevation p-5 space-y-3">
                <h3 class="font-bold text-sm text-[#1a1c1f] flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#0058bc]">tag</span>
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
